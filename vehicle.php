<?php
require_once __DIR__ . '/includes/bootstrap.php';

$profilePhone = '+27 78 979 6523';
if (isset($db) && $db instanceof PDO) {
  try {
    $stmt = $db->query("SELECT phone_number FROM users WHERE role = 'admin' ORDER BY user_id ASC LIMIT 1");
    $row = $stmt ? $stmt->fetch() : false;
    if (is_array($row)) {
      $candidatePhone = trim((string) ($row['phone_number'] ?? ''));
      if ($candidatePhone !== '') {
        $profilePhone = $candidatePhone;
      }
    }
  } catch (Throwable $e) {
    // Keep default phone when profile lookup fails.
  }
}

$sitePhone = trim((string) $settingsService->get('site_phone', ''));
if ($sitePhone === '') {
  $sitePhone = trim((string) $settingsService->get('company_phone', $profilePhone));
}

$pageTitle = 'VGi Cars | Vehicle Details';
$pageDescription = 'Vehicle details and specifications at VGi Cars.';
$contactHref = 'contact';
$buyCarHref = 'index#inventory';
require __DIR__ . '/header.php';
?>

  <main class="details-page">
    <section class="container breadcrumb reveal">
      <a href="index">Home</a>
      <span>/</span>
      <strong id="crumbVehicle">Vehicle Details</strong>
    </section>

    <section class="container details-layout">
      <div class="gallery-panel reveal">
        <div class="main-image-wrap" id="mainImageWrap">
          <img id="mainVehicleImage" src="" alt="Vehicle image" loading="eager" />
          <button class="gallery-nav gallery-nav-prev" id="galleryPrev" type="button" aria-label="Previous image">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button class="gallery-nav gallery-nav-next" id="galleryNext" type="button" aria-label="Next image">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
          <div class="gallery-counter" id="galleryCounter">
            <span id="galleryCurrent">1</span> / <span id="galleryTotal">1</span>
          </div>
        </div>
        <div class="thumb-row" id="thumbRow" aria-label="Vehicle gallery thumbnails"></div>
      </div>

      <aside class="info-panel reveal">
        <h1 id="vehicleTitle">Vehicle Name</h1>
        <p class="price" id="vehiclePrice">R 0</p>
        <div class="actions-row">
          <a class="btn btn-gold" href="contact"><i class="fa-solid fa-envelope"></i> Enquire</a>
        </div>
        <ul class="spec-chips" id="specChips"></ul>

        <div class="dealer-card">
          <h3>Dealer Information</h3>
          <p>VGi Cars Premium Showroom</p>
          <p><i class="fa-solid fa-phone"></i> <?= h($sitePhone) ?></p>
          <p><i class="fa-solid fa-location-dot"></i> 25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001</p>
        </div>
      </aside>
    </section>

    <section class="container section reveal">
      <div class="tabs" id="detailTabs">
        <button class="tab active" data-tab="overview">Overview</button>
        <button class="tab" data-tab="specs">Specifications</button>
        <button class="tab" data-tab="features">Features</button>
        <button class="tab" data-tab="finance">Finance</button>
      </div>

      <article class="tab-panel active" id="tab-overview">
        <h2>Vehicle Overview</h2>
        <p id="vehicleDescription"></p>
      </article>

      <article class="tab-panel" id="tab-specs">
        <h2>Specifications</h2>
        <div class="spec-table" id="specTable"></div>
      </article>

      <article class="tab-panel" id="tab-features">
        <h2>Features</h2>
        <ul class="features-list" id="featuresList"></ul>
      </article>

      <article class="tab-panel" id="tab-finance">
        <h2>Finance Calculator</h2>
        <form id="financeForm" class="finance-grid">
          <label>
            <span>Vehicle Price (R)</span>
            <input id="financePrice" type="number" min="0" required />
          </label>
          <label>
            <span>Deposit (R)</span>
            <input id="financeDeposit" type="number" min="0" value="100000" required />
          </label>
          <label>
            <span>Interest Rate (%)</span>
            <input id="financeInterest" type="number" min="0" step="0.1" value="11.5" required />
          </label>
          <label>
            <span>Months</span>
            <input id="financeMonths" type="number" min="1" value="60" required />
          </label>
          <button class="btn btn-gold" type="submit">Calculate</button>
        </form>
        <p class="finance-result" id="financeResult">Estimated monthly installment: R 0</p>
      </article>
    </section>

    <section class="container section reveal">
      <div class="section-head">
        <h2>Related Vehicles</h2>
      </div>
      <div class="vehicle-grid" id="relatedVehicles"></div>
    </section>
  </main>

<?php
$footerIntro = 'Classic motoring heritage, curated with precision and trust.';
$footerScripts = ['js/main.js?v=20260723b', 'js/gallery.js?v=20260723b', 'js/finance.js?v=20260723b'];
require __DIR__ . '/footer.php';
?>
