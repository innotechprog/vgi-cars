const vehicles = [
  {
    id: 1,
    name: "1967 Ford Mustang GT",
    make: "Ford",
    model: "Mustang GT",
    year: 1967,
    mileage: 45000,
    transmission: "Manual",
    fuel: "Petrol",
    engine: "V8 4.7L",
    location: "Johannesburg",
    price: 895000,
    image: "https://images.unsplash.com/photo-1494905998402-395d579af36f?auto=format&fit=crop&w=1400&q=80",
    gallery: [
      "https://images.unsplash.com/photo-1494905998402-395d579af36f?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1484136063621-1f5880e3ff13?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1400&q=80"
    ],
    description: "A legendary fastback restored with period-correct detailing, upgraded reliability components and authentic V8 character.",
    features: ["Matching numbers block", "Power steering", "Disc brake conversion", "Premium leather interior", "Bluetooth retro head unit"],
    specs: {
      Make: "Ford",
      Model: "Mustang GT",
      Year: "1967",
      Mileage: "45,000 km",
      VIN: "8R02S105421",
      Fuel: "Petrol",
      Transmission: "Manual",
      Drive: "RWD",
      Seats: "4",
      Doors: "2",
      Exterior: "Jet Black",
      Interior: "Tan Leather",
      Service: "Full"
    }
  },
  {
    id: 2,
    name: "1965 Jaguar E-Type",
    make: "Jaguar",
    model: "E-Type",
    year: 1965,
    mileage: 38000,
    transmission: "Manual",
    fuel: "Petrol",
    engine: "I6 4.2L",
    location: "Cape Town",
    price: 1250000,
    image: "https://images.unsplash.com/photo-1549923746-c502d488b3ea?auto=format&fit=crop&w=1400&q=80",
    gallery: [
      "https://images.unsplash.com/photo-1549923746-c502d488b3ea?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1532581140115-3e355d1ed1de?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1400&q=80"
    ],
    description: "One of the most beautiful cars ever built, this E-Type combines sculpted bodywork with smooth inline-six touring performance.",
    features: ["Factory wire wheels", "Independent rear suspension", "Polished wood wheel", "Stainless exhaust", "Collector-grade restoration"],
    specs: {
      Make: "Jaguar",
      Model: "E-Type",
      Year: "1965",
      Mileage: "38,000 km",
      VIN: "1E10372",
      Fuel: "Petrol",
      Transmission: "Manual",
      Drive: "RWD",
      Seats: "2",
      Doors: "2",
      Exterior: "Silver Mist",
      Interior: "Black Hide",
      Service: "Full"
    }
  },
  {
    id: 3,
    name: "1973 Porsche 911T",
    make: "Porsche",
    model: "911T",
    year: 1973,
    mileage: 60000,
    transmission: "Manual",
    fuel: "Petrol",
    engine: "Flat-6 2.4L",
    location: "Durban",
    price: 1100000,
    image: "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=1400&q=80",
    gallery: [
      "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1556800572-1b8aeef2c54f?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1542362567-b07e54358753?auto=format&fit=crop&w=1400&q=80"
    ],
    description: "A pure air-cooled icon known for lightweight balance, tactile steering and unmistakable flat-six soundtrack.",
    features: ["Fuchs wheels", "Sport exhaust", "Restored interior", "H4 headlights", "Classic gauges"],
    specs: {
      Make: "Porsche",
      Model: "911T",
      Year: "1973",
      Mileage: "60,000 km",
      VIN: "9113101473",
      Fuel: "Petrol",
      Transmission: "Manual",
      Drive: "RWD",
      Seats: "4",
      Doors: "2",
      Exterior: "Graphite Black",
      Interior: "Cognac",
      Service: "Partial"
    }
  },
  {
    id: 4,
    name: "1957 Chevrolet Bel Air",
    make: "Chevrolet",
    model: "Bel Air",
    year: 1957,
    mileage: 70000,
    transmission: "Automatic",
    fuel: "Petrol",
    engine: "V8 4.6L",
    location: "Pretoria",
    price: 950000,
    image: "https://images.unsplash.com/photo-1597007066704-67bf2068d5b2?auto=format&fit=crop&w=1400&q=80",
    gallery: [
      "https://images.unsplash.com/photo-1597007066704-67bf2068d5b2?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1567808291548-fc3ee04dbcf0?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1611566026373-c6c8da0ea861?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1487754180451-c456f719a1fc?auto=format&fit=crop&w=1400&q=80"
    ],
    description: "A chrome-rich American classic with signature fins, smooth V8 power and polished show presence.",
    features: ["Factory trim restored", "Whitewall tires", "Automatic transmission", "Dual exhaust", "Power brakes"],
    specs: {
      Make: "Chevrolet",
      Model: "Bel Air",
      Year: "1957",
      Mileage: "70,000 km",
      VIN: "VC57A234981",
      Fuel: "Petrol",
      Transmission: "Automatic",
      Drive: "RWD",
      Seats: "5",
      Doors: "2",
      Exterior: "Onyx Black",
      Interior: "Cream",
      Service: "Full"
    }
  },
  {
    id: 5,
    name: "1969 Mercedes-Benz 280SL",
    make: "Mercedes-Benz",
    model: "280SL",
    year: 1969,
    mileage: 42000,
    transmission: "Automatic",
    fuel: "Petrol",
    engine: "I6 2.8L",
    location: "Johannesburg",
    price: 1350000,
    image: "https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1400&q=80",
    gallery: [
      "https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1617788138017-80ad40651399?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1400&q=80"
    ],
    description: "Elegant and sophisticated, this 280SL balances grand touring comfort with unmistakable Mercedes design pedigree.",
    features: ["Hardtop included", "Power steering", "Original Becker radio", "Refinished wood trim", "Service records"],
    specs: {
      Make: "Mercedes-Benz",
      Model: "280SL",
      Year: "1969",
      Mileage: "42,000 km",
      VIN: "11304412022752",
      Fuel: "Petrol",
      Transmission: "Automatic",
      Drive: "RWD",
      Seats: "2",
      Doors: "2",
      Exterior: "Charcoal",
      Interior: "Tan",
      Service: "Full"
    }
  },
  {
    id: 6,
    name: "1970 Alfa Romeo GTV",
    make: "Alfa Romeo",
    model: "GTV",
    year: 1970,
    mileage: 52000,
    transmission: "Manual",
    fuel: "Petrol",
    engine: "I4 2.0L",
    location: "Cape Town",
    price: 780000,
    image: "https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=1400&q=80",
    gallery: [
      "https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1566024164372-0281f1133a5f?auto=format&fit=crop&w=1400&q=80",
      "https://images.unsplash.com/photo-1532581140115-3e355d1ed1de?auto=format&fit=crop&w=1400&q=80"
    ],
    description: "Italian coupe charisma meets agile handling in a classic GTV restored for spirited weekend drives.",
    features: ["Twin-cam engine", "5-speed manual", "Updated suspension bushings", "Period-correct alloys", "Detailed engine bay"],
    specs: {
      Make: "Alfa Romeo",
      Model: "GTV",
      Year: "1970",
      Mileage: "52,000 km",
      VIN: "AR2418675",
      Fuel: "Petrol",
      Transmission: "Manual",
      Drive: "RWD",
      Seats: "4",
      Doors: "2",
      Exterior: "Rosso Corsa",
      Interior: "Black",
      Service: "Partial"
    }
  }
];

window.VGI_DATA = { vehicles };

const currency = new Intl.NumberFormat("en-ZA");

function formatPrice(value) {
  return `R ${currency.format(value)}`;
}

function formatMileage(value) {
  return `${currency.format(value)} km`;
}

function vehicleCardTemplate(vehicle) {
  return `
    <article class="vehicle-card reveal">
      <div class="vehicle-image">
        <img src="${vehicle.image}" alt="${vehicle.name}" loading="lazy" />
        <button class="fav-btn" aria-label="Save ${vehicle.name}"><i class="fa-regular fa-heart"></i></button>
      </div>
      <div class="vehicle-content">
        <h3>${vehicle.name}</h3>
        <p class="meta">${vehicle.engine} • ${vehicle.transmission} • ${formatMileage(vehicle.mileage)}</p>
        <div class="price-row">
          <span class="price-tag">${formatPrice(vehicle.price)}</span>
          <a class="btn btn-outline" href="vehicle.html?id=${vehicle.id}">View Details</a>
        </div>
      </div>
    </article>
  `;
}

function setYear() {
  const yearNode = document.querySelectorAll("#yearNow");
  yearNode.forEach((node) => {
    node.textContent = new Date().getFullYear();
  });
}

function setHeaderBehavior() {
  const header = document.getElementById("siteHeader");
  const toggle = document.getElementById("menuToggle");
  const links = document.getElementById("navLinks");

  if (!header) {
    return;
  }

  window.addEventListener("scroll", () => {
    header.classList.toggle("scrolled", window.scrollY > 16);
  });

  if (toggle && links) {
    toggle.addEventListener("click", () => {
      links.classList.toggle("open");
    });
  }
}

function setRevealObserver() {
  const reveals = document.querySelectorAll(".reveal:not(.hero-copy)");
  if (!reveals.length) {
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("in-view");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.16 }
  );

  reveals.forEach((item) => observer.observe(item));
}

function initHeroScrollReveal() {
  const heroCopy = document.querySelector(".hero .hero-copy.reveal");
  if (!heroCopy) {
    return;
  }

  heroCopy.classList.remove("in-view");

  function onFirstScroll() {
    if (window.scrollY > 8) {
      heroCopy.classList.add("in-view");
      window.removeEventListener("scroll", onFirstScroll);
    }
  }

  window.addEventListener("scroll", onFirstScroll, { passive: true });
}

function populateFilters(list) {
  const makeSelect = document.getElementById("filterMake");
  const modelSelect = document.getElementById("filterModel");
  const yearMin = document.getElementById("filterYearMin");
  const yearMax = document.getElementById("filterYearMax");

  if (!makeSelect || !modelSelect || !yearMin || !yearMax) {
    return;
  }

  const makes = [...new Set(list.map((item) => item.make))].sort();
  const models = [...new Set(list.map((item) => item.model))].sort();
  const years = [...new Set(list.map((item) => item.year))].sort((a, b) => a - b);

  makes.forEach((make) => {
    makeSelect.insertAdjacentHTML("beforeend", `<option value="${make}">${make}</option>`);
  });

  models.forEach((model) => {
    modelSelect.insertAdjacentHTML("beforeend", `<option value="${model}">${model}</option>`);
  });

  years.forEach((year) => {
    const option = `<option value="${year}">${year}</option>`;
    yearMin.insertAdjacentHTML("beforeend", option);
    yearMax.insertAdjacentHTML("beforeend", option);
  });
}

function renderFeatured(list) {
  const grid = document.getElementById("featuredGrid");
  if (!grid) {
    return;
  }

  if (!list.length) {
    grid.innerHTML = "<p>No vehicles match your filters.</p>";
    return;
  }

  grid.innerHTML = list.map(vehicleCardTemplate).join("");
  setRevealObserver();
}

function initHomePage() {
  const form = document.getElementById("inventorySearchForm");
  if (!form) {
    return;
  }

  const source = vehicles.slice();
  populateFilters(source);
  renderFeatured(source.slice(0, 4));

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const make = document.getElementById("filterMake").value;
    const model = document.getElementById("filterModel").value;
    const yearFrom = Number(document.getElementById("filterYearMin").value || 0);
    const yearTo = Number(document.getElementById("filterYearMax").value || 9999);
    const price = Number(document.getElementById("filterPrice").value || Number.MAX_SAFE_INTEGER);

    const filtered = source.filter((item) => {
      if (make && item.make !== make) return false;
      if (model && item.model !== model) return false;
      if (item.year < yearFrom || item.year > yearTo) return false;
      if (item.price > price) return false;
      return true;
    });

    renderFeatured(filtered.slice(0, 4));
  });
}

function setTabs() {
  const tabs = document.querySelectorAll(".tab");
  if (!tabs.length) {
    return;
  }

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      tabs.forEach((item) => item.classList.remove("active"));
      document.querySelectorAll(".tab-panel").forEach((panel) => panel.classList.remove("active"));
      tab.classList.add("active");
      const panel = document.getElementById(`tab-${tab.dataset.tab}`);
      if (panel) {
        panel.classList.add("active");
      }
    });
  });
}

function initVehiclePage() {
  const title = document.getElementById("vehicleTitle");
  if (!title) {
    return;
  }

  const params = new URLSearchParams(window.location.search);
  const id = Number(params.get("id"));
  const selectedVehicle = vehicles.find((item) => item.id === id) || vehicles[0];
  window.VGI_DATA.selectedVehicle = selectedVehicle;

  document.getElementById("vehicleTitle").textContent = selectedVehicle.name;
  document.getElementById("crumbVehicle").textContent = selectedVehicle.name;
  document.getElementById("vehiclePrice").textContent = formatPrice(selectedVehicle.price);
  document.getElementById("vehicleDescription").textContent = selectedVehicle.description;
  document.getElementById("financePrice").value = selectedVehicle.price;

  const chips = [
    `${selectedVehicle.year}`,
    selectedVehicle.transmission,
    selectedVehicle.fuel,
    selectedVehicle.engine,
    selectedVehicle.location
  ];

  document.getElementById("specChips").innerHTML = chips.map((item) => `<li>${item}</li>`).join("");

  const specsHtml = Object.entries(selectedVehicle.specs)
    .map(([key, value]) => `<div class="spec-row"><span>${key}</span><strong>${value}</strong></div>`)
    .join("");

  document.getElementById("specTable").innerHTML = specsHtml;
  document.getElementById("featuresList").innerHTML = selectedVehicle.features.map((item) => `<li>${item}</li>`).join("");

  const related = vehicles.filter((vehicle) => vehicle.id !== selectedVehicle.id).slice(0, 4);
  const relatedNode = document.getElementById("relatedVehicles");
  relatedNode.innerHTML = related.map(vehicleCardTemplate).join("");

  document.dispatchEvent(new CustomEvent("vehicleDataReady", { detail: selectedVehicle }));
}

setYear();
setHeaderBehavior();
initHeroScrollReveal();
setRevealObserver();
initHomePage();
initVehiclePage();
setTabs();
