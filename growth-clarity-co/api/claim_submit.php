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

$name    = trim((string) ($input['name'] ?? ''));
$email   = trim((string) ($input['email'] ?? ''));
$company = trim((string) ($input['company'] ?? ''));
$role    = (string) ($input['role'] ?? '');
$arr     = (string) ($input['arr'] ?? '');
$motion  = (string) ($input['motion'] ?? '');

$allowedRoles = ['founder', 'cmo', 'growth', 'sales', 'other'];
$allowedArr   = ['pre', 'sub1m', 'a', 'b', 'c', 'd'];
$allowedMotion = ['plg', 'slg', 'hybrid'];

if ($name === '' || mb_strlen($name) > 150) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please enter your name.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please enter a valid work email.']);
    exit;
}
if ($company === '' || mb_strlen($company) > 150) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please enter your company name.']);
    exit;
}
if (!in_array($role, $allowedRoles, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please select your role.']);
    exit;
}
if (!in_array($arr, $allowedArr, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please select your ARR range.']);
    exit;
}
if (!in_array($motion, $allowedMotion, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please select your GTM motion.']);
    exit;
}

try {
    $stmt = gcc_db()->prepare(
        'INSERT INTO claim_submissions (name, email, company, role, arr, motion, source, ip_address)
         VALUES (:name, :email, :company, :role, :arr, :motion, :source, :ip_address)'
    );
    $stmt->execute([
        'name' => $name, 'email' => $email, 'company' => $company,
        'role' => $role, 'arr' => $arr, 'motion' => $motion,
        'source' => 'claim.php', 'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
} catch (PDOException $e) {
    error_log('gcc claim_submit DB insert failed: ' . $e->getMessage());
}

gcc_fire_webhook(ZAPIER_CLAIM_WEBHOOK, [
    'type' => 'claim_form',
    'name' => $name, 'email' => $email, 'company' => $company,
    'role' => $role, 'arr' => $arr, 'motion' => $motion,
    'source' => 'claim.php', 'submitted_at' => gmdate('c'),
]);

echo json_encode([
    'success' => true,
    'prospect' => ['name' => $name, 'email' => $email, 'company' => $company, 'role' => $role, 'arr' => $arr, 'motion' => $motion],
]);
