<?php
/**
 * Placeholder pricing page — linked from the diagnostic report's tier
 * callout ("See what's included →"). Replace with real tier copy/pricing
 * when it's ready; the three tier names below must stay in sync with
 * lib/report.php's gcc_suggested_tier() output (Entry Project, Core
 * Retainer, Fractional CMO).
 */
$pageTitle = 'Pricing & Engagement Tiers — Growth Clarity Co.';
$pageDescription = 'Three ways to work with Growth Clarity Co: Entry Project, Core Retainer, and Fractional CMO.';
$activeNav = 'pricing';
$extraHeadHtml = <<<HTML
<style>
  .tier-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 40px; }
  @media (max-width: 860px) { .tier-grid { grid-template-columns: 1fr; } }
  .tier-card { border: 1px solid var(--border-dark); border-radius: 16px; padding: 28px 26px; }
  .tier-card.is-featured { border-color: rgba(0,230,118,.3); background: rgba(0,230,118,.03); }
  .tier-card h3 { font-family: 'Bricolage Grotesque', sans-serif; font-size: 20px; font-weight: 800; margin-bottom: 6px; }
  .tier-card .tier-price { font-family: 'Bricolage Grotesque', sans-serif; font-size: 15px; color: var(--green); margin-bottom: 14px; }
  .tier-card ul { list-style: none; display: flex; flex-direction: column; gap: 10px; margin-bottom: 22px; padding: 0; }
  .tier-card li { font-size: 13.5px; color: rgba(255,255,255,.55); line-height: 1.5; padding-left: 16px; position: relative; }
  .tier-card li::before { content: '·'; color: var(--green); position: absolute; left: 0; }
</style>
HTML;
require __DIR__ . '/includes/header.php';
?>

<section class="wrap" style="padding:72px 0 100px">
  <div class="eyebrow">Pricing</div>
  <h1 style="font-family:'Bricolage Grotesque',sans-serif;font-size:clamp(28px,4vw,42px);font-weight:800;margin-bottom:16px">
    Three ways to work with us.
  </h1>
  <p class="lede">Your diagnostic report already suggests the tier that fits your stage and severity profile. Book a call and we'll confirm it against your actual funnel data.</p>

  <div class="tier-grid">
    <div class="tier-card">
      <h3>Entry Project</h3>
      <div class="tier-price">$1.2K&ndash;$5K one-time</div>
      <ul>
        <li>One root-cause playbook, scoped and delivered</li>
        <li>Best for a single, well-defined constraint</li>
        <li>Fixed timeline, fixed deliverable</li>
      </ul>
      <a href="contact.php" class="btn btn-outline-white btn-md">Book a call</a>
    </div>
    <div class="tier-card is-featured">
      <h3>Core Retainer</h3>
      <div class="tier-price">$2.4K&ndash;$10K / month</div>
      <ul>
        <li>Two or more critical GTM axes flagged</li>
        <li>Ongoing execution across playbooks, not just strategy</li>
        <li>Monthly reporting against your north-star targets</li>
      </ul>
      <a href="contact.php" class="btn btn-green btn-md">Book a call</a>
    </div>
    <div class="tier-card">
      <h3>Fractional CMO</h3>
      <div class="tier-price">$12K+ / month</div>
      <ul>
        <li>10+ person marketing team, or 4+ critical axes</li>
        <li>Full GTM ownership across strategy and execution</li>
        <li>Board-level reporting and roadmap ownership</li>
      </ul>
      <a href="contact.php" class="btn btn-outline-white btn-md">Book a call</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
