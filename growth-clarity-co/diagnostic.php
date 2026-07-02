<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GTM Diagnostic — Growth Clarity Co.</title>
<meta name="description" content="A 10-question diagnostic that scores your positioning, demand generation, CAC, alignment, conversion, and expansion — then tells you the root cause, not just the symptom.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Geist:wght@800&family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/shared.css">
<style>
:root {
  --ink: #060f1c; --navy: #0B1F3A; --green: #00E676; --green-2: #1FE07A;
  --white: #F5F7FA; --slate: #4D6480; --red: #FF5050; --amber: #F0A830;
  --border-dark: rgba(255,255,255,.08); --ease: cubic-bezier(0.16,1,0.3,1);
}
* { box-sizing: border-box; }
body { background: var(--ink); color: var(--white); font-family: 'Instrument Sans', system-ui, sans-serif; padding-top: 64px; }

/* ── HEADER ── */
.diag-header {
  border-bottom: 1px solid var(--border-dark); position: fixed; top: 0; left: 0; right: 0;
  z-index: 999; background: rgba(6,15,28,.96); backdrop-filter: blur(20px); height: 64px;
  display: flex; align-items: center;
}
.diag-header-inner {
  max-width: 720px; margin: 0 auto; padding: 0 24px;
  display: flex; align-items: center; justify-content: space-between; width: 100%;
}
.step-indicator { font-size: 11px; color: rgba(255,255,255,.28); letter-spacing: .04em; text-transform: uppercase; }

/* Progress bar */
.progress-track { height: 2px; background: var(--border-dark); position: fixed; top: 64px; left: 0; right: 0; z-index: 998; overflow: hidden; }
.progress-fill { height: 100%; background: var(--green); transition: width .5s var(--ease); width: 0%; }

/* ── MAIN WRAP ── */
.diag-wrap { max-width: 680px; margin: 0 auto; padding: 80px 24px 100px; }

/* ── SCREENS ── */
.screen { display: none; }
.screen.active { display: block; animation: rise .5s var(--ease); }
@keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }

.eyebrow { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--green); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
.eyebrow::before { content: ''; width: 16px; height: 1.5px; background: var(--green); }

h1.q-title { font-size: clamp(24px,4vw,32px); line-height: 1.18; font-weight: 800; color: var(--white); margin-bottom: 12px; max-width: 560px; letter-spacing: -0.02em; font-family: 'Bricolage Grotesque', sans-serif; }
p.q-sub { font-size: 15px; color: rgba(255,255,255,.45); line-height: 1.65; max-width: 480px; margin-bottom: 36px; }

/* Options */
.option-grid { display: flex; flex-direction: column; gap: 10px; margin-bottom: 32px; }
.option {
  border: 1px solid var(--border-dark); background: rgba(255,255,255,.02); border-radius: 10px;
  padding: 17px 20px; text-align: left; cursor: pointer; font-size: 15px;
  color: var(--white); transition: all .18s ease; display: flex; align-items: center; justify-content: space-between; gap: 16px; width: 100%;
  font-family: 'Instrument Sans', sans-serif;
}
.option:hover { border-color: rgba(0,230,118,.3); background: rgba(0,230,118,.04); }
.option.selected { border-color: var(--green); background: rgba(0,230,118,.08); }
.option .opt-num { font-size: 11px; color: rgba(255,255,255,.28); flex-shrink: 0; font-family: 'Geist Mono', monospace; }
.option.selected .opt-num { color: var(--green); }

/* Text input */
.input-field {
  width: 100%; border: none; border-bottom: 1.5px solid var(--border-dark); background: transparent;
  font-family: 'Bricolage Grotesque', sans-serif; font-size: 22px; padding: 10px 2px; color: var(--white); margin-bottom: 8px;
}
.input-field:focus { outline: none; border-bottom-color: var(--green); }
.input-field::placeholder { color: rgba(255,255,255,.25); font-family: 'Instrument Sans', sans-serif; font-size: 16px; }
.field-hint { font-size: 11px; color: rgba(255,255,255,.28); margin-bottom: 32px; }

.nav-row-diag { display: flex; align-items: center; justify-content: space-between; margin-top: 20px; }
.btn-ghost { font-size: 12px; letter-spacing: .04em; text-transform: uppercase; color: rgba(255,255,255,.35); padding: 14px 4px; transition: color .2s; background: none; border: none; cursor: pointer; font-family: 'Instrument Sans', sans-serif; }
.btn-ghost:hover { color: var(--white); }
.btn-green-diag {
  display: inline-flex; align-items: center; gap: 10px;
  background: var(--green); color: var(--ink); border: none; border-radius: 9px;
  padding: 14px 22px; font-size: 14px; font-weight: 700; font-family: 'Instrument Sans', sans-serif;
  cursor: pointer; transition: opacity .2s, transform .15s; letter-spacing: .01em;
}
.btn-green-diag:hover { opacity: .9; transform: translateY(-1px); }

/* ── GATE ── */
.gate-card { border: 1.5px solid rgba(0,230,118,.25); border-radius: 16px; padding: 36px 32px; background: rgba(0,230,118,.03); }
.gate-preview { display: flex; gap: 24px; margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--border-dark); flex-wrap: wrap; }
.preview-stat { flex: 1; min-width: 100px; }
.preview-num { font-family: 'Bricolage Grotesque', sans-serif; font-size: 38px; font-weight: 800; color: var(--green); line-height: 1; }
.preview-num.warn { color: var(--red); }
.preview-num.caution { color: var(--amber); }
.preview-label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.3); margin-top: 6px; }
.blur-tease { filter: blur(5px); user-select: none; pointer-events: none; opacity: .55; margin-bottom: 24px; }
.gate-form { display: flex; flex-direction: column; gap: 12px; }
.gate-input {
  border: 1px solid var(--border-dark); background: rgba(255,255,255,.04); padding: 14px 16px; font-size: 15px;
  font-family: 'Instrument Sans', sans-serif; border-radius: 9px; color: var(--white); outline: none; transition: border-color .2s;
}
.gate-input:focus { border-color: rgba(0,230,118,.35); }
.gate-input::placeholder { color: rgba(255,255,255,.28); }
.gate-consent { font-size: 11.5px; color: rgba(255,255,255,.28); line-height: 1.55; }
.gate-err { font-size: 12px; color: var(--red); min-height: 16px; }

/* ── LOADING ── */
.loading-screen { text-align: center; padding: 64px 0; }
.loading-bar-track { width: 220px; height: 2px; background: var(--border-dark); margin: 24px auto 0; overflow: hidden; }
.loading-bar-fill { width: 30%; height: 100%; background: var(--green); animation: loadbar 1.8s ease-in-out infinite; }
@keyframes loadbar { 0% { transform: translateX(-100%); } 100% { transform: translateX(330%); } }
.loading-steps { margin-top: 16px; min-height: 18px; }
.loading-step { font-size: 12px; color: rgba(255,255,255,.38); font-family: 'Geist Mono', monospace; }

/* ── REPORT STYLES ── */
.report-header { padding-bottom: 24px; border-bottom: 2px solid var(--green); margin-bottom: 32px; }
.report-kicker { font-size: 11px; text-transform: uppercase; letter-spacing: .07em; color: var(--green); margin-bottom: 10px; font-weight: 700; }
.report-title { font-family: 'Bricolage Grotesque', sans-serif; font-size: 28px; font-weight: 800; color: var(--white); line-height: 1.2; margin-bottom: 6px; }
.report-meta-row { font-size: 12px; color: rgba(255,255,255,.3); margin-bottom: 20px; }

/* Health score banner */
.health-banner {
  border: 1px solid var(--border-dark); border-radius: 14px; padding: 22px 24px;
  display: flex; align-items: center; gap: 24px; margin-bottom: 28px; flex-wrap: wrap;
}
.health-score-block { flex-shrink: 0; }
.health-score-num {
  font-family: 'Bricolage Grotesque', sans-serif; font-size: 56px; font-weight: 800; line-height: 1;
}
.health-score-num.critical { color: var(--red); }
.health-score-num.caution  { color: var(--amber); }
.health-score-num.healthy  { color: var(--green); }
.health-score-label { font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.3); margin-top: 4px; }
.health-desc { flex: 1; min-width: 200px; }
.health-maturity { font-family: 'Bricolage Grotesque', sans-serif; font-size: 15px; font-weight: 700; color: var(--white); margin-bottom: 6px; }
.health-sub-text { font-size: 13px; color: rgba(255,255,255,.45); line-height: 1.6; }

/* 6-axis scorecard */
.scorecard { border: 1px solid var(--border-dark); border-radius: 14px; overflow: hidden; margin-bottom: 28px; }
.scorecard-header { padding: 14px 18px; background: rgba(255,255,255,.02); border-bottom: 1px solid var(--border-dark); }
.scorecard-title { font-family: 'Geist Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.35); }
.score-axis {
  display: flex; align-items: center; gap: 14px; padding: 14px 18px;
  border-bottom: 1px solid var(--border-dark);
}
.score-axis:last-child { border-bottom: none; }
.score-axis.is-root { background: rgba(0,230,118,.04); }
.score-axis-label { width: 160px; flex-shrink: 0; }
.score-axis-name { font-size: 13px; color: var(--white); font-weight: 500; }
.score-axis-sub { font-size: 11px; color: rgba(255,255,255,.3); margin-top: 2px; }
.score-axis-bar { flex: 1; position: relative; }
.score-bar-track { height: 6px; background: rgba(255,255,255,.06); border-radius: 3px; overflow: hidden; position: relative; }
.score-bar-fill { height: 100%; border-radius: 3px; transition: width .8s var(--ease); }
.score-bar-fill.critical { background: linear-gradient(90deg, #b13c3c, var(--red)); }
.score-bar-fill.caution  { background: linear-gradient(90deg, #b9831a, var(--amber)); }
.score-bar-fill.healthy  { background: linear-gradient(90deg, var(--green-2), var(--green)); }
.score-bench-marker {
  position: absolute; top: -4px; width: 2px; height: 14px; background: rgba(255,255,255,.15); border-radius: 1px;
}
.score-axis-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.score-val { font-family: 'Bricolage Grotesque', sans-serif; font-size: 18px; font-weight: 800; width: 36px; text-align: right; }
.score-val.critical { color: var(--red); }
.score-val.caution  { color: var(--amber); }
.score-val.healthy  { color: var(--green); }
.score-badge { font-size: 10px; font-family: 'Geist Mono', monospace; padding: 3px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: .05em; }
.score-badge.critical { background: rgba(255,80,80,.15); color: var(--red); }
.score-badge.caution  { background: rgba(240,168,48,.12); color: var(--amber); }
.score-badge.healthy  { background: rgba(0,230,118,.1); color: var(--green); }
.root-tag { font-size: 10px; font-family: 'Geist Mono', monospace; color: var(--green); background: rgba(0,230,118,.1); padding: 2px 7px; border-radius: 4px; margin-left: 4px; }

/* Section blocks */
.r-section { margin-bottom: 32px; }
.r-section-head {
  font-family: 'Bricolage Grotesque', sans-serif; font-size: 18px; font-weight: 700; color: var(--white);
  margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid var(--border-dark);
  display: flex; align-items: center; gap: 10px;
}
.r-section-num { font-family: 'Geist Mono', monospace; font-size: 11px; color: rgba(255,255,255,.2); }
.r-body { font-size: 14.5px; line-height: 1.78; color: rgba(255,255,255,.62); }
.r-body p { margin-bottom: 12px; }
.r-body strong { color: var(--white); }

/* Executive summary */
.exec-summary {
  background: rgba(255,255,255,.02); border: 1px solid var(--border-dark); border-left: 3px solid var(--green);
  border-radius: 0 10px 10px 0; padding: 18px 20px; margin-bottom: 28px;
}
.exec-summary-label { font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: var(--green); margin-bottom: 8px; font-weight: 700; font-family: 'Geist Mono', monospace; }
.exec-summary-text { font-size: 15px; line-height: 1.72; color: rgba(255,255,255,.72); }

/* Symptoms */
.symptom-list { display: flex; flex-direction: column; gap: 0; }
.symptom-item {
  display: flex; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border-dark); align-items: flex-start;
}
.symptom-item:last-child { border-bottom: none; }
.sev-badge {
  font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; padding: 3px 7px; border-radius: 4px;
  flex-shrink: 0; margin-top: 2px; white-space: nowrap; font-family: 'Geist Mono', monospace;
}
.sev-critical { background: rgba(255,80,80,.15); color: var(--red); }
.sev-high     { background: rgba(240,168,48,.12); color: var(--amber); }
.sev-medium   { background: rgba(255,255,255,.06); color: rgba(255,255,255,.45); }
.symptom-content {}
.symptom-label { font-size: 14px; font-weight: 600; color: var(--white); margin-bottom: 4px; }
.symptom-detail { font-size: 13px; color: rgba(255,255,255,.5); line-height: 1.65; }

/* Root cause */
.root-cause-block {
  background: rgba(0,230,118,.03); border: 1px solid rgba(0,230,118,.15); border-radius: 12px; padding: 22px 22px; margin-bottom: 10px;
}
.root-primary { font-size: 15px; color: var(--white); line-height: 1.65; margin-bottom: 16px; }
.root-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 560px) { .root-grid { grid-template-columns: 1fr; } }
.root-sub-card { background: rgba(255,255,255,.03); border: 1px solid var(--border-dark); border-radius: 9px; padding: 14px 15px; }
.root-sub-label { font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.3); margin-bottom: 8px; font-family: 'Geist Mono', monospace; }
.root-sub-list { display: flex; flex-direction: column; gap: 6px; }
.root-sub-item { font-size: 12.5px; color: rgba(255,255,255,.55); line-height: 1.5; display: flex; gap: 8px; }
.root-sub-item::before { content: '·'; color: var(--green); flex-shrink: 0; }
.root-fix-first { font-size: 13px; color: rgba(0,230,118,.8); margin-top: 14px; font-style: italic; }

/* Priority matrix */
.priority-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
.priority-table th { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.3); font-weight: 600; padding: 8px 10px; border-bottom: 1px solid var(--border-dark); text-align: left; font-family: 'Geist Mono', monospace; }
.priority-table td { font-size: 13px; color: rgba(255,255,255,.65); padding: 12px 10px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: top; }
.priority-table tr:last-child td { border-bottom: none; }
.priority-table .rank-cell { font-family: 'Bricolage Grotesque', sans-serif; font-size: 22px; font-weight: 800; color: rgba(255,255,255,.15); text-align: center; }
.ice-score { font-family: 'Bricolage Grotesque', sans-serif; font-size: 18px; font-weight: 800; color: var(--green); }
.ice-letter { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: 700; margin-right: 2px; font-family: 'Geist Mono', monospace; }
.ice-H { background: rgba(0,230,118,.12); color: var(--green); }
.ice-M { background: rgba(240,168,48,.1); color: var(--amber); }
.ice-L { background: rgba(255,255,255,.06); color: rgba(255,255,255,.4); }
.priority-problem { font-size: 13px; color: var(--white); font-weight: 600; margin-bottom: 3px; }
.priority-reason { font-size: 12px; color: rgba(255,255,255,.4); line-height: 1.5; }

/* Playbooks */
.playbook-card {
  border: 1px solid var(--border-dark); border-radius: 12px; padding: 22px 22px; margin-bottom: 14px;
  transition: border-color .2s;
}
.playbook-card:hover { border-color: rgba(0,230,118,.2); }
.playbook-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 12px; flex-wrap: wrap; }
.playbook-name { font-family: 'Bricolage Grotesque', sans-serif; font-size: 17px; font-weight: 700; color: var(--white); }
.playbook-meta { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.playbook-layer { font-size: 10px; color: var(--green); border: 1px solid rgba(0,230,118,.25); border-radius: 4px; padding: 3px 8px; font-family: 'Geist Mono', monospace; letter-spacing: .04em; }
.playbook-timeline { font-size: 10px; color: rgba(255,255,255,.35); border: 1px solid var(--border-dark); border-radius: 4px; padding: 3px 8px; font-family: 'Geist Mono', monospace; }
.playbook-objective { font-size: 14px; color: rgba(255,255,255,.55); line-height: 1.6; margin-bottom: 14px; }
.playbook-actions-label { font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.3); margin-bottom: 9px; font-family: 'Geist Mono', monospace; }
.playbook-actions { display: flex; flex-direction: column; gap: 7px; margin-bottom: 14px; }
.pa-item { display: flex; gap: 10px; font-size: 13px; color: rgba(255,255,255,.6); line-height: 1.5; }
.pa-num { font-family: 'Geist Mono', monospace; font-size: 10px; color: var(--green); flex-shrink: 0; margin-top: 2px; }
.playbook-outcome {
  background: rgba(0,230,118,.04); border: 1px solid rgba(0,230,118,.12); border-radius: 8px;
  padding: 10px 13px; font-size: 12.5px; color: rgba(0,230,118,.75); line-height: 1.55;
}
.playbook-outcome::before { content: '→ '; font-weight: 700; }

/* Service stack */
.service-stack { margin-bottom: 32px; }
.service-playbook-group { margin-bottom: 20px; }
.service-group-title { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 10px; font-family: 'Geist Mono', monospace; }
.service-item {
  display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: baseline;
  padding: 12px 15px; border: 1px solid var(--border-dark); border-radius: 8px; margin-bottom: 8px;
}
.service-name { font-size: 14px; color: var(--white); font-weight: 500; margin-bottom: 3px; }
.service-scope { font-size: 12px; color: rgba(255,255,255,.38); line-height: 1.5; }
.service-price { font-family: 'Bricolage Grotesque', sans-serif; font-size: 14px; font-weight: 700; color: var(--green); white-space: nowrap; text-align: right; }

/* Roadmap */
.roadmap-track { border-left: 2px solid rgba(0,230,118,.2); padding-left: 24px; margin-left: 4px; }
.roadmap-phase { margin-bottom: 24px; position: relative; }
.roadmap-phase::before { content: ''; position: absolute; left: -29px; top: 5px; width: 10px; height: 10px; border-radius: 50%; background: var(--green); box-shadow: 0 0 0 3px rgba(0,230,118,.12); }
.roadmap-days { font-family: 'Geist Mono', monospace; font-size: 10px; color: var(--green); margin-bottom: 5px; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }
.roadmap-title { font-family: 'Bricolage Grotesque', sans-serif; font-size: 16px; font-weight: 700; color: var(--white); margin-bottom: 5px; }
.roadmap-focus { font-size: 13.5px; color: rgba(255,255,255,.5); line-height: 1.6; margin-bottom: 10px; }
.roadmap-milestones { display: flex; flex-direction: column; gap: 6px; }
.roadmap-ms { display: flex; gap: 10px; font-size: 12.5px; color: rgba(255,255,255,.45); line-height: 1.5; }
.roadmap-ms::before { content: '✓'; color: var(--green); font-size: 11px; flex-shrink: 0; margin-top: 1px; }

/* North star */
.northstar-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media (max-width: 480px) { .northstar-grid { grid-template-columns: 1fr; } }
.ns-card { border: 1px solid var(--border-dark); border-radius: 9px; padding: 14px 16px; }
.ns-label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.3); margin-bottom: 6px; font-family: 'Geist Mono', monospace; }
.ns-val { font-family: 'Bricolage Grotesque', sans-serif; font-size: 20px; font-weight: 800; color: var(--green); }
.ns-desc { font-size: 12px; color: rgba(255,255,255,.35); margin-top: 3px; }

/* Tier callout */
.tier-callout {
  border: 1px solid var(--border-dark); border-radius: 10px; padding: 18px 20px; margin-bottom: 28px;
  display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;
}
.tier-label { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: rgba(255,255,255,.35); }
.tier-val { font-family: 'Bricolage Grotesque', sans-serif; font-size: 20px; font-weight: 800; color: var(--green); margin-top: 2px; }
.btn-outline-white {
  display: inline-flex; align-items: center; gap: 8px; color: var(--white); border: 1px solid rgba(255,255,255,.2);
  border-radius: 8px; padding: 10px 18px; font-size: 13px; font-weight: 600; text-decoration: none;
  font-family: 'Instrument Sans', sans-serif; transition: border-color .2s, background .2s;
}
.btn-outline-white:hover { border-color: var(--white); background: rgba(255,255,255,.05); }

/* CTA section */
.cta-section { margin-top: 56px; }
.cta-primary { border: 1.5px solid rgba(0,230,118,.3); border-radius: 16px; padding: 36px 32px; background: rgba(0,230,118,.04); text-align: center; margin-bottom: 16px; }
.cta-primary h3 { font-family: 'Bricolage Grotesque', sans-serif; font-size: 22px; font-weight: 800; color: var(--white); margin-bottom: 10px; }
.cta-primary p { font-size: 14px; color: rgba(255,255,255,.45); line-height: 1.65; margin-bottom: 24px; max-width: 440px; margin-left: auto; margin-right: auto; }
.cta-secondary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 560px) { .cta-secondary-grid { grid-template-columns: 1fr; } }
.cta-secondary-card { border: 1px solid var(--border-dark); border-radius: 12px; padding: 22px 24px; }
.cta-secondary-card h4 { font-size: 14.5px; font-weight: 700; color: var(--white); margin-bottom: 6px; }
.cta-secondary-card p { font-size: 12.5px; color: rgba(255,255,255,.38); line-height: 1.55; margin-bottom: 14px; }

.err-box { border: 1px solid var(--red); background: rgba(255,80,80,.07); padding: 12px 16px; font-size: 13px; color: #ff8080; border-radius: 8px; margin-top: 12px; }

/* ── PRINT ── */
@media print {
  body { background: #fff !important; color: #1a1a1a !important; padding-top: 0 !important; }
  .diag-header, .progress-track, .no-print, .cta-section { display: none !important; }
  .screen { display: none !important; }
  .screen[data-screen="report"] { display: block !important; }
  .diag-wrap { max-width: 100% !important; padding: 20px !important; }
  h1, .report-title, .playbook-name, .roadmap-title, .r-section-head { color: #0B1F3A !important; }
  .r-body, .symptom-detail, .playbook-objective, .roadmap-focus { color: #444 !important; }
  .score-axis, .scorecard, .root-cause-block, .playbook-card, .service-item, .ns-card, .tier-callout { border-color: #ddd !important; }
  .btn-green-diag, .btn-outline-white { display: none !important; }
}
</style>
</head>
<body>

<!-- ── HEADER ── -->
<header class="diag-header">
  <div class="diag-header-inner">
    <a href="index.php" class="gcc-logo">
      <span class="star">&#10022;</span>
      <span class="w-growth">Growth</span><span class="w-clarity">Clarity</span><span class="w-co">Co.</span>
    </a>
    <div class="step-indicator" id="stepIndicator">Step 1 of 10</div>
  </div>
</header>
<div class="progress-track"><div class="progress-fill" id="progressFill"></div></div>

<main class="diag-wrap">

  <!-- INTRO -->
  <section class="screen active" data-screen="intro">
    <div class="eyebrow">GTM Diagnostic &middot; 4 minutes &middot; Free</div>
    <h1 class="q-title">Find out which of six GTM problems is actually costing you pipeline.</h1>
    <p class="q-sub">Ten quick questions about your stage, motion, and funnel. We score positioning, demand, CAC, alignment, conversion, and expansion — then map root cause, not just symptoms.</p>
    <button class="btn-green-diag" onclick="goNext()">Start the diagnostic <span>→</span></button>
  </section>

  <!-- Q1 -->
  <section class="screen" data-screen="q1">
    <div class="eyebrow">Company basics</div>
    <h1 class="q-title">How do you currently sell?</h1>
    <p class="q-sub">This determines which channel and funnel benchmarks apply to you.</p>
    <div class="option-grid" id="opts-motion"></div>
  </section>

  <!-- Q2 -->
  <section class="screen" data-screen="q2">
    <div class="eyebrow">Company stage</div>
    <h1 class="q-title">What's your current ARR?</h1>
    <p class="q-sub">Series A–C ($1M–$20M ARR) is our core diagnostic range — outside this, the benchmarks shift significantly.</p>
    <div class="option-grid" id="opts-arr"></div>
  </section>

  <!-- Q3 -->
  <section class="screen" data-screen="q3">
    <div class="eyebrow">Company stage</div>
    <h1 class="q-title">How big is your marketing function?</h1>
    <p class="q-sub">Include founders doing marketing part-time.</p>
    <div class="option-grid" id="opts-team"></div>
  </section>

  <!-- Q4 -->
  <section class="screen" data-screen="q4">
    <div class="eyebrow">Positioning &amp; ICP</div>
    <h1 class="q-title">If a prospect asked "why you and not a competitor," would every rep give the same answer?</h1>
    <p class="q-sub">Ask two reps separately if you're not sure. Inconsistency here is more common than founders expect.</p>
    <div class="option-grid" id="opts-messaging"></div>
  </section>

  <!-- Q5 -->
  <section class="screen" data-screen="q5">
    <div class="eyebrow">Demand generation</div>
    <h1 class="q-title">How would you describe your pipeline month to month?</h1>
    <p class="q-sub">Be honest — this is one of the strongest signals in the entire diagnostic.</p>
    <div class="option-grid" id="opts-pipeline"></div>
  </section>

  <!-- Q6 -->
  <section class="screen" data-screen="q6">
    <div class="eyebrow">CAC &amp; unit economics</div>
    <h1 class="q-title">Over the last two quarters, has your CAC...</h1>
    <p class="q-sub">Compare cost per closed-won customer, not cost per lead. Can you fully explain the move?</p>
    <div class="option-grid" id="opts-cac"></div>
  </section>

  <!-- Q7 -->
  <section class="screen" data-screen="q7">
    <div class="eyebrow">Sales &amp; marketing alignment</div>
    <h1 class="q-title">Do sales and marketing agree on what a qualified lead looks like?</h1>
    <p class="q-sub">Disagreement here is the single most common silent killer of pipeline velocity.</p>
    <div class="option-grid" id="opts-alignment"></div>
  </section>

  <!-- Q8 -->
  <section class="screen" data-screen="q8">
    <div class="eyebrow">Conversion</div>
    <h1 class="q-title">Of the demos or trials you run, roughly what share turn into paying customers?</h1>
    <p class="q-sub">PLG: think trial-to-paid. Sales-led: think demo-to-close. Hybrid: use whichever is higher stakes.</p>
    <div class="option-grid" id="opts-conversion"></div>
  </section>

  <!-- Q9 -->
  <section class="screen" data-screen="q9">
    <div class="eyebrow">Expansion &amp; NRR</div>
    <h1 class="q-title">Is expansion revenue (upsell, cross-sell) a meaningful part of your growth?</h1>
    <p class="q-sub">Healthy SaaS targets 110%+ net revenue retention. Below 100% means you're shrinking your existing base.</p>
    <div class="option-grid" id="opts-nrr"></div>
  </section>

  <!-- Q10 -->
  <section class="screen" data-screen="q10">
    <div class="eyebrow">Open context</div>
    <h1 class="q-title">In one sentence, what made you open this diagnostic today?</h1>
    <p class="q-sub">Optional — but this is the single most useful field for understanding your urgency and context.</p>
    <input type="text" class="input-field" id="freeTextInput" placeholder="e.g. our board asked why CAC is up 30% this quarter" maxlength="220">
    <div class="field-hint">Press Enter or click below to continue</div>
    <div class="nav-row-diag">
      <button class="btn-ghost" onclick="goBack()">← Back</button>
      <button class="btn-green-diag" onclick="goNext()">See my results <span>→</span></button>
    </div>
  </section>

  <!-- GATE -->
  <section class="screen" data-screen="gate">
    <div class="eyebrow">Your diagnostic is ready</div>
    <h1 class="q-title">Here's a preview of what we found.</h1>
    <div class="gate-card">
      <div class="gate-preview" id="gatePreview"></div>
      <div class="blur-tease">
        <p style="font-size:14px;line-height:1.7;color:rgba(255,255,255,.6);margin-bottom:12px"><strong style="color:var(--white)">Root cause:</strong> Your combination of answers points to an upstream problem that's quietly compounding across multiple dimensions. Most companies at your stage fix this before touching any channel spend, because—</p>
        <p style="font-size:14px;line-height:1.7;color:rgba(255,255,255,.6)"><strong style="color:var(--white)">Recommended service:</strong> sequenced against your highest-severity axis, with an estimated 90-day roadmap and—</p>
      </div>
      <div id="gateFormWrap">
        <div class="gate-form" id="gateForm">
          <input type="text" class="gate-input" id="nameInput" placeholder="Your name">
          <input type="email" class="gate-input" id="emailInput" placeholder="Work email">
          <button class="btn-green-diag" style="justify-content:center;margin-top:4px" onclick="submitGate()">Unlock my full GTM diagnostic →</button>
          <div class="gate-consent">We'll send your full report here and occasional GTM frameworks from Jithin. Unsubscribe anytime.</div>
          <div id="gateError" class="gate-err"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- LOADING -->
  <section class="screen" data-screen="loading">
    <div class="loading-screen">
      <div class="eyebrow" style="justify-content:center">Generating your diagnostic</div>
      <h1 class="q-title" style="font-size:22px;text-align:center;max-width:100%">Mapping your answers against the Growth OS pattern library</h1>
      <div class="loading-bar-track"><div class="loading-bar-fill"></div></div>
      <div class="loading-steps"><div class="loading-step" id="loadingLine">Classifying your company profile...</div></div>
    </div>
  </section>

  <!-- REPORT -->
  <section class="screen" data-screen="report">
    <div id="reportContent"></div>
  </section>

</main>

<script>
/* ============================================================
 All AI generation and Zapier delivery now happen server-side in
 api/diagnostic_submit.php — this page no longer holds an API key
 or webhook URL. It only computes a *preview* score client-side
 (for the gate screen, before the user has given contact details)
 and keeps a same-shape fallback renderer in case our own API is
 unreachable (e.g. the visitor is offline).
============================================================ */

/* ── STATE ── */
const state = {
  step: 0,
  screens: ['intro','q1','q2','q3','q4','q5','q6','q7','q8','q9','q10','gate','loading','report'],
  answers: {},
  contact: {},
};

/* ── LOAD CLAIM DATA (from claim.php) ── */
const prospect = (() => { try { return JSON.parse(localStorage.getItem('gcc_prospect') || '{}'); } catch(e) { return {}; } })();

/* ── OPTION SETS ── */
const optionSets = {
  motion: [
    { v: 'plg',    label: 'Self-serve / product-led (low-touch signup)' },
    { v: 'slg',    label: 'Sales-led (demos, sales cycles, contracts)' },
    { v: 'hybrid', label: 'Hybrid — both self-serve and sales-assisted' },
  ],
  arr: [
    { v: 'pre', label: 'Pre-revenue or under $1M ARR' },
    { v: 'a',   label: '$1M–$5M ARR (Series A range)' },
    { v: 'b',   label: '$5M–$12M ARR (Series B range)' },
    { v: 'c',   label: '$12M–$20M ARR (Series C range)' },
    { v: 'd',   label: 'Above $20M ARR' },
  ],
  team: [
    { v: 'none',  label: 'No dedicated marketing — founder-led' },
    { v: 'small', label: '1–3 person marketing team' },
    { v: 'mid',   label: '4–10 person marketing team' },
    { v: 'large', label: '10+ person marketing team' },
  ],
  messaging: [
    { v: 'yes_consistent', label: 'Yes — every rep gives nearly the same answer' },
    { v: 'somewhat',       label: 'Roughly, but everyone phrases it differently' },
    { v: 'no',             label: "No — I'd expect very different answers" },
    { v: 'unsure',         label: "Honestly, I'm not sure" },
  ],
  pipeline: [
    { v: 'consistent', label: 'Predictable — roughly the same every month' },
    { v: 'volatile',   label: 'Volatile — great month, then a dead one' },
    { v: 'declining',  label: "Declining — it was fine, now it's shrinking" },
    { v: 'none',       label: "We don't have real pipeline yet" },
  ],
  cac: [
    { v: 'down',        label: 'Gone down — and we know why' },
    { v: 'flat',        label: 'Stayed roughly flat' },
    { v: 'up_moderate', label: "Gone up, but there's an explainable reason" },
    { v: 'up_sharp',    label: "Gone up and we can't fully explain why" },
  ],
  alignment: [
    { v: 'aligned', label: 'Yes — we have a shared, documented definition' },
    { v: 'mostly',  label: "Mostly, but it's informal and inconsistent" },
    { v: 'no',      label: "No — sales routinely says marketing's leads aren't good" },
  ],
  conversion: [
    { v: 'strong',  label: '25%+ — conversion is a genuine strength' },
    { v: 'average', label: '10–25% — roughly in line with benchmark' },
    { v: 'weak',    label: 'Under 10% — this feels low for our motion' },
    { v: 'unsure',  label: "We don't track this clearly" },
  ],
  nrr: [
    { v: 'strong', label: 'Yes — expansion is a tracked, meaningful lever' },
    { v: 'some',   label: "Some, but it's not systematic or repeatable" },
    { v: 'flat',   label: 'Barely — NRR is flat or below 100%' },
    { v: 'unsure', label: "We don't track this closely" },
  ],
};

/* ── RENDER OPTIONS ── */
function renderOptions(containerId, key, groupKey) {
  const el = document.getElementById(containerId);
  if (!el) return;
  el.innerHTML = '';
  optionSets[key].forEach((opt, i) => {
    const btn = document.createElement('button');
    btn.className = 'option';
    btn.innerHTML = `<span>${opt.label}</span><span class="opt-num">${String(i+1).padStart(2,'0')}</span>`;
    btn.onclick = () => { selectOption(groupKey, opt.v, btn, containerId); };
    el.appendChild(btn);
  });
}

function selectOption(gKey, val, btnEl, containerId) {
  document.querySelectorAll('#' + containerId + ' .option').forEach(o => o.classList.remove('selected'));
  btnEl.classList.add('selected');
  state.answers[gKey] = val;
  setTimeout(() => goNext(), 280);
}

renderOptions('opts-motion', 'motion', 'motion');
renderOptions('opts-arr', 'arr', 'arr');
renderOptions('opts-team', 'team', 'team');
renderOptions('opts-messaging', 'messaging', 'messaging');
renderOptions('opts-pipeline', 'pipeline', 'pipeline');
renderOptions('opts-cac', 'cac', 'cac');
renderOptions('opts-alignment', 'alignment', 'alignment');
renderOptions('opts-conversion', 'conversion', 'conversion');
renderOptions('opts-nrr', 'nrr', 'nrr');

/* ── NAVIGATION ── */
function showScreen(idx) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  const name = state.screens[idx];
  document.querySelector(`[data-screen="${name}"]`).classList.add('active');
  const qScr = state.screens.slice(1, 11);
  const qIdx = qScr.indexOf(name);
  if (qIdx >= 0) {
    document.getElementById('stepIndicator').textContent = `Step ${qIdx+1} of 10`;
    document.getElementById('progressFill').style.width = `${((qIdx+1)/10)*100}%`;
  } else if (name === 'intro') {
    document.getElementById('stepIndicator').textContent = 'Step 1 of 10';
    document.getElementById('progressFill').style.width = '0%';
  } else {
    document.getElementById('stepIndicator').textContent = 'Almost there';
    document.getElementById('progressFill').style.width = '100%';
  }
  window.scrollTo({ top: 0, behavior: 'smooth' });
  if (name === 'gate') { renderGatePreview(); prefillGate(); }
}

function goNext() {
  if (document.getElementById('freeTextInput')) {
    state.answers.freetext = document.getElementById('freeTextInput').value.trim();
  }
  if (state.step < state.screens.length - 1) { state.step++; showScreen(state.step); }
}
function goBack() {
  if (state.step > 0) { state.step--; showScreen(state.step); }
}

document.getElementById('freeTextInput').addEventListener('keydown', e => { if (e.key === 'Enter') goNext(); });

/* ── PRE-FILL GATE FROM claim.php ── */
function prefillGate() {
  if (prospect.name && prospect.email) {
    state.contact = { name: prospect.name, email: prospect.email, company: prospect.company || '' };
    document.getElementById('gateFormWrap').style.display = 'none';
    setTimeout(() => { goNext(); runDiagnostic(); }, 800);
  } else {
    if (prospect.name)  document.getElementById('nameInput').value  = prospect.name;
    if (prospect.email) document.getElementById('emailInput').value = prospect.email;
  }
}

/* ── CLIENT-SIDE SCORING ──
   Mirrors lib/report.php exactly. Used only for the gate-screen preview
   (before we have contact details to submit) and as an emergency
   fallback if the browser can't reach our own server at all. The
   authoritative scores/report always come from api/diagnostic_submit.php. */
const TIE_BREAK_ORDER = ['positioning','alignment','demand','conversion','cac','expansion'];

function computeScores() {
  const a = state.answers;
  let positioning = 1, demand = 1, cac = 1, alignment = 1, conversion = 1, expansion = 1;

  if      (a.messaging === 'no')             positioning += 4;
  else if (a.messaging === 'unsure')         positioning += 3;
  else if (a.messaging === 'somewhat')       positioning += 2;

  if      (a.pipeline === 'volatile')        demand += 3;
  else if (a.pipeline === 'declining')       demand += 4;
  else if (a.pipeline === 'none')            demand += 4;

  if      (a.cac === 'up_sharp')           { cac += 4; positioning += 1; }
  else if (a.cac === 'up_moderate')          cac += 2;

  if      (a.alignment === 'no')             alignment += 4;
  else if (a.alignment === 'mostly')         alignment += 2;

  if      (a.conversion === 'weak')          conversion += 4;
  else if (a.conversion === 'unsure')        conversion += 2;
  else if (a.conversion === 'average')       conversion += 1;

  if      (a.nrr === 'flat')                 expansion += 4;
  else if (a.nrr === 'unsure')               expansion += 3;
  else if (a.nrr === 'some')                 expansion += 2;

  const cap = n => Math.max(1, Math.min(5, Math.round(n)));
  return { positioning: cap(positioning), demand: cap(demand), cac: cap(cac), alignment: cap(alignment), conversion: cap(conversion), expansion: cap(expansion) };
}

function topProblem(scores) {
  const labels = { positioning: 'Positioning & ICP', demand: 'Demand generation', cac: 'CAC & unit economics', alignment: 'Sales-mktg alignment', conversion: 'Conversion', expansion: 'Expansion & NRR' };
  let top = 'positioning', max = -1;
  TIE_BREAK_ORDER.forEach(k => { if (scores[k] >= max) { max = scores[k]; top = k; } });
  return { key: top, label: labels[top], score: scores[top] };
}

function computeHealthScore(scores) {
  const w = { positioning: .22, demand: .20, cac: .15, alignment: .15, conversion: .18, expansion: .10 };
  let h = 0;
  Object.keys(w).forEach(k => { h += ((5 - scores[k]) / 4) * w[k]; });
  return Math.round(h * 100);
}

function healthClass(n) { return n >= 70 ? 'healthy' : n >= 45 ? 'caution' : 'critical'; }
function scoreClass(n)  { return n >= 4 ? 'critical' : n >= 2 ? 'caution' : 'healthy'; }
function scoreBadge(n)  { return n >= 4 ? 'Critical' : n >= 2 ? 'Caution' : 'Healthy'; }

function criticalCount(scores) { return Object.values(scores).filter(v => v >= 4).length; }

function suggestedTier(scores, team) {
  const c = criticalCount(scores);
  if (team === 'large' || c >= 4) return 'Fractional CMO';
  if (c >= 2)                      return 'Core Retainer';
  return 'Entry Project';
}

const SERVICE_MAP = {
  positioning: { primary: 'Messaging House Rebuild',               secondary: 'ICP Audit & Hyper-ICP Definition' },
  alignment:   { primary: 'GTM Alignment Workshop',                secondary: 'Sales Enablement Kit System' },
  demand:      { primary: 'Demand Gen Engine Setup',               secondary: 'Content-Led Growth Engine' },
  conversion:  { primary: 'Website Conversion System (CRO)',       secondary: 'Lifecycle Email & Nurture System' },
  cac:         { primary: 'CAC / LTV Optimization Audit',          secondary: 'Lead Scoring Engine Rebuild' },
  expansion:   { primary: 'Expansion & NRR Revenue Playbook',      secondary: 'Product Adoption & Onboarding System' },
};

/* ── GATE PREVIEW ── */
function renderGatePreview() {
  const scores = computeScores();
  const top    = topProblem(scores);
  const hs     = computeHealthScore(scores);
  const sClass = top.score >= 4 ? 'warn' : top.score >= 2 ? 'caution' : '';
  document.getElementById('gatePreview').innerHTML = `
    <div class="preview-stat">
      <div class="preview-num ${sClass}">${hs}/100</div>
      <div class="preview-label">GTM Health Score</div>
    </div>
    <div class="preview-stat">
      <div class="preview-num">${top.score}/5</div>
      <div class="preview-label">${top.label} severity</div>
    </div>
    <div class="preview-stat">
      <div class="preview-num">${criticalCount(scores)}</div>
      <div class="preview-label">Critical axes flagged</div>
    </div>
  `;
}

/* ── GATE SUBMIT ── */
function submitGate() {
  const name  = document.getElementById('nameInput').value.trim();
  const email = document.getElementById('emailInput').value.trim();
  const errEl = document.getElementById('gateError');
  errEl.innerHTML = '';
  if (!name)  { errEl.innerHTML = '<div class="err-box">We need your name to personalize the report.</div>'; return; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { errEl.innerHTML = '<div class="err-box">That email doesn\'t look right — double-check it.</div>'; return; }
  state.contact = { name, email };
  goNext();
  runDiagnostic();
}

/* ── LOADING MESSAGES ── */
const loadingMessages = [
  'Classifying your company profile...',
  'Scoring six GTM pain axes...',
  'Running root cause triangulation...',
  'Mapping upstream → downstream dependencies...',
  'Cross-referencing the Growth OS pattern library...',
  'Building your priority matrix...',
  'Generating playbook recommendations...',
  'Calculating your 90-day roadmap...',
  'Drafting your internal sales brief...',
];
let loadingInterval;
function startLoading() {
  let i = 0;
  document.getElementById('loadingLine').textContent = loadingMessages[0];
  loadingInterval = setInterval(() => { i = (i+1) % loadingMessages.length; document.getElementById('loadingLine').textContent = loadingMessages[i]; }, 1400);
}
function stopLoading() { clearInterval(loadingInterval); }

/* ── MAIN DIAGNOSTIC — submits to our own server, which owns the AI call
   and the Zapier dual-write. ── */
async function runDiagnostic() {
  startLoading();

  const payload = {
    name: state.contact.name,
    email: state.contact.email,
    company: state.contact.company || prospect.company || '',
    answers: state.answers,
  };

  try {
    const res = await fetch('api/diagnostic_submit.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    if (!res.ok || !data.success) throw new Error(data.error || 'Diagnostic submission failed');
    stopLoading();
    renderReport(data.client_report, data.scores, data.top, data.tier, data.health_score);
  } catch (err) {
    // Our server is unreachable (e.g. visitor is offline) — fall back to a
    // locally computed template report so they still get a usable result.
    stopLoading();
    const scores = computeScores();
    const top    = topProblem(scores);
    const tier   = suggestedTier(scores, state.answers.team);
    const hs     = computeHealthScore(scores);
    renderFallbackReport(scores, top, tier, hs);
  }
}

/* ── RENDER FULL ELITE REPORT ── */
function renderReport(r, scores, top, tier, hs) {
  const axisInfo = {
    positioning: { name: 'Positioning & ICP',        sub: 'Messaging, differentiation, ICP clarity' },
    demand:      { name: 'Demand generation',         sub: 'Pipeline consistency, channel mix' },
    cac:         { name: 'CAC & unit economics',      sub: 'Customer acquisition cost trend' },
    alignment:   { name: 'Sales-mktg alignment',      sub: 'Shared lead definition, handoff quality' },
    conversion:  { name: 'Conversion',                sub: 'Demo-to-close / trial-to-paid rate' },
    expansion:   { name: 'Expansion & NRR',           sub: 'Upsell, cross-sell, net revenue retention' },
  };
  const benchmarkPct = { positioning: 40, demand: 50, cac: 40, alignment: 35, conversion: 55, expansion: 45 };

  const scorecardRows = Object.keys(axisInfo).map(k => {
    const sc  = scores[k];
    const cls = scoreClass(sc);
    const pct = ((sc - 1) / 4) * 100;
    const isRoot = k === top.key;
    return `
      <div class="score-axis ${isRoot ? 'is-root' : ''}">
        <div class="score-axis-label">
          <div class="score-axis-name">${axisInfo[k].name}${isRoot ? ' <span class="root-tag">ROOT</span>' : ''}</div>
          <div class="score-axis-sub">${axisInfo[k].sub}</div>
        </div>
        <div class="score-axis-bar">
          <div class="score-bar-track">
            <div class="score-bar-fill ${cls}" style="width:${pct}%"></div>
            <div class="score-bench-marker" style="left:${benchmarkPct[k]}%"></div>
          </div>
        </div>
        <div class="score-axis-right">
          <div class="score-val ${cls}">${sc}/5</div>
          <div class="score-badge ${cls}">${scoreBadge(sc)}</div>
        </div>
      </div>`;
  }).join('');

  const symptomsHtml = (r.symptoms || []).map(s => `
    <div class="symptom-item">
      <div class="sev-badge sev-${s.severity || 'medium'}">${s.severity || 'medium'}</div>
      <div class="symptom-content">
        <div class="symptom-label">${s.label}</div>
        <div class="symptom-detail">${s.detail}</div>
      </div>
    </div>`).join('');

  const contribHtml  = (r.root_cause?.contributing_factors || []).map(f => `<div class="root-sub-item">${f}</div>`).join('');
  const downstreamHtml = (r.root_cause?.downstream_effects || []).map(d => `<div class="root-sub-item">${d}</div>`).join('');

  const pmHtml = (r.priority_matrix || []).map(pm => `
    <tr>
      <td class="rank-cell">${pm.rank}</td>
      <td><div class="priority-problem">${pm.problem}</div><div class="priority-reason">${pm.reasoning}</div></td>
      <td><span class="ice-letter ice-${pm.impact}">I:${pm.impact}</span> <span class="ice-letter ice-${pm.confidence}">C:${pm.confidence}</span> <span class="ice-letter ice-${pm.effort}">E:${pm.effort}</span></td>
      <td><div class="ice-score">${pm.ice_score}</div></td>
    </tr>`).join('');

  const playbooksHtml = (r.playbooks || []).map(p => `
    <div class="playbook-card">
      <div class="playbook-top">
        <div class="playbook-name">${p.name}</div>
        <div class="playbook-meta">
          <span class="playbook-layer">${p.growth_os_layer}</span>
          <span class="playbook-timeline">${p.timeline}</span>
        </div>
      </div>
      <div class="playbook-objective">${p.objective}</div>
      <div class="playbook-actions-label">Key actions</div>
      <div class="playbook-actions">
        ${(p.key_actions || []).map((a, i) => `<div class="pa-item"><span class="pa-num">0${i+1}</span><span>${a}</span></div>`).join('')}
      </div>
      <div class="playbook-outcome">${p.expected_outcome}</div>
    </div>`).join('');

  const serviceHtml = (r.service_stack || []).map(sg => `
    <div class="service-playbook-group">
      <div class="service-group-title">${sg.playbook_ref}</div>
      ${(sg.services || []).map(sv => `
        <div class="service-item">
          <div>
            <div class="service-name">${sv.name}</div>
            <div class="service-scope">${sv.scope}</div>
          </div>
          <div class="service-price">${sv.price_usd}</div>
        </div>`).join('')}
    </div>`).join('');

  const roadmapHtml = (r.roadmap || []).map(ph => `
    <div class="roadmap-phase">
      <div class="roadmap-days">${ph.phase}</div>
      <div class="roadmap-title">${ph.title}</div>
      <div class="roadmap-focus">${ph.focus}</div>
      <div class="roadmap-milestones">
        ${(ph.milestones || []).map(m => `<div class="roadmap-ms">${m}</div>`).join('')}
      </div>
    </div>`).join('');

  const ns = r.north_star_targets || {};
  const nsHtml = `
    <div class="northstar-grid">
      <div class="ns-card"><div class="ns-label">LTV : CAC target</div><div class="ns-val">${ns.ltv_cac || '3:1 → 5:1'}</div><div class="ns-desc">Healthy SaaS minimum is 3:1</div></div>
      <div class="ns-card"><div class="ns-label">Demo conversion</div><div class="ns-val">${ns.demo_conversion || '+25%'}</div><div class="ns-desc">vs your current baseline</div></div>
      <div class="ns-card"><div class="ns-label">CAC improvement</div><div class="ns-val">${ns.cac_improvement || '−30%'}</div><div class="ns-desc">Within 2 quarters</div></div>
      <div class="ns-card"><div class="ns-label">Payback period</div><div class="ns-val">${ns.payback_period || '< 18 mo'}</div><div class="ns-desc">Target for Series A–C</div></div>
    </div>`;

  const hc = healthClass(hs);

  document.getElementById('reportContent').innerHTML = `
    <div class="report-header" id="reportPrintHeader">
      <div class="report-kicker">GTM Diagnostic Report</div>
      <div class="report-title">Prepared for ${state.contact.name}</div>
      <div class="report-meta-row">${new Date().toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'})} &middot; Growth Clarity Co. &middot; ${state.contact.company || ''}</div>
      <button class="btn-outline-white no-print" style="margin-top:18px;font-size:13px" onclick="window.print()">↓ Download PDF report</button>
    </div>

    <div class="health-banner">
      <div class="health-score-block">
        <div class="health-score-num ${hc}">${hs}</div>
        <div class="health-score-label">GTM Health Score / 100</div>
      </div>
      <div class="health-desc">
        <div class="health-maturity">${r.maturity_level}</div>
        <div class="health-sub-text">${hc === 'critical' ? 'Multiple critical constraints identified. Immediate structural fix required before any channel investment.' : hc === 'caution' ? 'Core mechanics need tightening. Strong upside available with the right sequencing.' : 'Solid GTM foundation. Optimise for efficiency and scale.'}</div>
      </div>
    </div>

    <!-- 6-AXIS SCORECARD -->
    <div class="scorecard">
      <div class="scorecard-header"><div class="scorecard-title">6-axis GTM scorecard — benchmarked vs $1M to $100M+ SaaS &nbsp;·&nbsp; White marker = healthy-range threshold</div></div>
      ${scorecardRows}
    </div>

    <!-- EXECUTIVE SUMMARY -->
    <div class="exec-summary">
      <div class="exec-summary-label">Executive summary</div>
      <div class="exec-summary-text">${r.executive_summary}</div>
    </div>

    <!-- SYMPTOMS -->
    <div class="r-section">
      <div class="r-section-head"><span class="r-section-num">01</span> What your answers reveal</div>
      <div class="symptom-list">${symptomsHtml}</div>
    </div>

    <!-- ROOT CAUSE -->
    <div class="r-section">
      <div class="r-section-head"><span class="r-section-num">02</span> Root cause analysis</div>
      <div class="root-cause-block">
        <div class="root-primary">${r.root_cause?.primary || ''}</div>
        <div class="root-grid">
          <div class="root-sub-card">
            <div class="root-sub-label">Contributing factors</div>
            <div class="root-sub-list">${contribHtml}</div>
          </div>
          <div class="root-sub-card">
            <div class="root-sub-label">Downstream effects</div>
            <div class="root-sub-list">${downstreamHtml}</div>
          </div>
        </div>
        <div class="root-fix-first">${r.root_cause?.why_fix_first || ''}</div>
      </div>
    </div>

    <!-- PRIORITY MATRIX -->
    <div class="r-section">
      <div class="r-section-head"><span class="r-section-num">03</span> Priority matrix <span style="font-size:12px;font-family:'Geist Mono',monospace;color:rgba(255,255,255,.3);font-weight:400;margin-left:6px">ICE scored: Impact · Confidence · Effort</span></div>
      <div style="overflow-x:auto">
        <table class="priority-table">
          <thead><tr><th>#</th><th>Problem</th><th>ICE</th><th>Score</th></tr></thead>
          <tbody>${pmHtml}</tbody>
        </table>
      </div>
    </div>

    <!-- TIER CALLOUT -->
    <div class="tier-callout">
      <div><div class="tier-label">Suggested engagement tier</div><div class="tier-val">${tier}</div></div>
      <a href="pricing.php" class="btn-outline-white">See what's included →</a>
    </div>

    <!-- PLAYBOOKS -->
    <div class="r-section">
      <div class="r-section-head"><span class="r-section-num">04</span> Recommended playbooks</div>
      ${playbooksHtml}
    </div>

    <!-- SERVICE STACK -->
    <div class="r-section">
      <div class="r-section-head"><span class="r-section-num">05</span> Service stack &amp; pricing</div>
      <div class="service-stack">${serviceHtml}</div>
    </div>

    <!-- ROADMAP -->
    <div class="r-section">
      <div class="r-section-head"><span class="r-section-num">06</span> Your 90-day execution roadmap</div>
      <div class="roadmap-track">${roadmapHtml}</div>
    </div>

    <!-- NORTH STAR -->
    <div class="r-section">
      <div class="r-section-head"><span class="r-section-num">07</span> North star targets</div>
      ${nsHtml}
    </div>

    <!-- CTA -->
    <div class="cta-section no-print">
      <div class="cta-primary">
        <h3>Want us to build this with you?</h3>
        <p>Book a 45-minute Revenue Growth Audit and we'll walk through this exact report together — using it as the starting point, not a cold discovery call.</p>
        <a href="contact.php" class="btn-green-diag" style="display:inline-flex;justify-content:center">Schedule a Call to discuss →</a>
      </div>
      <div class="cta-secondary-grid">
        <div class="cta-secondary-card">
          <h4>Not ready for a call yet?</h4>
          <p>Your report has been sent to ${state.contact.email}. We'll also send 1-2 relevant frameworks based on your ${top.label.toLowerCase()} score — not a generic drip.</p>
          <a href="https://www.linkedin.com/newsletters/marketingled-newsletter-7415340728588926976/" target="_blank" rel="noopener" class="btn-outline-white">Subscribe to the newsletter</a>
        </div>
        <div class="cta-secondary-card">
          <h4>See the thinking behind this score?</h4>
          <p>Jithin breaks down diagnostics like this on LinkedIn most weeks — real patterns from real engagements, not theory.</p>
          <a href="https://www.linkedin.com/in/jithingeorge-marketing-strategist/" target="_blank" rel="noopener" class="btn-outline-white">Connect on LinkedIn</a>
        </div>
      </div>
    </div>
  `;

  document.querySelector('[data-screen="report"]').classList.add('active');
  document.querySelectorAll('.screen').forEach(s => { if (!s.classList.contains('active')) s.style.display = 'none'; });
}

/* ── EMERGENCY FALLBACK (only when api/diagnostic_submit.php itself is
   unreachable — normal AI-vs-template fallback happens server-side) ── */
function renderFallbackReport(scores, top, tier, hs) {
  const svc = SERVICE_MAP[top.key];
  const r = {
    maturity_level: `${state.answers.motion === 'plg' ? 'Product-led' : state.answers.motion === 'slg' ? 'Sales-led' : 'Hybrid'}, ${top.label.toLowerCase()} is the binding constraint`,
    executive_summary: `Based on your answers, ${top.label} is your highest-severity GTM constraint — scoring ${top.score}/5 on our severity scale. This sits upstream of the other symptoms you're experiencing, which means resolving it first will have a compounding positive effect on the rest. At your stage and team size, this is the most common pattern we see in companies that plateau despite good product-market signals.`,
    symptoms: [
      { label: 'Pipeline inconsistency creating forecasting risk', severity: 'high', detail: 'Month-to-month pipeline variance makes revenue planning unreliable. This typically traces back to a demand system that runs on relationships and referrals rather than engineered, repeatable channels.' },
      { label: 'CAC trending in an unexplained direction', severity: 'high', detail: 'Unexplained CAC movement usually indicates the demand mix is shifting without intention — either channel saturation, lower-fit leads entering the funnel, or both.' },
      { label: 'Messaging inconsistency across customer touchpoints', severity: 'medium', detail: "When reps describe your product differently to prospects, you're essentially running multiple competing GTM strategies simultaneously — each one diluting the other's effectiveness." },
    ],
    root_cause: {
      primary: `${top.label} is the primary constraint, and it's upstream of the CAC and conversion issues you're experiencing. Fixing positioning and ICP clarity first prevents pouring spend into a leaky funnel.`,
      contributing_factors: ['Lack of documented ICP with behavioral triggers', 'Messaging built around features, not buyer outcomes', 'No structured experiment cycle to test and iterate demand channels'],
      downstream_effects: ['Elevated CAC from poor-fit lead acquisition', 'Lower conversion rates due to mismatched expectations', 'Sales cycle elongation from positioning ambiguity'],
      why_fix_first: 'Resolving the root cause eliminates the need to fix the downstream symptoms individually — the compounding ROI is 3-5x higher than treating each symptom in isolation.',
    },
    priority_matrix: [
      { rank: 1, problem: top.label, impact: 'H', confidence: 'H', effort: 'M', ice_score: 8, reasoning: 'Highest severity score, upstream of all other symptoms — fix unlocks the rest.' },
      { rank: 2, problem: 'Demand engine systematization', impact: 'H', confidence: 'M', effort: 'M', ice_score: 6, reasoning: 'Replaces unpredictable referral-dependent pipeline with engineered, repeatable channels.' },
      { rank: 3, problem: 'Sales-marketing alignment', impact: 'M', confidence: 'H', effort: 'L', ice_score: 7, reasoning: 'Quick structural win that immediately improves lead quality perception and funnel velocity.' },
    ],
    playbooks: [
      { name: 'Positioning & Messaging Engine', objective: 'Rebuild the ICP definition and messaging house so every rep, page, and campaign speaks with one voice.', key_actions: ['Run ICP audit: map firmographic + behavioral triggers for top 20 best-fit customers', 'Build messaging house: problem statement, unique angle, proof hierarchy', 'Rewrite homepage hero and sales deck using outcome-led framing', 'Install win/loss interview process to continuously validate messaging'], expected_outcome: '+30% message-market resonance in first 30 days, measurable in reply rate and demo conversion', timeline: '30 days', growth_os_layer: 'Strategy' },
      { name: 'Inbound Demand Engine', objective: 'Build a predictable, channel-diversified pipeline engine that doesn\'t depend on the founder\'s network.', key_actions: ['Audit existing channels against ICP signal data', 'Build content → pipeline flywheel targeting problem-aware search terms', 'Set up lead scoring: fit + intent + timing dimensions', 'Launch 4-week experiment sprint: test 2 channels with $500 each'], expected_outcome: 'Predictable pipeline within 60 days, CAC benchmarked by channel within 90 days', timeline: '60 days', growth_os_layer: 'Execution' },
    ],
    service_stack: [
      { playbook_ref: 'Positioning & Messaging Engine', services: [
        { name: svc.primary, scope: 'Full ICP audit, messaging house document, homepage rewrite, sales deck revision', price_usd: tier === 'Entry Project' ? '$1.2K–$4K' : '$2.4K–$8K' },
        { name: svc.secondary, scope: 'Firmographic + behavioral ICP definition with scoring rubric', price_usd: '$1.2K–$2.5K' },
      ]},
      { playbook_ref: 'Inbound Demand Engine', services: [
        { name: 'Demand Gen Engine Setup', scope: 'Channel architecture, lead scoring system, experiment sprint framework', price_usd: tier === 'Entry Project' ? '$2K–$5K' : '$3K–$8K/mo' },
        { name: 'Content-Led Growth Engine', scope: 'Problem-aware keyword strategy, editorial calendar, 4 pillar articles', price_usd: '$2K–$4K + retainer' },
      ]},
    ],
    roadmap: [
      { phase: 'Days 1–30', title: 'Diagnose & align', focus: 'Root cause validation and messaging foundation rebuild.', milestones: ['ICP audit complete (win/loss interviews, best-fit customer map)', 'Messaging house drafted and reviewed with sales', 'Baseline metrics captured across all 6 axes'] },
      { phase: 'Days 31–60', title: 'Build the engine', focus: 'Demand gen architecture and first channel experiments live.', milestones: ['Lead scoring system implemented in CRM', 'First experiment sprint launched (2 channels)', 'New homepage messaging live and A/B tested'] },
      { phase: 'Days 61–90', title: 'Measure & scale', focus: 'Scale what\'s working, kill what isn\'t, close the alignment gaps.', milestones: ['Channel CAC report: first vs second experiment sprint', 'Sales-marketing SLA documented and signed off', 'QBR deck with pipeline vs target, recommended Q4 investments'] },
    ],
    north_star_targets: { ltv_cac: '3:1 → 5:1 within 2 quarters', demo_conversion: '+25% in 45 days', cac_improvement: '−30% in 2 quarters', payback_period: 'Under 18 months' },
  };

  renderReport(r, scores, top, tier, hs);
}
</script>
</body>
</html>
