<?php
$pageTitle = $pageTitle ?? 'VGi Cars';
$pageDescription = $pageDescription ?? 'VGi Cars premium vintage car showroom and dealership.';
$activePage = $activePage ?? '';
$contactHref = $contactHref ?? 'contact';
$buyCarHref = $buyCarHref ?? 'index#inventory';
$assetVersion = '20260720c';
$logoPath = 'images/vgilogo.png';
$ogImage = $ogImage ?? $logoPath;
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $escape($pageTitle) ?></title>
  <meta name="description" content="<?= $escape($pageDescription) ?>" />
  <meta property="og:title" content="<?= $escape($pageTitle) ?>" />
  <meta property="og:description" content="<?= $escape($pageDescription) ?>" />
  <meta property="og:image" content="<?= $escape($ogImage) ?>" />
  <meta property="og:type" content="website" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= $escape($pageTitle) ?>" />
  <meta name="twitter:description" content="<?= $escape($pageDescription) ?>" />
  <meta name="twitter:image" content="<?= $escape($ogImage) ?>" />
  <link rel="icon" type="image/png" href="<?= $escape($logoPath) ?>" />
  <link rel="apple-touch-icon" href="<?= $escape($logoPath) ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css?v=<?= $assetVersion ?>" />
  <link rel="stylesheet" href="css/responsive.css?v=<?= $assetVersion ?>" />
</head>
<body>
  <header class="site-header" id="siteHeader">
    <nav class="container nav-wrap">
      <a class="logo" href="index" aria-label="VGi Cars home">
        <img src="<?= $escape($logoPath) ?>" alt="VGi Cars" class="site-logo-image" />
        <span class="mobile-logo-text">Drive with pride</span>
      </a>
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
      </button>
      <ul class="nav-links" id="navLinks">
        <li><a<?= $activePage === 'home' ? ' class="active"' : '' ?> href="index">Home</a></li>
        <li><a<?= $activePage === 'about' ? ' class="active"' : '' ?> href="about">About Us</a></li>
        <li><a<?= $activePage === 'contact' ? ' class="active"' : '' ?> href="<?= $escape($contactHref) ?>">Contact</a></li>
      </ul>
      <a class="btn btn-outline nav-cta" href="<?= $escape($buyCarHref) ?>">Buy Car</a>
    </nav>
  </header>