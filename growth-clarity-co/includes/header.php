<?php
/**
 * Shared <head> + site header. Include after setting:
 *   $pageTitle, $pageDescription, $activeNav (one of: home|pricing|contact)
 */
$pageTitle       = $pageTitle ?? 'Growth Clarity Co.';
$pageDescription = $pageDescription ?? 'GTM consulting for Series A–C B2B SaaS companies.';
$activeNav       = $activeNav ?? '';

function gcc_nav_class(string $key, string $active): string
{
    return $key === $active ? 'is-active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Geist:wght@800&family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/shared.css">
<?= $extraHeadHtml ?? '' ?>
</head>
<body>

<header class="site-header">
  <div class="site-header-inner">
    <a href="index.php" class="gcc-logo">
      <span class="star">&#10022;</span>
      <span class="w-growth">Growth</span><span class="w-clarity">Clarity</span><span class="w-co">Co.</span>
    </a>
    <nav class="site-nav">
      <a href="index.php" class="<?= gcc_nav_class('home', $activeNav) ?>">Home</a>
      <a href="pricing.php" class="<?= gcc_nav_class('pricing', $activeNav) ?>">Pricing</a>
      <a href="diagnostic.php" class="<?= gcc_nav_class('diagnostic', $activeNav) ?>">Free Diagnostic</a>
      <a href="contact.php" class="<?= gcc_nav_class('contact', $activeNav) ?>">Contact</a>
    </nav>
  </div>
</header>
