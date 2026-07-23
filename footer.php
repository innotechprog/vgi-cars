<?php
$footerId = $footerId ?? 'contact';
$footerIntro = $footerIntro ?? 'At VGi Cars, we specialize in distinctive vehicles presented with care, clarity, and a deep appreciation for automotive heritage.';
$footerWhyTitle = $footerWhyTitle ?? 'Why VGi';
$footerWhyItems = $footerWhyItems ?? [
    'Curated vehicle selection',
    'Trusted buying support',
    'Enthusiast-led service',
];
$footerScripts = $footerScripts ?? ['js/main.js?v=20260723b'];
$whatsAppHref = $whatsAppHref ?? 'https://wa.me/27789796523?text=Hello%20VGi%20Cars%2C%20I%20would%20like%20to%20chat%20about%20a%20vehicle.';

if (!isset($assetBase)) {
  $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
  $assetBase = ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '') ? '' : rtrim($scriptDir, '/');
}

if (!isset($asset) || !is_callable($asset)) {
  $asset = static function (string $path) use ($assetBase): string {
    $path = ltrim(str_replace('\\', '/', $path), '/');
    return ($assetBase === '' ? '' : $assetBase) . '/' . $path;
  };
}

if (!isset($settingsService)) {
  try {
    require_once __DIR__ . '/includes/bootstrap.php';
  } catch (Throwable $e) {
    // Footer should still render with defaults if settings are unavailable.
  }
}

$setting = static function (string $key, string $default = '') use (&$settingsService): string {
  if (!isset($settingsService) || !method_exists($settingsService, 'get')) {
    return $default;
  }

  $value = trim((string) $settingsService->get($key, $default));
  return $value !== '' ? $value : $default;
};

$profileFallback = [
  'phone_number' => '+27 78 979 6523',
  'email' => 'info@vgicars.co.za',
  'facebook' => '',
  'instagram' => '',
  'linkedin' => '',
];

if (isset($db) && $db instanceof PDO) {
  try {
    $stmt = $db->query("SELECT phone_number, email, facebook, instagram, linkedin FROM users WHERE role = 'admin' ORDER BY user_id ASC LIMIT 1");
    $row = $stmt ? $stmt->fetch() : false;
    if (is_array($row)) {
      foreach ($profileFallback as $key => $defaultValue) {
        $value = trim((string) ($row[$key] ?? ''));
        if ($value !== '') {
          $profileFallback[$key] = $value;
        }
      }
    }
  } catch (Throwable $e) {
    // Keep static fallbacks if lookup fails.
  }
}

$sitePhone = $setting('site_phone', '0789796523');
if ($sitePhone === '') {
  $sitePhone = $setting('company_phone', $profileFallback['phone_number']);
}

$siteEmail = $setting('site_contact_email', '');
if ($siteEmail === '') {
  $siteEmail = $setting('smtp_from_email', $profileFallback['email']);
}

$settingPhone = preg_replace('/[^0-9]/', '', $sitePhone);
if ($settingPhone === '') {
  $settingPhone = '27789796523';
}

$defaultWhatsapp = 'https://wa.me/' . $settingPhone . '?text=Hello%20VGi%20Cars%2C%20I%20would%20like%20to%20chat%20about%20a%20vehicle.';
$whatsAppHref = $defaultWhatsapp;
$facebookHref = $setting('social_facebook', '');
if ($facebookHref === '') {
  $facebookHref = $profileFallback['facebook'] !== '' ? $profileFallback['facebook'] : 'https://www.facebook.com/';
}

$instagramHref = $setting('social_instagram', '');
if ($instagramHref === '') {
  $instagramHref = $profileFallback['instagram'] !== '' ? $profileFallback['instagram'] : 'https://www.instagram.com/don.joachim60s?igsh=MWdqcG5obXphYjdqeQ==&utm_source=ig_contact_invite';
}

$linkedinHref = $setting('social_linkedin', '');
if ($linkedinHref === '') {
  $linkedinHref = $profileFallback['linkedin'] !== '' ? $profileFallback['linkedin'] : 'https://www.linkedin.com/';
}
$footerSocialLinks = $footerSocialLinks ?? [
  [
  'href' => $facebookHref,
    'label' => 'Facebook',
    'icon' => 'fa-brands fa-facebook-f',
    'class' => 'is-facebook',
  ],
  [
  'href' => $instagramHref,
    'label' => 'Instagram',
    'icon' => 'fa-brands fa-instagram',
    'class' => 'is-instagram',
  ],
  [
    'href' => $whatsAppHref,
    'label' => 'WhatsApp',
    'icon' => 'fa-brands fa-whatsapp',
    'class' => 'is-whatsapp',
  ],
  [
    'href' => $linkedinHref,
    'label' => 'LinkedIn',
    'icon' => 'fa-brands fa-linkedin-in',
    'class' => 'is-linkedin',
  ],
];
$footerLogoPath = $asset('images/vgilogo.png');
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$scriptSrc = static function (string $script) use ($asset): string {
  $script = trim($script);
  if ($script === '' || preg_match('#^(https?:)?//#i', $script)) {
    return $script;
  }

  $parts = explode('?', $script, 2);
  $path = $asset($parts[0]);
  return isset($parts[1]) ? ($path . '?' . $parts[1]) : $path;
};
?>
  <footer class="site-footer" id="<?= $escape($footerId) ?>">
    <div class="container footer-grid">
      <div>
        <a class="logo" href="index">
          <img src="<?= $escape($footerLogoPath) ?>" alt="VGi Cars" class="site-logo-image footer-logo-image" />
        </a>
        <p><?= $escape($footerIntro) ?></p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index">Home</a></li>
          <li><a href="about">About Us</a></li>
          <li><a href="index#inventory">Browse Cars</a></li>
          <li><a href="contact">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4><?= $escape($footerWhyTitle) ?></h4>
        <ul>
          <?php foreach ($footerWhyItems as $item): ?>
            <li><?= $escape((string) $item) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h4>Contact Us</h4>
        <ul>
          <li><i class="fa-solid fa-phone"></i> <a href="tel:=0789796523">+27789796523</a></li>
          <li><i class="fa-solid fa-envelope"></i> <a href="mailto:info@vgicars.co.za">info@vgicars.co.za</a></li>
          <li><i class="fa-solid fa-location-dot"></i> 25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001</li>
        </ul>
        <div class="footer-social-links" aria-label="VGi Cars social media links">
          <?php foreach ($footerSocialLinks as $social): ?>
            <?php
            $href = trim((string) ($social['href'] ?? ''));
            $label = trim((string) ($social['label'] ?? 'Social'));
            $icon = trim((string) ($social['icon'] ?? 'fa-solid fa-link'));
            $class = trim((string) ($social['class'] ?? ''));
            ?>
            <?php if ($href !== ''): ?>
              <a href="<?= $escape($href) ?>" class="social-link <?= $escape($class) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= $escape($label) ?>">
                <i class="<?= $escape($icon) ?>" aria-hidden="true"></i>
              </a>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <p class="copyright">&copy; <span id="yearNow"></span> VGi Cars. All Rights Reserved.</p>
    <p class="copyright developer-credit">Developed by <a href="https://ib-innovativesolutions.com/it-solutions" target="_blank" rel="noopener noreferrer">IB Innovative Solutions</a></p>
  </footer>

  <a class="whatsapp-float" href="<?= $escape($whatsAppHref) ?>" data-wa-number="<?= $escape($settingPhone) ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat to us on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
    <span>Chat to us</span>
  </a>

  <?php foreach ($footerScripts as $script): ?>
    <script src="<?= $escape($scriptSrc((string) $script)) ?>" defer></script>
  <?php endforeach; ?>
</body>
</html>