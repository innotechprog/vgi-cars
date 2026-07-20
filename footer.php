<?php
$footerId = $footerId ?? 'contact';
$footerIntro = $footerIntro ?? 'At VGi Cars, we specialize in distinctive vehicles presented with care, clarity, and a deep appreciation for automotive heritage.';
$footerWhyTitle = $footerWhyTitle ?? 'Why VGi';
$footerWhyItems = $footerWhyItems ?? [
    'Curated vehicle selection',
    'Trusted buying support',
    'Enthusiast-led service',
];
$footerScripts = $footerScripts ?? ['js/main.js?v=20260720d'];
$whatsAppHref = $whatsAppHref ?? 'https://wa.me/27762538318?text=Hello%20VGi%20Cars%2C%20I%20would%20like%20to%20chat%20about%20a%20vehicle.';
$footerLogoPath = 'images/vgilogo.png';
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
          <li><i class="fa-solid fa-phone"></i> +27 76 253 8318</li>
          <li><i class="fa-solid fa-envelope"></i> info@vgicars.co.za</li>
          <li><i class="fa-solid fa-location-dot"></i> Johannesburg, South Africa</li>
        </ul>
      </div>
    </div>
    <p class="copyright">&copy; <span id="yearNow"></span> VGi Cars. All Rights Reserved.</p>
    <p class="copyright developer-credit">Developed by <a href="https://ib-innovativesolutions.com/it-solutions" target="_blank" rel="noopener noreferrer">IB Innovative Solutions</a></p>
  </footer>

  <a class="whatsapp-float" href="<?= $escape($whatsAppHref) ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat to us on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
    <span>Chat to us</span>
  </a>

  <?php foreach ($footerScripts as $script): ?>
    <script src="<?= $escape((string) $script) ?>" defer></script>
  <?php endforeach; ?>
</body>
</html>