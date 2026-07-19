function initVehicleGallery(vehicle) {
  const mainImage = document.getElementById("mainVehicleImage");
  const thumbRow = document.getElementById("thumbRow");

  if (!mainImage || !thumbRow || !vehicle || !Array.isArray(vehicle.gallery)) {
    return;
  }

  const images = vehicle.gallery;
  let current = 0;

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
        current = Number(thumb.dataset.index);
        updateMain();
      });
    });
  }

  function updateMain() {
    mainImage.src = images[current];
    mainImage.alt = `${vehicle.name} image ${current + 1}`;
    thumbRow.querySelectorAll(".thumb").forEach((thumb, index) => {
      thumb.classList.toggle("active", index === current);
    });
  }

  renderThumbs();
  updateMain();
}

document.addEventListener("vehicleDataReady", (event) => {
  initVehicleGallery(event.detail);
});
