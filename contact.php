<?php
require_once __DIR__ . '/includes/bootstrap.php';

$profilePhone = '+27 78 979 6523';
$profileEmail = 'info@vgicars.co.za';
if (isset($db) && $db instanceof PDO) {
  try {
    $stmt = $db->query("SELECT phone_number, email FROM users WHERE role = 'admin' ORDER BY user_id ASC LIMIT 1");
    $row = $stmt ? $stmt->fetch() : false;
    if (is_array($row)) {
      $candidatePhone = trim((string) ($row['phone_number'] ?? ''));
      $candidateEmail = trim((string) ($row['email'] ?? ''));
      if ($candidatePhone !== '') {
        $profilePhone = $candidatePhone;
      }
      if ($candidateEmail !== '') {
        $profileEmail = $candidateEmail;
      }
    }
  } catch (Throwable $e) {
    // Keep defaults when profile lookup fails.
  }
}

$sitePhone = trim((string) $settingsService->get('site_phone', '+27789796523'));
if ($sitePhone === '') {
  $sitePhone = trim((string) $settingsService->get('company_phone', $profilePhone));
}

$siteEmail = trim((string) $settingsService->get('site_contact_email', 'info@vgicars.co.za'));
if ($siteEmail === '') {
  $siteEmail = trim((string) $settingsService->get('smtp_from_email', $profileEmail));
}
$digitsPhone = preg_replace('/[^0-9]/', '', $sitePhone);
if ($digitsPhone === '') {
  $digitsPhone = '27789796523';
}
$whatsAppContactHref = 'https://wa.me/' . $digitsPhone . '?text=Hello%20VGi%20Cars%2C%20I%20would%20like%20to%20enquire%20about%20a%20vehicle.';

$pageTitle = 'VGi Cars | Contact Us';
$pageDescription = 'Get in touch with VGi Cars for vehicle enquiries, sourcing requests, viewings, and general assistance.';
$activePage = 'contact';
$contactHref = 'contact';
$buyCarHref = 'index#inventory';
require __DIR__ . '/header.php';
?>

  <main class="contact-page">
    <section class="contact-hero">
      <div class="contact-hero-overlay"></div>
      <div class="container contact-hero-inner reveal">
        <span class="page-kicker">Contact VGi Cars</span>
        <h1>Reach out for vehicle enquiries, sourcing requests, or to arrange a viewing.</h1>
        <p class="contact-lead">
          Whether you are buying, selling, or looking for a specific vehicle, we keep the conversation direct, informed, and personal.
        </p>
      </div>
    </section>

    <section class="section container contact-grid-wrap">
      <div class="contact-grid">
        <article class="contact-card reveal">
          <span class="page-kicker">Call Or Message</span>
          <h2>Speak to the showroom.</h2>
          <p>Use the channel that suits you best and we will help you with stock availability, bookings, or sourcing.</p>

          <div class="contact-method-list">
            <a class="contact-method" href="tel:<?= h($digitsPhone) ?>">
              <i class="fa-solid fa-phone"></i>
              <div>
                <strong>Phone</strong>
                <span><?= h($sitePhone) ?></span>
              </div>
            </a>

            <a class="contact-method" href="mailto:<?= h($siteEmail) ?>?subject=VGi%20Cars%20Enquiry">
              <i class="fa-solid fa-envelope"></i>
              <div>
                <strong>Email</strong>
                <span><?= h($siteEmail) ?></span>
              </div>
            </a>

            <a class="contact-method" href="<?= h($whatsAppContactHref) ?>" target="_blank" rel="noopener noreferrer">
              <i class="fa-brands fa-whatsapp"></i>
              <div>
                <strong>WhatsApp</strong>
                <span>Chat with the team directly</span>
              </div>
            </a>
          </div>

          <div class="hero-actions contact-actions">
            <a class="btn btn-gold" href="<?= h($whatsAppContactHref) ?>" target="_blank" rel="noopener noreferrer">Start WhatsApp Chat</a>
            <a class="btn btn-outline" href="index#inventory">Browse Cars</a>
          </div>
        </article>

        <aside class="contact-card contact-side-card reveal">
          <span class="page-kicker">Visit Us</span>
          <h3>25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001</h3>
          <p>Get in touch to arrange a private viewing, discuss a part exchange, or ask about a vehicle before visiting.</p>

          <div class="contact-detail-stack">
            <div class="contact-detail-item">
              <i class="fa-solid fa-location-dot"></i>
              <div>
                <strong>Location</strong>
                <span>25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001</span>
              </div>
            </div>

            <div class="contact-detail-item">
              <i class="fa-solid fa-clock"></i>
              <div>
                <strong>Availability</strong>
                <span>By appointment and direct enquiry</span>
              </div>
            </div>

            <div class="contact-detail-item">
              <i class="fa-solid fa-car-side"></i>
              <div>
                <strong>Services</strong>
                <span>Vehicle sales, sourcing guidance, and viewing arrangements</span>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </section>

    <section class="section container">
      <div class="contact-panel reveal">
        <div class="section-head">
          <h2>Send A Quick Enquiry</h2>
        </div>

        <form class="contact-form-grid" action="mailto:<?= h($siteEmail) ?>" method="post" enctype="text/plain">
          <label>
            <span>Full Name</span>
            <input type="text" name="name" placeholder="Your full name" required />
          </label>

          <label>
            <span>Email Address</span>
            <input type="email" name="email" placeholder="you@example.com" required />
          </label>

          <label>
            <span>Phone Number</span>
            <input type="text" name="phone" placeholder="Your contact number" />
          </label>

          <label>
            <span>Subject</span>
            <input type="text" name="subject" placeholder="What are you enquiring about?" required />
          </label>

          <label class="contact-form-full">
            <span>Message</span>
            <textarea name="message" rows="6" placeholder="Tell us about the vehicle or service you need." required></textarea>
          </label>

          <div class="contact-form-full contact-form-note">
            <p>This opens your email app with the enquiry prefilled. For the fastest response, you can also call or message us directly.</p>
          </div>

          <div class="contact-form-full">
            <button class="btn btn-gold" type="submit">Send Enquiry</button>
          </div>
        </form>
      </div>
    </section>
  </main>

<?php
$footerId = 'contact-footer';
$footerIntro = 'Contact VGi Cars for vehicle enquiries, sourcing requests, and viewing arrangements handled with clarity and attention to detail.';
$footerScripts = ['js/main.js?v=20260723c'];
require __DIR__ . '/footer.php';
?>