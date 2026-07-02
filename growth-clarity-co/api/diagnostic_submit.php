<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../lib/report.php';
require_once __DIR__ . '/../lib/webhook.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON body']);
    exit;
}

// ── Validate contact fields ──
$name = trim((string) ($input['name'] ?? ''));
$email = trim((string) ($input['email'] ?? ''));
$company = trim((string) ($input['company'] ?? ''));

if ($name === '' || mb_strlen($name) > 150) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'A valid name is required.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'A valid work email is required.']);
    exit;
}
$company = mb_substr($company, 0, 150);

// ── Validate answers against the known option sets (whitelist) ──
$answersInput = $input['answers'] ?? [];
if (!is_array($answersInput)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Missing answers.']);
    exit;
}

$answers = [];
foreach (GCC_OPTION_SETS as $key => $allowed) {
    $val = (string) ($answersInput[$key] ?? '');
    if (!in_array($val, $allowed, true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => "Invalid or missing answer for '{$key}'."]);
        exit;
    }
    $answers[$key] = $val;
}
$answers['freetext'] = mb_substr(trim(strip_tags((string) ($answersInput['freetext'] ?? ''))), 0, 220);

// ── Compute authoritative scores server-side ──
$scores = gcc_compute_scores($answers);
$top    = gcc_top_problem($scores);
$hs     = gcc_compute_health_score($scores);
$tier   = gcc_suggested_tier($scores, $answers['team']);

// ── Generate the report (AI first, template fallback on any failure) ──
$prompt   = gcc_build_prompt($answers, $scores, $top, $tier, $hs, $name, $company !== '' ? $company : 'unknown');
$aiResult = gcc_call_gemini($prompt);

if ($aiResult !== null) {
    $clientReport  = $aiResult['client_report'];
    $internalBrief = $aiResult['internal_brief'];
    $aiGenerated   = true;
} else {
    $clientReport  = gcc_fallback_report($scores, $top, $tier, $hs, $answers);
    $internalBrief = gcc_fallback_internal_brief($answers, $top);
    $aiGenerated   = false;
}

// Escape all text before it ever reaches the browser's innerHTML.
// internal_brief never leaves the server (DB + webhook only), so it's left
// unescaped for readability in Google Sheets / the internal_brief column.
$clientReport = gcc_escape_report_strings($clientReport);

// ── Persist to MySQL ──
try {
    $stmt = gcc_db()->prepare(
        'INSERT INTO diagnostic_submissions
            (name, email, company, motion, arr, team, messaging, pipeline, cac, alignment, conversion, nrr, freetext,
             score_positioning, score_demand, score_cac, score_alignment, score_conversion, score_expansion,
             health_score, top_cluster, top_severity, suggested_tier, ai_generated, client_report, internal_brief, ip_address)
         VALUES
            (:name, :email, :company, :motion, :arr, :team, :messaging, :pipeline, :cac, :alignment, :conversion, :nrr, :freetext,
             :score_positioning, :score_demand, :score_cac, :score_alignment, :score_conversion, :score_expansion,
             :health_score, :top_cluster, :top_severity, :suggested_tier, :ai_generated, :client_report, :internal_brief, :ip_address)'
    );
    $stmt->execute([
        'name' => $name, 'email' => $email, 'company' => $company !== '' ? $company : null,
        'motion' => $answers['motion'], 'arr' => $answers['arr'], 'team' => $answers['team'],
        'messaging' => $answers['messaging'], 'pipeline' => $answers['pipeline'], 'cac' => $answers['cac'],
        'alignment' => $answers['alignment'], 'conversion' => $answers['conversion'], 'nrr' => $answers['nrr'],
        'freetext' => $answers['freetext'] !== '' ? $answers['freetext'] : null,
        'score_positioning' => $scores['positioning'], 'score_demand' => $scores['demand'], 'score_cac' => $scores['cac'],
        'score_alignment' => $scores['alignment'], 'score_conversion' => $scores['conversion'], 'score_expansion' => $scores['expansion'],
        'health_score' => $hs, 'top_cluster' => $top['key'], 'top_severity' => $top['score'], 'suggested_tier' => $tier,
        'ai_generated' => $aiGenerated ? 1 : 0,
        'client_report' => json_encode($clientReport), 'internal_brief' => json_encode($internalBrief),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
} catch (PDOException $e) {
    // Storage failure shouldn't block the prospect from seeing their report.
    error_log('gcc diagnostic_submit DB insert failed: ' . $e->getMessage());
}

// ── Dual-write to Zapier (Google Sheets mirror), best-effort ──
gcc_fire_webhook(ZAPIER_DIAGNOSTIC_WEBHOOK, [
    'type' => 'diagnostic_complete',
    'name' => $name, 'email' => $email, 'company' => $company,
    'motion' => $answers['motion'], 'arr' => $answers['arr'], 'team_size' => $answers['team'],
    'messaging' => $answers['messaging'], 'pipeline' => $answers['pipeline'], 'cac_trend' => $answers['cac'],
    'alignment' => $answers['alignment'], 'conversion' => $answers['conversion'], 'nrr' => $answers['nrr'],
    'freetext' => $answers['freetext'],
    'score_positioning' => $scores['positioning'], 'score_demand' => $scores['demand'], 'score_cac' => $scores['cac'],
    'score_alignment' => $scores['alignment'], 'score_conversion' => $scores['conversion'], 'score_expansion' => $scores['expansion'],
    'health_score' => $hs, 'top_cluster' => $top['key'], 'top_severity' => $top['score'],
    'critical_count' => gcc_critical_count($scores), 'suggested_tier' => $tier,
    'fit_score' => $internalBrief['fit_score'] ?? '', 'intent_score' => $internalBrief['intent_score'] ?? '',
    'timing_score' => $internalBrief['timing_score'] ?? '', 'opening_line' => $internalBrief['opening_line'] ?? '',
    'likely_objection' => $internalBrief['likely_objection'] ?? '', 'price_anchor' => $internalBrief['price_anchor'] ?? '',
    'disqualify' => $internalBrief['disqualify'] ?? 'false',
    'submitted_at' => gmdate('c'), 'source' => 'diagnostic.php',
]);

echo json_encode([
    'success'       => true,
    'client_report' => $clientReport,
    'scores'        => $scores,
    'top'           => $top,
    'tier'          => $tier,
    'health_score'  => $hs,
    'contact'       => ['name' => $name, 'email' => $email],
]);
