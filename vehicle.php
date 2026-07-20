<?php
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
        </div>
        <div class="thumb-row" id="thumbRow"></div>
      </div>

      <aside class="info-panel reveal">
        <h1 id="vehicleTitle">Vehicle Name</h1>
        <p class="price" id="vehiclePrice">R 0</p>
        <div class="actions-row">
          <button class="btn btn-gold"><i class="fa-solid fa-envelope"></i> Enquire</button>
          <button class="btn btn-outline"><i class="fa-solid fa-calendar-check"></i> Book Viewing</button>
        </div>
        <ul class="spec-chips" id="specChips"></ul>

        <div class="dealer-card">
          <h3>Dealer Information</h3>
          <p>VGi Cars Premium Showroom</p>
          <p><i class="fa-solid fa-phone"></i> +27 76 253 8318</p>
          <p><i class="fa-solid fa-location-dot"></i> Johannesburg, South Africa</p>
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
$footerScripts = ['js/main.js?v=20260720d', 'js/gallery.js', 'js/finance.js'];
require __DIR__ . '/footer.php';
?>
