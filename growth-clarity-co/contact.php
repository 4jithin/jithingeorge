<?php
$pageTitle = 'Book a Revenue Growth Audit — Growth Clarity Co.';
$pageDescription = 'Book a 45-minute Revenue Growth Audit with Jithin George — diagnose your GTM, fix pipeline, reduce CAC, and build a system that scales.';
$activeNav = 'contact';
$extraHeadHtml = <<<HTML
<style>
  .contact-wrap { padding: 72px 0 100px; }
  .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: start; }
  .contact-form { display: flex; flex-direction: column; gap: 16px; }
  .contact-form label { font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: rgba(255,255,255,.4); margin-bottom: 6px; display: block; }
  .contact-form input, .contact-form select, .contact-form textarea {
    width: 100%; border: 1px solid var(--border-dark); background: rgba(255,255,255,.03); border-radius: 8px;
    padding: 13px 16px; color: var(--white); font-family: 'Instrument Sans', sans-serif; font-size: 14px;
  }
  .contact-form input:focus, .contact-form select:focus, .contact-form textarea:focus { outline: none; border-color: var(--green); }
  .contact-form textarea { resize: vertical; min-height: 90px; }
  .what-happens { border: 1px solid var(--border-dark); border-radius: 12px; padding: 24px; margin-top: 8px; }
  .what-happens-row { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border-dark); font-size: 13px; color: rgba(255,255,255,.6); }
  .what-happens-row:last-child { border-bottom: none; }
  .what-happens-row span { color: var(--green); font-weight: 700; flex-shrink: 0; width: 18px; }
  .form-success { display: none; text-align: center; padding: 40px 20px; }
  .form-success.show { display: block; }
  @media (max-width: 760px) { .contact-grid { grid-template-columns: 1fr; } }
</style>
HTML;
require __DIR__ . '/includes/header.php';
?>

<section class="contact-wrap">
  <div class="wrap">
    <div class="eyebrow">Book a call</div>
    <h1 style="font-size:38px;max-width:600px;margin-bottom:16px">Book a 45-minute Revenue Growth Audit, not a sales pitch.</h1>
    <p class="lede" style="margin-bottom:48px">We'll walk through your funnel data together and tell you the root cause live on the call — whether or not you ever become a client. If you've already run the diagnostic, mention it; we'll use the report as the starting point.</p>

    <div class="contact-grid">
      <div>
        <form class="contact-form" id="contactForm">
          <div>
            <label for="cName">Name</label>
            <input type="text" id="cName" name="name" placeholder="Your full name" required>
          </div>
          <div>
            <label for="cEmail">Work email</label>
            <input type="email" id="cEmail" name="email" placeholder="you@company.com" required>
          </div>
          <div>
            <label for="cCompany">Company</label>
            <input type="text" id="cCompany" name="company" placeholder="Company name" required>
          </div>
          <div>
            <label for="cArr">Current ARR</label>
            <select id="cArr" name="arr">
              <option value="">Select a range</option>
              <option>Pre-revenue or under $1M</option>
              <option>$1M&ndash;$5M (Series A)</option>
              <option>$5M&ndash;$12M (Series B)</option>
              <option>$12M&ndash;$20M (Series C)</option>
              <option>Above $20M</option>
            </select>
          </div>
          <div>
            <label for="cMsg">What's the most urgent GTM problem right now?</label>
            <textarea id="cMsg" name="message" placeholder="e.g. board asked why CAC is up 30% this quarter"></textarea>
          </div>
          <button type="submit" class="btn btn-green btn-lg" style="justify-content:center;margin-top:6px">Request the audit</button>
          <div style="font-size:11.5px;color:rgba(255,255,255,.3);line-height:1.5">We respond within one business day. No automated sequence afterward unless you opt in.</div>
        </form>
        <div class="form-success" id="formSuccess">
          <div class="eyebrow" style="justify-content:center">Request received</div>
          <h3 style="font-size:22px;margin-bottom:10px">We'll be in touch within one business day.</h3>
          <p style="font-size:14px;color:rgba(255,255,255,.5)">In the meantime, the GTM diagnostic gives you a head start — we'll use it as the basis for the call if you complete it first.</p>
          <a href="diagnostic.php" class="btn btn-outline-white btn-md" style="margin-top:18px">Take the diagnostic &rarr;</a>
        </div>
      </div>

      <div>
        <h3 style="font-size:18px;margin-bottom:6px">What happens after you submit</h3>
        <div class="what-happens">
          <div class="what-happens-row"><span>01</span>We review your submission (and diagnostic report, if you have one) before the call — no cold discovery questions wasting your 45 minutes.</div>
          <div class="what-happens-row"><span>02</span>We diagnose the root cause live, using your actual funnel data where you can share it.</div>
          <div class="what-happens-row"><span>03</span>If there's a fit, we recommend one tier and one starting service — never the full 21-service catalog.</div>
          <div class="what-happens-row"><span>04</span>If there isn't a fit, we'll tell you directly and point you to a better-suited resource.</div>
        </div>
        <div class="btn-row">
          <a href="https://www.linkedin.com/in/jithingeorge-marketing-strategist/" target="_blank" rel="noopener" class="btn-outline-white btn-md">Connect on LinkedIn</a>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // NOTE: this form is intentionally not wired to a backend yet — no lead
    // is currently persisted or emailed. Wire it to api/claim_submit.php's
    // pattern (validate + INSERT + webhook) if/when contact leads need to
    // be captured the same way diagnostic and claim leads are.
    document.getElementById('contactForm').style.display = 'none';
    document.getElementById('formSuccess').classList.add('show');
  });
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
