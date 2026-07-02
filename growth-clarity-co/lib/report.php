<?php
/**
 * Server-side port of the scoring engine + AI report generation that used
 * to live in diagnostic.html's client-side JavaScript. Keeping this on the
 * server means:
 *  - the Gemini API key never reaches the browser
 *  - scores/tier/root-cause are computed from validated input, not trusted
 *    from the client
 */

const GCC_OPTION_SETS = [
    'motion'     => ['plg', 'slg', 'hybrid'],
    'arr'        => ['pre', 'a', 'b', 'c', 'd'],
    'team'       => ['none', 'small', 'mid', 'large'],
    'messaging'  => ['yes_consistent', 'somewhat', 'no', 'unsure'],
    'pipeline'   => ['consistent', 'volatile', 'declining', 'none'],
    'cac'        => ['down', 'flat', 'up_moderate', 'up_sharp'],
    'alignment'  => ['aligned', 'mostly', 'no'],
    'conversion' => ['strong', 'average', 'weak', 'unsure'],
    'nrr'        => ['strong', 'some', 'flat', 'unsure'],
];

const GCC_TIE_BREAK_ORDER = ['positioning', 'alignment', 'demand', 'conversion', 'cac', 'expansion'];

const GCC_TOP_LABELS = [
    'positioning' => 'Positioning & ICP',
    'demand'      => 'Demand generation',
    'cac'         => 'CAC & unit economics',
    'alignment'   => 'Sales-mktg alignment',
    'conversion'  => 'Conversion',
    'expansion'   => 'Expansion & NRR',
];

const GCC_SERVICE_MAP = [
    'positioning' => ['primary' => 'Messaging House Rebuild',          'secondary' => 'ICP Audit & Hyper-ICP Definition'],
    'alignment'   => ['primary' => 'GTM Alignment Workshop',           'secondary' => 'Sales Enablement Kit System'],
    'demand'      => ['primary' => 'Demand Gen Engine Setup',          'secondary' => 'Content-Led Growth Engine'],
    'conversion'  => ['primary' => 'Website Conversion System (CRO)',  'secondary' => 'Lifecycle Email & Nurture System'],
    'cac'         => ['primary' => 'CAC / LTV Optimization Audit',     'secondary' => 'Lead Scoring Engine Rebuild'],
    'expansion'   => ['primary' => 'Expansion & NRR Revenue Playbook', 'secondary' => 'Product Adoption & Onboarding System'],
];

function gcc_cap(float $n): int
{
    return (int) max(1, min(5, round($n)));
}

/** @param array<string,string> $a validated answers keyed by question id */
function gcc_compute_scores(array $a): array
{
    $positioning = 1; $demand = 1; $cac = 1; $alignment = 1; $conversion = 1; $expansion = 1;

    if (($a['messaging'] ?? '') === 'no') $positioning += 4;
    elseif (($a['messaging'] ?? '') === 'unsure') $positioning += 3;
    elseif (($a['messaging'] ?? '') === 'somewhat') $positioning += 2;

    if (($a['pipeline'] ?? '') === 'volatile') $demand += 3;
    elseif (($a['pipeline'] ?? '') === 'declining') $demand += 4;
    elseif (($a['pipeline'] ?? '') === 'none') $demand += 4;

    if (($a['cac'] ?? '') === 'up_sharp') { $cac += 4; $positioning += 1; }
    elseif (($a['cac'] ?? '') === 'up_moderate') $cac += 2;

    if (($a['alignment'] ?? '') === 'no') $alignment += 4;
    elseif (($a['alignment'] ?? '') === 'mostly') $alignment += 2;

    if (($a['conversion'] ?? '') === 'weak') $conversion += 4;
    elseif (($a['conversion'] ?? '') === 'unsure') $conversion += 2;
    elseif (($a['conversion'] ?? '') === 'average') $conversion += 1;

    if (($a['nrr'] ?? '') === 'flat') $expansion += 4;
    elseif (($a['nrr'] ?? '') === 'unsure') $expansion += 3;
    elseif (($a['nrr'] ?? '') === 'some') $expansion += 2;

    return [
        'positioning' => gcc_cap($positioning),
        'demand'      => gcc_cap($demand),
        'cac'         => gcc_cap($cac),
        'alignment'   => gcc_cap($alignment),
        'conversion'  => gcc_cap($conversion),
        'expansion'   => gcc_cap($expansion),
    ];
}

function gcc_top_problem(array $scores): array
{
    $top = 'positioning';
    $max = -1;
    foreach (GCC_TIE_BREAK_ORDER as $k) {
        if ($scores[$k] >= $max) { $max = $scores[$k]; $top = $k; }
    }
    return ['key' => $top, 'label' => GCC_TOP_LABELS[$top], 'score' => $scores[$top]];
}

function gcc_compute_health_score(array $scores): int
{
    $w = ['positioning' => .22, 'demand' => .20, 'cac' => .15, 'alignment' => .15, 'conversion' => .18, 'expansion' => .10];
    $h = 0.0;
    foreach ($w as $k => $weight) {
        $h += ((5 - $scores[$k]) / 4) * $weight;
    }
    return (int) round($h * 100);
}

function gcc_critical_count(array $scores): int
{
    return count(array_filter($scores, fn($v) => $v >= 4));
}

function gcc_suggested_tier(array $scores, string $team): string
{
    $c = gcc_critical_count($scores);
    if ($team === 'large' || $c >= 4) return 'Fractional CMO';
    if ($c >= 2) return 'Core Retainer';
    return 'Entry Project';
}

/**
 * Recursively HTML-escapes every string leaf in an array/object structure.
 * The client renders this JSON via innerHTML, so this is the server-side
 * backstop against stored XSS — regardless of whether the string came from
 * the AI, the free-text field, or our own fallback templates.
 */
function gcc_escape_report_strings($data)
{
    if (is_array($data)) {
        return array_map('gcc_escape_report_strings', $data);
    }
    if (is_string($data)) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    return $data;
}

function gcc_build_prompt(array $a, array $scores, array $top, string $tier, int $hs, string $name, string $company): string
{
    $svc = GCC_SERVICE_MAP[$top['key']];

    return <<<PROMPT
You are a senior B2B SaaS GTM strategist with 15+ years running Growth Clarity Co — a revenue consulting firm specializing in Series A–C companies (\$1M–\$20M ARR). Produce an ELITE, insight-heavy GTM diagnostic report. Respond ONLY with valid JSON — no markdown, no preamble, no trailing text.

Diagnostic Input:
GTM motion: {$a['motion']} | ARR: {$a['arr']} | Marketing team: {$a['team']}
Messaging consistency: {$a['messaging']} | Pipeline pattern: {$a['pipeline']} | CAC trend: {$a['cac']}
Sales-mktg alignment: {$a['alignment']} | Conversion: {$a['conversion']} | NRR/expansion: {$a['nrr']}
Trigger (why they opened this): "{$a['freetext']}"

Computed severity scores (1=fine, 5=critical):
  positioning={$scores['positioning']}, demand={$scores['demand']}, cac={$scores['cac']},
  alignment={$scores['alignment']}, conversion={$scores['conversion']}, expansion={$scores['expansion']}

GTM Health Score: {$hs}/100
Root cause cluster (tie-break order — most upstream first): {$top['label']}
Primary service: {$svc['primary']} | Secondary: {$svc['secondary']}
Suggested tier: {$tier}
Prospect name: {$name} | Company: {$company}

Return this EXACT JSON structure. Be specific to their answers — no generic advice:

{
  "client_report": {
    "maturity_level": "5-8 word label e.g. 'Growth-stage, pre-systematic demand engine'",
    "executive_summary": "3-4 sentences addressing {$name} directly. Synthesize the pattern across all their answers. Name the primary constraint and its business consequence. Reference their specific answer patterns — not generic observations. End with what unlocking this means for revenue.",
    "symptoms": [
      {
        "label": "concise symptom title (4-7 words)",
        "severity": "critical|high|medium",
        "detail": "2 sentences. Connect their specific answer to the downstream business impact. Quantify where plausible."
      }
    ],
    "root_cause": {
      "primary": "1-2 sentences naming the primary root cause and why this is upstream of the other symptoms. Be specific to their answers.",
      "contributing_factors": ["3 specific contributing factors, each a short phrase with brief explanation"],
      "downstream_effects": ["2-3 downstream effects this root cause is creating in other scoring dimensions"],
      "why_fix_first": "1 sentence on the compounding ROI of fixing this before the downstream symptoms."
    },
    "priority_matrix": [
      {
        "rank": 1,
        "problem": "problem name",
        "impact": "H|M|L",
        "confidence": "H|M|L",
        "effort": "M|L|H",
        "ice_score": 1-10,
        "reasoning": "1 sentence on why this ranks here — specific to their data"
      }
    ],
    "playbooks": [
      {
        "name": "playbook name (one of: Positioning & Messaging Engine | Inbound Demand Engine | Pipeline Acceleration System | Activation & Retention Engine | Revenue Expansion Engine)",
        "objective": "1 sentence on what this achieves and why it's sequenced first",
        "key_actions": ["specific action 1 — not generic, tied to their answer pattern", "specific action 2", "specific action 3", "specific action 4"],
        "expected_outcome": "metric-driven outcome e.g. '+28% demo conversion in 45 days, -30% CAC in Q2'",
        "timeline": "e.g. 30 days | 45 days | 60 days",
        "growth_os_layer": "Strategy|Intelligence|Delivery|Execution|Automation|Education|Ecosystem"
      }
    ],
    "service_stack": [
      {
        "playbook_ref": "playbook name (must match above)",
        "services": [
          { "name": "specific service name", "scope": "1 sentence on exactly what's delivered", "price_usd": "range e.g. \$1.2K–\$3K | \$2.4K–\$6K/mo | \$12K+/mo" }
        ]
      }
    ],
    "roadmap": [
      { "phase": "Days 1–30", "title": "short phase title", "focus": "1 sentence", "milestones": ["deliverable 1", "deliverable 2", "deliverable 3"] },
      { "phase": "Days 31–60", "title": "short phase title", "focus": "1 sentence", "milestones": ["deliverable 1", "deliverable 2", "deliverable 3"] },
      { "phase": "Days 61–90", "title": "short phase title", "focus": "1 sentence", "milestones": ["deliverable 1", "deliverable 2", "deliverable 3"] }
    ],
    "north_star_targets": {
      "ltv_cac": "target ratio e.g. '3:1 → 5:1 within 2 quarters'",
      "demo_conversion": "target and timeframe",
      "cac_improvement": "target % reduction and timeframe",
      "payback_period": "target in months"
    }
  },
  "internal_brief": {
    "fit_score": "High|Medium|Low — based on ARR and team size vs our \$1M–\$20M ICP",
    "intent_score": "High|Medium|Low — based on urgency language in free-text and answer pattern",
    "timing_score": "Now|Soon|Later",
    "opening_line": "natural 1-sentence opener for the sales call",
    "discovery_questions": ["probing follow-up question 1", "probing follow-up question 2"],
    "likely_objection": "the single most likely objection this profile raises on a discovery call",
    "objection_counter": "1-sentence counter to that objection",
    "price_anchor": "specific opening price point and fallback if they push back",
    "disqualify": "true if ARR is pre-revenue or \$20M+ and should route to courses/product pitch, otherwise false",
    "upsell_flag": "natural add-on to mention near end of call, or empty string"
  }
}

RULES: Be insight-heavy. Reference their actual answers throughout. Produce 4-6 symptoms. Produce exactly 2-3 playbooks. Produce the service stack under each playbook with 2-3 services each. The report should feel like it was written by a strategist who read their CRM data, not generated from a template.
PROMPT;
}

/**
 * Calls Gemini server-side. Returns ['client_report' => ..., 'internal_brief' => ...]
 * on success, or null on any failure (caller should fall back to the template report).
 */
function gcc_call_gemini(string $prompt): ?array
{
    if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === '' || GEMINI_API_KEY === 'YOUR_GEMINI_API_KEY_HERE') {
        return null;
    }

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . GEMINI_MODEL . ':generateContent?key=' . GEMINI_API_KEY;
    $body = json_encode([
        'contents'         => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
        'generationConfig' => ['maxOutputTokens' => 3200, 'responseMimeType' => 'application/json'],
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 12,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);

    if ($response === false || $error || $httpCode !== 200) {
        return null;
    }

    $data = json_decode($response, true);
    $raw  = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
    if (!$raw) return null;

    $clean  = trim(preg_replace('/```json|```/', '', $raw));
    $parsed = json_decode($clean, true);
    if (!is_array($parsed) || !isset($parsed['client_report'], $parsed['internal_brief'])) {
        return null;
    }

    return $parsed;
}

function gcc_fallback_report(array $scores, array $top, string $tier, int $hs, array $a): array
{
    $svc = GCC_SERVICE_MAP[$top['key']];
    $motionLabel = $a['motion'] === 'plg' ? 'Product-led' : ($a['motion'] === 'slg' ? 'Sales-led' : 'Hybrid');
    $topLower = strtolower($top['label']);

    return [
        'maturity_level'    => "{$motionLabel}, {$topLower} is the binding constraint",
        'executive_summary' => "Based on your answers, {$top['label']} is your highest-severity GTM constraint — scoring {$top['score']}/5 on our severity scale. This sits upstream of the other symptoms you're experiencing, which means resolving it first will have a compounding positive effect on the rest. At your stage and team size, this is the most common pattern we see in companies that plateau despite good product-market signals.",
        'symptoms' => [
            ['label' => 'Pipeline inconsistency creating forecasting risk', 'severity' => 'high', 'detail' => 'Month-to-month pipeline variance makes revenue planning unreliable. This typically traces back to a demand system that runs on relationships and referrals rather than engineered, repeatable channels.'],
            ['label' => 'CAC trending in an unexplained direction', 'severity' => 'high', 'detail' => 'Unexplained CAC movement usually indicates the demand mix is shifting without intention — either channel saturation, lower-fit leads entering the funnel, or both.'],
            ['label' => 'Messaging inconsistency across customer touchpoints', 'severity' => 'medium', 'detail' => "When reps describe your product differently to prospects, you're essentially running multiple competing GTM strategies simultaneously — each one diluting the other's effectiveness."],
        ],
        'root_cause' => [
            'primary' => "{$top['label']} is the primary constraint, and it's upstream of the CAC and conversion issues you're experiencing. Fixing this first prevents pouring spend into a leaky funnel.",
            'contributing_factors' => ['Lack of documented ICP with behavioral triggers', 'Messaging built around features, not buyer outcomes', 'No structured experiment cycle to test and iterate demand channels'],
            'downstream_effects' => ['Elevated CAC from poor-fit lead acquisition', 'Lower conversion rates due to mismatched expectations', 'Sales cycle elongation from positioning ambiguity'],
            'why_fix_first' => 'Resolving the root cause eliminates the need to fix the downstream symptoms individually — the compounding ROI is 3-5x higher than treating each symptom in isolation.',
        ],
        'priority_matrix' => [
            ['rank' => 1, 'problem' => $top['label'], 'impact' => 'H', 'confidence' => 'H', 'effort' => 'M', 'ice_score' => 8, 'reasoning' => 'Highest severity score, upstream of all other symptoms — fix unlocks the rest.'],
            ['rank' => 2, 'problem' => 'Demand engine systematization', 'impact' => 'H', 'confidence' => 'M', 'effort' => 'M', 'ice_score' => 6, 'reasoning' => 'Replaces unpredictable referral-dependent pipeline with engineered, repeatable channels.'],
            ['rank' => 3, 'problem' => 'Sales-marketing alignment', 'impact' => 'M', 'confidence' => 'H', 'effort' => 'L', 'ice_score' => 7, 'reasoning' => 'Quick structural win that immediately improves lead quality perception and funnel velocity.'],
        ],
        'playbooks' => [
            ['name' => 'Positioning & Messaging Engine', 'objective' => 'Rebuild the ICP definition and messaging house so every rep, page, and campaign speaks with one voice.', 'key_actions' => ['Run ICP audit: map firmographic + behavioral triggers for top 20 best-fit customers', 'Build messaging house: problem statement, unique angle, proof hierarchy', 'Rewrite homepage hero and sales deck using outcome-led framing', 'Install win/loss interview process to continuously validate messaging'], 'expected_outcome' => '+30% message-market resonance in first 30 days, measurable in reply rate and demo conversion', 'timeline' => '30 days', 'growth_os_layer' => 'Strategy'],
            ['name' => 'Inbound Demand Engine', 'objective' => "Build a predictable, channel-diversified pipeline engine that doesn't depend on the founder's network.", 'key_actions' => ['Audit existing channels against ICP signal data', 'Build content → pipeline flywheel targeting problem-aware search terms', 'Set up lead scoring: fit + intent + timing dimensions', 'Launch 4-week experiment sprint: test 2 channels with $500 each'], 'expected_outcome' => 'Predictable pipeline within 60 days, CAC benchmarked by channel within 90 days', 'timeline' => '60 days', 'growth_os_layer' => 'Execution'],
        ],
        'service_stack' => [
            ['playbook_ref' => 'Positioning & Messaging Engine', 'services' => [
                ['name' => $svc['primary'], 'scope' => 'Full ICP audit, messaging house document, homepage rewrite, sales deck revision', 'price_usd' => $tier === 'Entry Project' ? '$1.2K–$4K' : '$2.4K–$8K'],
                ['name' => $svc['secondary'], 'scope' => 'Firmographic + behavioral ICP definition with scoring rubric', 'price_usd' => '$1.2K–$2.5K'],
            ]],
            ['playbook_ref' => 'Inbound Demand Engine', 'services' => [
                ['name' => 'Demand Gen Engine Setup', 'scope' => 'Channel architecture, lead scoring system, experiment sprint framework', 'price_usd' => $tier === 'Entry Project' ? '$2K–$5K' : '$3K–$8K/mo'],
                ['name' => 'Content-Led Growth Engine', 'scope' => 'Problem-aware keyword strategy, editorial calendar, 4 pillar articles', 'price_usd' => '$2K–$4K + retainer'],
            ]],
        ],
        'roadmap' => [
            ['phase' => 'Days 1–30', 'title' => 'Diagnose & align', 'focus' => 'Root cause validation and messaging foundation rebuild.', 'milestones' => ['ICP audit complete (win/loss interviews, best-fit customer map)', 'Messaging house drafted and reviewed with sales', 'Baseline metrics captured across all 6 axes']],
            ['phase' => 'Days 31–60', 'title' => 'Build the engine', 'focus' => 'Demand gen architecture and first channel experiments live.', 'milestones' => ['Lead scoring system implemented in CRM', 'First experiment sprint launched (2 channels)', 'New homepage messaging live and A/B tested']],
            ['phase' => 'Days 61–90', 'title' => 'Measure & scale', 'focus' => "Scale what's working, kill what isn't, close the alignment gaps.", 'milestones' => ['Channel CAC report: first vs second experiment sprint', 'Sales-marketing SLA documented and signed off', 'QBR deck with pipeline vs target, recommended Q4 investments']],
        ],
        'north_star_targets' => [
            'ltv_cac' => '3:1 → 5:1 within 2 quarters',
            'demo_conversion' => '+25% in 45 days',
            'cac_improvement' => '−30% in 2 quarters',
            'payback_period' => 'Under 18 months',
        ],
    ];
}

function gcc_fallback_internal_brief(array $a, array $top): array
{
    return [
        'fit_score' => 'Unknown — AI report generation failed, review answers manually',
        'intent_score' => 'Unknown',
        'timing_score' => 'Unknown',
        'opening_line' => !empty($a['freetext']) ? "They mentioned: \"{$a['freetext']}\"" : "Lead with their {$top['label']} score ({$top['score']}/5).",
        'discovery_questions' => [],
        'likely_objection' => '',
        'objection_counter' => '',
        'price_anchor' => '',
        'disqualify' => 'false',
        'upsell_flag' => '',
    ];
}
