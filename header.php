<?php
$pageTitle = $pageTitle ?? 'VGi Cars | Buy and Sell Cars in South Africa';
$pageDescription = $pageDescription ?? 'VGi Cars is a trusted dealership for quality vehicles, including modern, premium, performance, and classic cars.';
$pageKeywords = $pageKeywords ?? 'cars for sale, buy cars, sell cars, used cars, premium cars, classic cars, performance cars, VGi Cars, Johannesburg car dealership';
$activePage = $activePage ?? '';
$contactHref = $contactHref ?? 'contact';
$buyCarHref = $buyCarHref ?? 'index#inventory';
$assetVersion = '20260723c';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$baseUrl = $scheme . '://' . $host;
$canonicalUrl = $canonicalUrl ?? ($baseUrl . $requestUri);
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$assetBase = ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '') ? '' : rtrim($scriptDir, '/');
$encodeUrlPath = static function (string $path): string {
  $path = str_replace('\\', '/', $path);
  $absolute = str_starts_with($path, '/');
  $path = trim($path, '/');
  if ($path === '') {
    return $absolute ? '' : '';
  }

  $encoded = implode('/', array_map('rawurlencode', explode('/', $path)));
  return ($absolute ? '/' : '') . $encoded;
};
$asset = static function (string $path) use ($assetBase, $encodeUrlPath): string {
  $path = ltrim(str_replace('\\', '/', $path), '/');
  $base = $assetBase === '' ? '' : $encodeUrlPath($assetBase);
  return ($base === '' ? '' : $base) . '/' . $encodeUrlPath($path);
};
$logoPath = $asset('images/vgilogo.png');
$ogImage = $ogImage ?? ($baseUrl . $logoPath);
$ogType = $ogType ?? 'website';
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$vgiBaseJs = $assetBase === '' ? '' : $encodeUrlPath($assetBase);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title><?= $escape($pageTitle) ?></title>
  <meta name="description" content="<?= $escape($pageDescription) ?>" />
  <meta name="keywords" content="<?= $escape($pageKeywords) ?>" />
  <meta name="robots" content="index,follow" />
  <link rel="canonical" href="<?= $escape($canonicalUrl) ?>" />
  <meta property="og:site_name" content="VGi Cars" />
  <meta property="og:locale" content="en_ZA" />
  <meta property="og:url" content="<?= $escape($canonicalUrl) ?>" />
  <meta property="og:type" content="<?= $escape($ogType) ?>" />
  <meta property="og:title" content="<?= $escape($pageTitle) ?>" />
  <meta property="og:description" content="<?= $escape($pageDescription) ?>" />
  <meta property="og:image" content="<?= $escape($ogImage) ?>" />
  <meta property="og:image:alt" content="VGi Cars Logo" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:url" content="<?= $escape($canonicalUrl) ?>" />
  <meta name="twitter:title" content="<?= $escape($pageTitle) ?>" />
  <meta name="twitter:description" content="<?= $escape($pageDescription) ?>" />
  <meta name="twitter:image" content="<?= $escape($ogImage) ?>" />
  <meta name="twitter:image:alt" content="VGi Cars Logo" />
  <link rel="icon" type="image/png" href="<?= $escape($logoPath) ?>" />
  <link rel="apple-touch-icon" href="<?= $escape($logoPath) ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="stylesheet" href="<?= $escape($asset('css/style.css')) ?>?v=<?= $escape($assetVersion) ?>" />
  <link rel="stylesheet" href="<?= $escape($asset('css/responsive.css')) ?>?v=<?= $escape($assetVersion) ?>" />
  <script>window.VGI_BASE = <?= json_encode($vgiBaseJs, JSON_UNESCAPED_SLASHES) ?>;</script>
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