<?php
/**
 * Copy this file to config.php and fill in real values.
 * config.php is gitignored — never commit real credentials.
 */

// ── Database ──────────────────────────────────────────────
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'growth_clarity');
define('DB_USER', 'db_user');
define('DB_PASS', 'change_me');
define('DB_CHARSET', 'utf8mb4');

// ── AI provider (Google Gemini) ──────────────────────────
// Get a free key (no card required) at aistudio.google.com/apikey.
// This key is only ever read server-side by lib/report.php — it is
// never sent to the browser, unlike the original client-side version.
define('GEMINI_API_KEY', 'YOUR_GEMINI_API_KEY_HERE');
define('GEMINI_MODEL', 'gemini-2.5-flash');

// ── Zapier webhooks (optional dual-write to Google Sheets) ──
// Leave blank ('') to disable — MySQL storage works either way.
define('ZAPIER_DIAGNOSTIC_WEBHOOK', '');
define('ZAPIER_CLAIM_WEBHOOK', '');
