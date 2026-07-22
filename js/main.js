const currency = new Intl.NumberFormat("en-ZA");
let vehicles = [];
let homeSource = [];
let filteredVehicles = [];
let visibleCount = 0;
let infiniteScrollBound = false;
let searchPanelCompactBound = false;

const INITIAL_VISIBLE = 20;
const LOAD_STEP = 20;
const MIN_FILTER_YEAR = 1950;

window.VGI_DATA = { vehicles };

async function fetchJson(url) {
  const response = await fetch(url, { headers: { Accept: "application/json" } });
  if (!response.ok) {
    throw new Error(`Request failed: ${response.status}`);
  }
  return response.json();
}

function formatPrice(value) {
  return `R ${currency.format(value)}`;
}

function formatMileage(value) {
  return `${currency.format(value)} km`;
}

function updateWhatsAppLink(vehicle = null) {
  const link = document.querySelector('.whatsapp-float');
  if (!link) {
    return;
  }

  const configured = String(link.getAttribute('data-wa-number') || '').replace(/\D/g, '');
  const baseNumber = configured || '27789796523';
  const message = vehicle
    ? `Hello VGi Cars, I would like to chat about ${vehicle.name}. ${window.location.href}`
    : 'Hello VGi Cars, I would like to chat about a vehicle.';

  link.href = `https://wa.me/${baseNumber}?text=${encodeURIComponent(message)}`;
}

function vehicleCardTemplate(vehicle) {
  const image = vehicle.image || "images/hero-bg.png";
  return `
    <article class="vehicle-card reveal">
      <div class="vehicle-image">
        <img src="${image}" alt="${vehicle.name}" loading="lazy" />
        <button class="fav-btn" aria-label="Save ${vehicle.name}"><i class="fa-regular fa-heart"></i></button>
      </div>
      <div class="vehicle-content">
        <h3>${vehicle.name}</h3>
        <p class="meta">${vehicle.engine || "Engine"} � ${vehicle.transmission || "Auto"} � ${formatMileage(vehicle.mileage || 0)}</p>
        <div class="price-row">
          <span class="price-tag">${formatPrice(vehicle.price || 0)}</span>
          <a class="btn btn-outline" href="vehicle?id=${vehicle.id}">View Details</a>
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

  const isDesktop = window.matchMedia("(min-width: 861px)").matches;
  if (!isDesktop) {
    reveals.forEach((item) => item.classList.add("in-view"));
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

  const isDesktop = window.matchMedia("(min-width: 861px)").matches;
  if (!isDesktop) {
    heroCopy.classList.add("in-view");
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

function getCurrentYear() {
  return new Date().getFullYear();
}

function populateSelectOptions(selectNode, values, placeholder) {
  selectNode.innerHTML = `<option value="">${placeholder}</option>`;
  values.forEach((value) => {
    selectNode.insertAdjacentHTML("beforeend", `<option value="${value}">${value}</option>`);
  });
}

function syncModelOptions(list) {
  const makeSelect = document.getElementById("filterMake");
  const modelSelect = document.getElementById("filterModel");

  if (!makeSelect || !modelSelect) {
    return;
  }

  const selectedMake = makeSelect.value;
  const previousModel = modelSelect.value;
  const models = [...new Set(
    list
      .filter((item) => !selectedMake || item.make === selectedMake)
      .map((item) => item.model)
      .filter(Boolean)
  )].sort();

  populateSelectOptions(modelSelect, models, "All Models");

  if (models.includes(previousModel)) {
    modelSelect.value = previousModel;
  }
}

function syncYearOptions() {
  const yearMinSelect = document.getElementById("filterYearMin");
  const yearMaxSelect = document.getElementById("filterYearMax");

  if (!yearMinSelect || !yearMaxSelect) {
    return;
  }

  const currentYear = getCurrentYear();
  const selectedYearMin = Number(yearMinSelect.value || MIN_FILTER_YEAR);
  const previousYearMax = Number(yearMaxSelect.value || 0);
  const yearMaxValues = [];

  for (let year = selectedYearMin; year <= currentYear; year += 1) {
    yearMaxValues.push(year);
  }

  populateSelectOptions(yearMaxSelect, yearMaxValues, "Max Year");

  if (previousYearMax >= selectedYearMin) {
    yearMaxSelect.value = String(previousYearMax);
  } else if (previousYearMax !== 0) {
    yearMaxSelect.value = String(selectedYearMin);
  }
}

function populateFilters(list) {
  const makeSelect = document.getElementById("filterMake");
  const modelSelect = document.getElementById("filterModel");
  const yearMin = document.getElementById("filterYearMin");
  const yearMax = document.getElementById("filterYearMax");

  if (!makeSelect || !modelSelect || !yearMin || !yearMax) {
    return;
  }

  makeSelect.innerHTML = '<option value="">All Makes</option>';

  const makes = [...new Set(list.map((item) => item.make).filter(Boolean))].sort();
  const yearValues = [];
  const currentYear = getCurrentYear();

  for (let year = MIN_FILTER_YEAR; year <= currentYear; year += 1) {
    yearValues.push(year);
  }

  makes.forEach((make) => {
    makeSelect.insertAdjacentHTML("beforeend", `<option value="${make}">${make}</option>`);
  });

  populateSelectOptions(yearMin, yearValues, "Min Year");
  syncModelOptions(list);
  syncYearOptions();
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

function sortVehicles(list, sortMode) {
  const sorted = list.slice();

  if (sortMode === "price_asc") {
    sorted.sort((a, b) => Number(a.price || 0) - Number(b.price || 0));
    return sorted;
  }

  if (sortMode === "price_desc") {
    sorted.sort((a, b) => Number(b.price || 0) - Number(a.price || 0));
    return sorted;
  }

  sorted.sort((a, b) => Number(b.id || 0) - Number(a.id || 0));
  return sorted;
}

function renderHomeResults() {
  const maxVisible = Math.min(visibleCount, filteredVehicles.length);
  renderFeatured(filteredVehicles.slice(0, maxVisible));
}

function maybeLoadMoreOnScroll() {
  if (!filteredVehicles.length || visibleCount >= filteredVehicles.length) {
    return;
  }

  const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 240;
  if (!nearBottom) {
    return;
  }

  visibleCount = Math.min(visibleCount + LOAD_STEP, filteredVehicles.length);
  renderHomeResults();
}

function initSearchPanelToggle() {
  const panel = document.getElementById("inventory");
  const toggle = document.getElementById("searchPanelToggle");
  const form = document.getElementById("inventorySearchForm");

  if (!panel || !toggle || !form) {
    return;
  }

  const syncCompactState = () => {
    const panelTop = panel.getBoundingClientRect().top;
    const stickyTop = parseFloat(getComputedStyle(panel).top || "0") || 0;
    const shouldCompact = panelTop <= stickyTop + 1;

    panel.classList.toggle("is-compact", shouldCompact);

    if (!shouldCompact) {
      panel.classList.remove("is-open");
      form.hidden = false;
      toggle.setAttribute("aria-expanded", "false");
      return;
    }

    if (!panel.classList.contains("is-open")) {
      form.hidden = true;
      toggle.setAttribute("aria-expanded", "false");
    }
  };

  toggle.addEventListener("click", () => {
    if (!panel.classList.contains("is-compact")) {
      return;
    }

    const isOpen = panel.classList.contains("is-open");
    panel.classList.toggle("is-open", !isOpen);
    form.hidden = isOpen;
    toggle.setAttribute("aria-expanded", isOpen ? "false" : "true");
  });

  syncCompactState();

  if (!searchPanelCompactBound) {
    window.addEventListener("scroll", syncCompactState, { passive: true });
    window.addEventListener("resize", syncCompactState);
    searchPanelCompactBound = true;
  }
}

function initHomePage() {
  const form = document.getElementById("inventorySearchForm");
  if (!form) {
    return;
  }

  initSearchPanelToggle();

  homeSource = vehicles.slice();
  populateFilters(homeSource);

  const applyFilters = () => {
    const make = document.getElementById("filterMake").value;
    const model = document.getElementById("filterModel").value;
    const yearFrom = Number(document.getElementById("filterYearMin").value || 0);
    const yearTo = Number(document.getElementById("filterYearMax").value || 9999);
    const price = Number(document.getElementById("filterPrice").value || Number.MAX_SAFE_INTEGER);
    const sortMode = document.getElementById("filterSort")?.value || "recent";

    const filtered = homeSource.filter((item) => {
      if (make && item.make !== make) return false;
      if (model && item.model !== model) return false;
      if (Number(item.year) < yearFrom || Number(item.year) > yearTo) return false;
      if (Number(item.price) > price) return false;
      return true;
    });

    filteredVehicles = sortVehicles(filtered, sortMode);
    visibleCount = Math.min(INITIAL_VISIBLE, filteredVehicles.length);
    renderHomeResults();
  };

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    applyFilters();
  });

  const makeField = document.getElementById("filterMake");
  const modelField = document.getElementById("filterModel");
  const yearMinField = document.getElementById("filterYearMin");
  const yearMaxField = document.getElementById("filterYearMax");
  const priceField = document.getElementById("filterPrice");
  const sortField = document.getElementById("filterSort");

  if (makeField) {
    makeField.addEventListener("change", () => {
      syncModelOptions(homeSource);
      applyFilters();
    });
  }

  if (yearMinField) {
    yearMinField.addEventListener("change", () => {
      syncYearOptions();
      applyFilters();
    });
  }

  [modelField, yearMaxField, priceField, sortField].forEach((field) => {
    if (field) {
      field.addEventListener("change", applyFilters);
    }
  });

  if (!infiniteScrollBound) {
    window.addEventListener("scroll", maybeLoadMoreOnScroll, { passive: true });
    infiniteScrollBound = true;
  }

  applyFilters();
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

async function initVehiclePage() {
  const title = document.getElementById("vehicleTitle");
  if (!title) {
    return;
  }

  const params = new URLSearchParams(window.location.search);
  const id = Number(params.get("id"));
  if (!id) {
    return;
  }

  const detailResult = await fetchJson(`api/car?id=${id}`);
  if (!detailResult.success || !detailResult.data) {
    return;
  }

  const selectedVehicle = detailResult.data;
  window.VGI_DATA.selectedVehicle = selectedVehicle;
  updateWhatsAppLink(selectedVehicle);

  document.getElementById("vehicleTitle").textContent = selectedVehicle.name;
  document.getElementById("crumbVehicle").textContent = selectedVehicle.name;
  document.getElementById("vehiclePrice").textContent = formatPrice(selectedVehicle.price);
  document.getElementById("vehicleDescription").textContent = selectedVehicle.description || "";
  document.getElementById("financePrice").value = selectedVehicle.price || 0;

  const chips = [
    `${selectedVehicle.year || ""}`,
    selectedVehicle.transmission || "",
    selectedVehicle.fuel || "",
    selectedVehicle.engine || "",
    selectedVehicle.location || ""
  ].filter(Boolean);

  document.getElementById("specChips").innerHTML = chips.map((item) => `<li>${item}</li>`).join("");

  const specsHtml = Object.entries(selectedVehicle.specs || {})
    .map(([key, value]) => `<div class="spec-row"><span>${key}</span><strong>${value}</strong></div>`)
    .join("");

  document.getElementById("specTable").innerHTML = specsHtml;
  document.getElementById("featuresList").innerHTML = (selectedVehicle.features || []).map((item) => `<li>${item}</li>`).join("");

  const related = vehicles.filter((vehicle) => vehicle.id !== selectedVehicle.id).slice(0, 4);
  const relatedNode = document.getElementById("relatedVehicles");
  if (relatedNode) {
    relatedNode.innerHTML = related.map(vehicleCardTemplate).join("");
    setRevealObserver();
  }

  document.dispatchEvent(new CustomEvent("vehicleDataReady", { detail: selectedVehicle }));
}

async function loadVehicles() {
  const result = await fetchJson("api/cars");
  if (result.success && Array.isArray(result.data)) {
    vehicles = result.data;
    window.VGI_DATA.vehicles = vehicles;
  }
}

async function boot() {
  setYear();
  setHeaderBehavior();
  initHeroScrollReveal();
  setRevealObserver();
  setTabs();
  updateWhatsAppLink();

  try {
    await loadVehicles();
    initHomePage();
    await initVehiclePage();
  } catch (error) {
    console.error("Failed to load vehicles", error);
  }
}

boot();
