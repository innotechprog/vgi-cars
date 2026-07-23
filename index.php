<?php
$pageTitle = 'VGi Cars | Quality Cars For Sale';
$pageDescription = 'Browse quality vehicles at VGi Cars, from modern and premium models to collector and classic cars.';
$pageKeywords = 'cars for sale, buy cars, sell cars, used cars, premium cars, modern cars, classic cars, Johannesburg dealership';
$activePage = 'home';
$contactHref = 'contact';
$buyCarHref = '#inventory';
require __DIR__ . '/header.php';
?>

  <main>
    <section class="hero">
      <div class="hero-overlay"></div>
      <div class="container hero-grid">
        <div class="hero-copy reveal">
          <h1><span>LEGENDARY DRIVES.</span></h1>
          <div class="hero-actions">
            <a class="btn btn-gold" href="#inventory">Buy Cars</a>
          </div>
        </div>
      </div>
    </section>

    <section class="search-panel container reveal" id="inventory">
      <button class="search-toggle-btn" id="searchPanelToggle" type="button" aria-expanded="false" aria-controls="inventorySearchForm">
        <i class="fa-solid fa-bars"></i>
        <span>Search & Filters</span>
      </button>

      <form id="inventorySearchForm" class="search-grid" autocomplete="off" hidden>
        <div class="search-title">
          <i class="fa-solid fa-magnifying-glass"></i>
          <span>Search Cars</span>
        </div>

        <label>
          <span>Make</span>
          <select id="filterMake">
            <option value="">All Makes</option>
          </select>
        </label>

        <label>
          <span>Model</span>
          <select id="filterModel">
            <option value="">All Models</option>
          </select>
        </label>

        <label>
          <span>Year From</span>
          <select id="filterYearMin">
            <option value="">Min Year</option>
          </select>
        </label>

        <label>
          <span>Year To</span>
          <select id="filterYearMax">
            <option value="">Max Year</option>
          </select>
        </label>

        <label>
          <span>Price Up To</span>
          <select id="filterPrice">
            <option value="">No Limit</option>
            <option value="50000">R 50,000</option>
            <option value="100000">R 100,000</option>
            <option value="250000">R 250,000</option>
            <option value="500000">R 500,000</option>
            <option value="800000">R 800,000</option>
            <option value="1000000">R 1,000,000</option>
            <option value="1200000">R 1,200,000</option>
            <option value="1500000">R 1,500,000</option>
          </select>
        </label>

        <label>
          <span>Sort Price</span>
          <select id="filterSort">
            <option value="recent">Recently Added</option>
            <option value="price_asc">Lowest to Highest</option>
            <option value="price_desc">Highest to Lowest</option>
          </select>
        </label>

        <button class="btn btn-gold" type="submit">Search</button>
      </form>
    </section>

    <section class="section container">
      <div class="section-head reveal">
        <h2>Featured Cars</h2>
      </div>

      <div class="vehicle-grid" id="featuredGrid"></div>
    </section>

    <section class="section perks" id="services">
      <div class="container perks-grid">
        <article class="perk reveal">
          <i class="fa-solid fa-shield-halved"></i>
          <h3>Quality Assured</h3>
          <p>Every car is thoroughly inspected and quality guaranteed.</p>
        </article>
        <article class="perk reveal">
          <i class="fa-solid fa-screwdriver-wrench"></i>
          <h3>Expert Restoration</h3>
          <p>Our specialists preserve originality with modern reliability.</p>
        </article>
        <article class="perk reveal">
          <i class="fa-solid fa-steering-wheel"></i>
          <h3>Drive With Pride</h3>
          <p>Experience iconic machines crafted for true enthusiasts.</p>
        </article>
        <article class="perk reveal">
          <i class="fa-solid fa-handshake-angle"></i>
          <h3>Trusted Dealer</h3>
          <p>Years of expertise and passion for collectible automobiles.</p>
        </article>
      </div>
    </section>

  </main>

<?php
$footerIntro = 'At VGi Cars, we specialize in buying and selling quality vehicles across modern, premium, performance, and classic segments.';
$footerWhyItems = [
    'Curated selections',
    'Trusted guidance',
    'Collector mindset',
];
$footerScripts = ['js/main.js?v=20260723b', 'js/slider.js?v=20260723b'];
require __DIR__ . '/footer.php';
?>
