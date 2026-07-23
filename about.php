<?php
$pageTitle = 'VGi Cars | About Us';
$pageDescription = 'Learn more about VGi Cars, our passion for collectible motoring, and the standard behind every vehicle we present.';
$activePage = 'about';
$contactHref = 'contact';
$buyCarHref = 'index#inventory';
require __DIR__ . '/header.php';
?>

  <main class="about-page">
    <section class="about-hero">
      <div class="about-hero-overlay"></div>
      <div class="container about-hero-inner reveal">
        <span class="page-kicker">About VGi Cars</span>
        <h1>Curating memorable cars for collectors, enthusiasts, and buyers who want more than transport.</h1>
        <p class="about-lead">
          VGi Cars is built around careful selection, honest presentation, and a deep respect for vehicles with character.
          We focus on cars that feel special to own, drive, and preserve.
        </p>
      </div>
    </section>

    <section class="section container about-story-grid">
      <article class="about-panel reveal">
        <span class="page-kicker">Our Story</span>
        <h2>Classic appeal, modern confidence.</h2>
        <p>
          We created VGi Cars to offer a showroom experience that values heritage as much as condition. Every car in our care
          is reviewed for presentation, provenance, and the kind of ownership experience it can deliver.
        </p>
        <p>
          Whether the vehicle is a timeless cruiser, a rare collector's piece, or a refined modern classic, our goal is the same:
          help clients buy with clarity and sell with confidence.
        </p>
      </article>
      <aside class="about-panel about-side-card reveal">
        <h3>What Defines VGi</h3>
        <ul class="about-checklist">
          <li><i class="fa-solid fa-check"></i> Curated stock with a focus on quality and character</li>
          <li><i class="fa-solid fa-check"></i> Clear vehicle information and straightforward guidance</li>
          <li><i class="fa-solid fa-check"></i> Support for buyers, collectors, and private sellers</li>
          <li><i class="fa-solid fa-check"></i> A showroom style built around trust, not pressure</li>
        </ul>
      </aside>
    </section>

    <section class="section container">
      <div class="about-stats">
        <article class="about-stat reveal">
          <strong>Premium</strong>
          <span>presentation standard on every listed vehicle</span>
        </article>
        <article class="about-stat reveal">
          <strong>Buyer-first</strong>
          <span>guidance from enquiry through delivery</span>
        </article>
        <article class="about-stat reveal">
          <strong>Detail-led</strong>
          <span>attention to specification, history, and condition</span>
        </article>
        <article class="about-stat reveal">
          <strong>Johannesburg</strong>
          <span>based, serving clients across South Africa</span>
        </article>
      </div>
    </section>

    <section class="section container">
      <div class="section-head reveal">
        <h2>How We Work</h2>
      </div>
      <div class="about-values-grid">
        <article class="about-panel reveal">
          <i class="fa-solid fa-gem"></i>
          <h3>Selective Sourcing</h3>
          <p>We prioritize vehicles with strong presence, proper care, and the right story behind them.</p>
        </article>
        <article class="about-panel reveal">
          <i class="fa-solid fa-file-circle-check"></i>
          <h3>Transparent Presentation</h3>
          <p>Listings are designed to be clear and useful so buyers can assess a vehicle before making contact.</p>
        </article>
        <article class="about-panel reveal">
          <i class="fa-solid fa-people-arrows"></i>
          <h3>Personal Service</h3>
          <p>We keep the process direct, responsive, and tailored to the type of car and client involved.</p>
        </article>
      </div>
    </section>

    <section class="section container">
      <div class="about-cta reveal">
        <span class="page-kicker">Start The Conversation</span>
        <h2>Looking for a vehicle with presence?</h2>
        <p>Browse the current collection or speak to us directly about sourcing, selling, or arranging a viewing.</p>
        <div class="hero-actions about-actions">
          <a class="btn btn-gold" href="index#inventory">Browse Cars</a>
          <a class="btn btn-outline" href="#contact">Contact Us</a>
        </div>
      </div>
    </section>
  </main>

<?php
$footerIntro = 'At VGi Cars, we specialize in distinctive vehicles presented with care, clarity, and a deep appreciation for automotive heritage.';
$footerWhyItems = [
    'Curated vehicle selection',
    'Trusted buying support',
    'Enthusiast-led service',
];
$footerScripts = ['js/main.js?v=20260723b'];
require __DIR__ . '/footer.php';
?>