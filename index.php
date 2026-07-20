<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VGi Cars | Vintage Collection</title>
  <meta name="description" content="VGi Cars premium vintage car showroom and dealership." />
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
        <li><a class="active" href="index">Home</a></li>
        <li><a href="#inventory">Inventory</a></li>
        <li><a href="#services">About Us</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>

      <a class="btn btn-outline nav-cta" href="#inventory">Buy Car</a>
    </nav>
  </header>

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
        <a href="#inventory">View All Cars <i class="fa-solid fa-arrow-right"></i></a>
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

  <footer class="site-footer" id="contact">
    <div class="container footer-grid">
      <div>
        <a class="logo" href="index">
          <span class="logo-main">VGi</span>
          <span class="logo-sub">CARS</span>
        </a>
        <p>
          At VGi Cars, we specialize in buying, selling and restoring vintage cars.
          We deliver automotive history with confidence.
        </p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index">Home</a></li>
          <li><a href="#inventory">Inventory</a></li>
          <li><a href="#services">About Us</a></li>
          <li><a href="#services">Services</a></li>
        </ul>
      </div>
      <div>
        <h4>Inventory</h4>
        <ul>
          <li><a href="#inventory">All Cars</a></li>
          <li><a href="#inventory">Vintage Cars</a></li>
          <li><a href="#inventory">New Arrivals</a></li>
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
  </footer>

  <a class="whatsapp-float" href="https://wa.me/27762538318?text=Hello%20VGi%20Cars%2C%20I%20would%20like%20to%20chat%20about%20a%20vehicle." target="_blank" rel="noopener noreferrer" aria-label="Chat to us on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
    <span>Chat to us</span>
  </a>

  <script src="js/main.js?v=20260720d" defer></script>
  <script src="js/slider.js" defer></script>
</body>
</html>
