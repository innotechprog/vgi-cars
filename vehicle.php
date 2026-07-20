<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VGi Cars | Vehicle Details</title>
  <meta name="description" content="Vehicle details and specifications at VGi Cars." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css?v=20260720b" />
  <link rel="stylesheet" href="css/responsive.css?v=20260720b" />
</head>
<body>
  <header class="site-header" id="siteHeader">
    <nav class="container nav-wrap">
      <a class="logo" href="index" aria-label="VGi Cars home">
        <span class="logo-main">VGi</span>
        <span class="logo-sub">CARS</span>
      </a>
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
      </button>
      <ul class="nav-links" id="navLinks">
        <li><a href="index">Home</a></li>
        <li><a href="index#inventory">Inventory</a></li>
        <li><a href="index#about">About Us</a></li>
        <li><a href="index#services">Services</a></li>
        <li><a href="index#contact">Contact</a></li>
      </ul>
      <a class="btn btn-outline nav-cta" href="index#inventory">Buy Car</a>
    </nav>
  </header>

  <main class="details-page">
    <section class="container breadcrumb reveal">
      <a href="index">Home</a>
      <span>/</span>
      <a href="index#inventory">Inventory</a>
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

  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <a class="logo" href="index">
          <span class="logo-main">VGi</span>
          <span class="logo-sub">CARS</span>
        </a>
        <p>Classic motoring heritage, curated with precision and trust.</p>
      </div>
      <div>
        <h4>Contact</h4>
        <ul>
          <li><i class="fa-solid fa-phone"></i> +27 76 253 8318</li>
          <li><i class="fa-solid fa-envelope"></i> info@vgicars.co.za</li>
          <li><i class="fa-solid fa-location-dot"></i> Johannesburg, South Africa</li>
        </ul>
      </div>
    </div>
    <p class="copyright">&copy; <span id="yearNow"></span> VGi Cars. All Rights Reserved.</p>
  </footer>

  <a class="whatsapp-float" href="https://wa.me/27762538318?text=Hello%20VGi%20Cars%2C%20I%20would%20like%20to%20chat%20about%20a%20vehicle." target="_blank" rel="noopener noreferrer" aria-label="Chat to us on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
    <span>Chat to us</span>
  </a>

  <script src="js/main.js?v=20260720d" defer></script>
  <script src="js/gallery.js" defer></script>
  <script src="js/finance.js" defer></script>
</body>
</html>
