function initVehicleGallery(vehicle) {
  const mainImage = document.getElementById("mainVehicleImage");
  const thumbRow = document.getElementById("thumbRow");
  const prevButton = document.getElementById("galleryPrev");
  const nextButton = document.getElementById("galleryNext");
  const currentNode = document.getElementById("galleryCurrent");
  const totalNode = document.getElementById("galleryTotal");
  const counter = document.getElementById("galleryCounter");
  const wrap = document.getElementById("mainImageWrap");

  if (!mainImage || !thumbRow || !vehicle || !Array.isArray(vehicle.gallery)) {
    return;
  }

  const images = vehicle.gallery.filter(Boolean);
  if (!images.length) {
    return;
  }

  const hasMany = images.length > 1;
  let current = 0;
  let autoPlayTimer = null;
  let touchStartX = null;

  if (totalNode) {
    totalNode.textContent = String(images.length);
  }

  if (prevButton) {
    prevButton.classList.toggle("is-hidden", !hasMany);
  }
  if (nextButton) {
    nextButton.classList.toggle("is-hidden", !hasMany);
  }
  if (counter) {
    counter.classList.toggle("is-hidden", !hasMany);
  }

  function normalizeIndex(index) {
    return (index + images.length) % images.length;
  }

  function goTo(index) {
    current = normalizeIndex(index);
    updateMain();
  }

  function next() {
    goTo(current + 1);
  }

  function previous() {
    goTo(current - 1);
  }

  function renderThumbs() {
    thumbRow.innerHTML = images
      .map(
        (image, index) => `
          <button class="thumb ${index === current ? "active" : ""}" data-index="${index}" type="button" aria-label="View image ${index + 1}">
            <img src="${image}" alt="${vehicle.name} image ${index + 1}" loading="lazy" />
          </button>
        `
      )
      .join("");

    thumbRow.querySelectorAll(".thumb").forEach((thumb) => {
      thumb.addEventListener("click", () => {
        goTo(Number(thumb.dataset.index));
      });
    });

    thumbRow.addEventListener(
      "wheel",
      (event) => {
        if (Math.abs(event.deltaY) <= Math.abs(event.deltaX)) {
          return;
        }
        event.preventDefault();
        thumbRow.scrollLeft += event.deltaY;
      },
      { passive: false }
    );
  }

  function updateMain() {
    mainImage.src = images[current];
    mainImage.alt = `${vehicle.name} image ${current + 1}`;

    if (currentNode) {
      currentNode.textContent = String(current + 1);
    }

    thumbRow.querySelectorAll(".thumb").forEach((thumb, index) => {
      thumb.classList.toggle("active", index === current);
      if (index === current) {
        const targetLeft = thumb.offsetLeft - (thumbRow.clientWidth - thumb.offsetWidth) / 2;
        thumbRow.scrollTo({
          left: Math.max(0, targetLeft),
          behavior: "smooth",
        });
      }
    });
  }

  function startAutoPlay() {
    if (!hasMany) {
      return;
    }

    stopAutoPlay();
    autoPlayTimer = window.setInterval(next, 5000);
  }

  function stopAutoPlay() {
    if (autoPlayTimer) {
      window.clearInterval(autoPlayTimer);
      autoPlayTimer = null;
    }
  }

  renderThumbs();
  updateMain();

  if (prevButton) {
    prevButton.addEventListener("click", previous);
  }

  if (nextButton) {
    nextButton.addEventListener("click", next);
  }

  if (wrap) {
    wrap.addEventListener("mouseenter", stopAutoPlay);
    wrap.addEventListener("mouseleave", startAutoPlay);

    wrap.addEventListener("touchstart", (event) => {
      touchStartX = event.changedTouches[0]?.clientX ?? null;
    });

    wrap.addEventListener("touchend", (event) => {
      if (touchStartX === null) {
        return;
      }

      const touchEndX = event.changedTouches[0]?.clientX ?? touchStartX;
      const delta = touchEndX - touchStartX;
      touchStartX = null;

      if (Math.abs(delta) < 30) {
        return;
      }

      if (delta > 0) {
        previous();
      } else {
        next();
      }
    });
  }

  document.addEventListener("keydown", (event) => {
    if (!hasMany) {
      return;
    }

    if (event.key === "ArrowLeft") {
      previous();
    }

    if (event.key === "ArrowRight") {
      next();
    }
  });

  startAutoPlay();
}

document.addEventListener("vehicleDataReady", (event) => {
  initVehicleGallery(event.detail);
});
