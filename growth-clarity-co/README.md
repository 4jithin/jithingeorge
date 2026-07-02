# Growth Clarity Co. — PHP/MySQL conversion

PHP + MySQL port of the GTM Diagnostic, Claim, and Contact pages. Originally
these were static HTML pages that called the Gemini API and a Zapier webhook
directly from client-side JavaScript, which meant the Gemini API key was
sitting in plain view in the page source. This version moves that logic
server-side and adds MySQL storage for leads.

## What changed vs. the original HTML

- **AI key no longer shipped to the browser.** `diagnostic.php` posts the
  visitor's answers to `api/diagnostic_submit.php`, which calls Gemini
  server-side using a key read from `config.php`. The browser never sees it.
- **Scores are recomputed server-side** from validated answers rather than
  trusted from the client, so they can't be tampered with before submission.
- **Leads are stored in MySQL** (`diagnostic_submissions`, `claim_submissions`)
  in addition to an optional Zapier dual-write, so you have a system of
  record even if Zapier/Sheets is unavailable or unconfigured.
- **Output is HTML-escaped** before it reaches the browser's `innerHTML`,
  closing off a stored-XSS path via the free-text field / AI output that
  existed in the original.
- `shared.css` / `partials.js` (referenced by the original pages but not
  supplied) were replaced with `assets/css/shared.css` and PHP includes
  (`includes/header.php` / `includes/footer.php`).
- `index.php` and `pricing.php` are minimal placeholder pages — the real
  homepage/pricing content wasn't part of what was converted. Swap in the
  real copy when you have it; just keep the tier names in `pricing.php` in
  sync with `lib/report.php`'s `gcc_suggested_tier()`.

## File layout

```
config.sample.php   → copy to config.php and fill in real values (gitignored)
db.php              → PDO connection helper
sql/schema.sql       → MySQL schema (diagnostic_submissions, claim_submissions)
lib/report.php       → scoring engine, AI prompt, Gemini call, template fallback
lib/webhook.php      → best-effort Zapier POST helper
api/diagnostic_submit.php → validates answers, scores + generates report, stores + returns JSON
api/claim_submit.php      → validates claim form, stores, returns JSON
includes/header.php, includes/footer.php → shared site chrome
assets/css/shared.css     → design tokens + buttons + nav/footer
diagnostic.php, claim.php, contact.php, index.php, pricing.php
```

## Setup

1. **Database**
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
2. **Config**
   ```bash
   cp config.sample.php config.php
   ```
   Then edit `config.php`:
   - `DB_HOST` / `DB_NAME` / `DB_USER` / `DB_PASS` — your MySQL credentials.
   - `GEMINI_API_KEY` — get a free key at aistudio.google.com/apikey. If left
     as the placeholder, the app silently uses the built-in template report
     instead of calling the AI (this is intentional — it fails safe).
   - `ZAPIER_DIAGNOSTIC_WEBHOOK` / `ZAPIER_CLAIM_WEBHOOK` — optional. Leave
     blank to skip the Zapier dual-write entirely.
3. **Requirements**: PHP 8+ with `pdo_mysql` and `curl` extensions enabled.
4. **Run locally**:
   ```bash
   php -S localhost:8000
   ```
   then visit `http://localhost:8000/claim.php` or `/diagnostic.php`.

## Notes

- `contact.php`'s form is intentionally left unwired to a backend, matching
  the original (it only showed a client-side success state). Wire it to a
  new `api/contact_submit.php` following the same pattern as
  `api/claim_submit.php` if you want those leads captured too.
- `config.php` is gitignored — don't commit real credentials or API keys.
