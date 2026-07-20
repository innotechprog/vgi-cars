<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$car = null;
$images = [];
if (isset($_GET['id']) && (int) $_GET['id'] > 0) {
  $car = $carService->find((int) $_GET['id']);
  if ($car) {
    $images = $carService->findImages((int) $car['car_id']);
  }
}

$pageTitle = $car ? 'Edit Car Information' : 'Add a car';
$pageStyles = <<<'CSS'
#drop-zone:hover {
    border-color: #0d6efd !important;
    background: #e7f1ff !important;
}

#preview-container .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

#preview-container .card:hover:not(.dragging-card) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.dragging-card {
    opacity: 0.6 !important;
    transform: scale(0.95) !important;
    transition: none !important;
}

.dragging-card:hover {
    transform: scale(0.95) !important;
    box-shadow: none !important;
}

#preview-container .card img {
    transition: transform 0.3s;
}

#preview-container .card:hover img {
    transform: scale(1.05);
}

.picker-card {
    position: relative;
    overflow: hidden;
    will-change: transform;
    transform: translateZ(0);
    backface-visibility: hidden;
    background: #fff;
    animation: fadeInScale 0.3s ease;
}

.picker-card img {
    display: block;
    width: 100%;
    height: 120px;
    object-fit: cover;
    background: #f0f0f0;
}

.picker-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
    z-index: 10;
}

.picker-card.selected {
    border: 2px solid #0d6efd;
}

.picker-card.selected[data-order="1"] {
    border: 3px solid #dc3545;
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
}

.picker-card.selected[data-order="1"]::after {
    background: #dc3545;
    content: "★1";
    font-size: 1rem;
}

.picker-card .card-body {
    background: #fff;
}

#picker-preview {
    scrollbar-width: thin;
    scrollbar-color: #0d6efd #e9ecef;
}

#picker-preview::-webkit-scrollbar {
    height: 6px;
}

#picker-preview::-webkit-scrollbar-track {
    background: #e9ecef;
    border-radius: 3px;
}

#picker-preview::-webkit-scrollbar-thumb {
    background: #0d6efd;
    border-radius: 3px;
}

#picker-preview::-webkit-scrollbar-thumb:hover {
    background: #0b5ed7;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

@keyframes popIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.picker-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.picker-card .position-relative {
    position: relative;
    background: #fff;
    overflow: hidden;
}

.picker-card::after {
    content: attr(data-order);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: #0d6efd;
    color: white;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    z-index: 10;
    pointer-events: none;
}

.picker-card.selected::after {
    display: flex;
    animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.picker-card.selected .picker-overlay {
    background: rgba(13, 110, 253, 0.5) !important;
}

.picker-card:hover .picker-overlay {
    background: transparent !important;
}

.picker-card.selected:hover .picker-overlay {
    background: rgba(13, 110, 253, 0.5) !important;
}

.picker-card.selecting {
    animation: pulse 0.3s ease;
}

.drag-over {
    border-left: 3px solid #0d6efd !important;
    background: rgba(13, 110, 253, 0.05) !important;
}

.grip-handle {
    opacity: 0.3;
    transition: opacity 0.2s ease;
    cursor: grab;
}

.grip-handle:hover {
    opacity: 1;
}

.grip-handle:active {
    cursor: grabbing;
}
CSS;
$pageScripts = ['assets/js/carformval.js'];
require __DIR__ . '/_header.php';
?>
<div class="pagetitle">
  <h1><?= $car ? 'Edit Car Information' : 'Add a car' ?></h1>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <form id="carForm" class="mt-4" action="actions/process_car.php" method="POST" enctype="multipart/form-data">
            <?php if ($car): ?>
            <input type="hidden" name="car_id" value="<?= (int) $car['car_id'] ?>">
            <?php endif; ?>
            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label">Year</label>
                <select class="form-select" aria-label="Year select" name="year">
                  <option value="">Select Year</option>
                  <?php
                  $currentYear = (int) date('Y');
                  for ($year = $currentYear; $year >= 1900; $year--) {
                      echo '<option value="' . $year . '">' . $year . '</option>';
                  }
                  ?>
                </select>
                <span class="text-danger" id="year-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="mm_code" class="form-label">MM Code</label>
                <input type="text" class="form-control" name="mm_code" id="mm_code">
                <span class="text-danger" id="mm-code-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="make" class="form-label">Make</label>
                <select class="form-select" name="make" id="make">
                  <option value="">Select Make</option>
                </select>
                <span class="text-danger" id="make-error"></span>

                <div id="custom-make-container" class="mt-2" style="display: none;">
                  <label for="custom_make" class="form-label">Please specify the make:</label>
                  <input type="text" class="form-control" name="custom_make" id="custom_make" placeholder="Enter custom make">
                  <span class="text-danger" id="custom-make-error"></span>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="model" class="form-label">Model</label>
                <select class="form-select" name="model" id="model" disabled>
                  <option value="">Select Make First</option>
                </select>
                <span class="text-danger" id="model-error"></span>

                <div id="custom-model-container" class="mt-2" style="display: none;">
                  <label for="custom_model" class="form-label">Please specify the model:</label>
                  <input type="text" class="form-control" name="custom_model" id="custom_model" placeholder="Enter custom model">
                  <span class="text-danger" id="custom-model-error"></span>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="variant" class="form-label">Variant</label>
                <select class="form-select" name="variant" id="variant" disabled>
                  <option value="">Select Model First</option>
                </select>
                <span class="text-danger" id="variant-error"></span>

                <div id="custom-variant-container" class="mt-2" style="display: none;">
                  <label for="custom_variant_input" class="form-label">Please specify the variant:</label>
                  <input type="text" class="form-control" name="custom_variant_input" id="custom_variant_input" placeholder="Enter custom variant">
                  <span class="text-danger" id="custom-variant-input-error"></span>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="custom_variant" class="form-label">Custom Variant</label>
                <input type="text" class="form-control" name="custom_variant" id="custom_variant">
                <span class="text-danger" id="custom-variant-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="vin" class="form-label">VIN Optional</label>
                <input type="text" class="form-control" name="vin" id="vin">
                <span class="text-danger" id="vin-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="mileage" class="form-label">Mileage</label>
                <input type="number" class="form-control" name="mileage" id="mileage" min="0">
                <span class="text-danger" id="mileage-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" name="price" id="price" min="0" step="0.01">
                <span class="text-danger" id="price-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="color" class="form-label">Color</label>
                <select class="form-select" name="color" id="color">
                  <option value="">Select Color</option>
                  <option value="White">White</option>
                  <option value="Black">Black</option>
                  <option value="Silver">Silver</option>
                  <option value="Gray">Gray</option>
                  <option value="Red">Red</option>
                  <option value="Blue">Blue</option>
                  <option value="Green">Green</option>
                  <option value="Yellow">Yellow</option>
                  <option value="Orange">Orange</option>
                  <option value="Brown">Brown</option>
                  <option value="Beige">Beige</option>
                  <option value="Gold">Gold</option>
                  <option value="Purple">Purple</option>
                  <option value="Pink">Pink</option>
                  <option value="Maroon">Maroon</option>
                  <option value="Navy">Navy</option>
                  <option value="Burgundy">Burgundy</option>
                  <option value="Champagne">Champagne</option>
                  <option value="Bronze">Bronze</option>
                  <option value="Titanium">Titanium</option>
                  <option value="Pearl White">Pearl White</option>
                  <option value="Metallic Silver">Metallic Silver</option>
                  <option value="Matte Black">Matte Black</option>
                  <option value="Other">Other</option>
                </select>
                <span class="text-danger" id="color-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="transmission" class="form-label">Transmission</label>
                <select class="form-select" aria-label="Transmission select" name="transmission" id="transmission">
                  <option value="">Select Transmission</option>
                  <option value="Manual">Manual</option>
                  <option value="Automatic">Automatic</option>
                </select>
                <span class="text-danger" id="transmission-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="fuel_type" class="form-label">Fuel Type</label>
                <select class="form-select" aria-label="Fuel Type select" name="fuel_type" id="fuel_type">
                  <option value="">Select Fuel Type</option>
                  <option value="Petrol">Petrol</option>
                  <option value="Diesel">Diesel</option>
                  <option value="Electric">Electric</option>
                  <option value="Hybrid">Hybrid</option>
                </select>
                <span class="text-danger" id="fuel-type-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                <span class="text-danger" id="description-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label">Eligible For Finance</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="finance_eligible" id="yes" value="Yes" checked>
                  <label class="form-check-label" for="yes">Yes</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="finance_eligible" id="no" value="No">
                  <label class="form-check-label" for="no">No</label>
                </div>
                <span class="text-danger" id="finance-eligible-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label">Used or New</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="condition_type" id="used" value="Used" checked>
                  <label class="form-check-label" for="used">Used</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="condition_type" id="new" value="New">
                  <label class="form-check-label" for="new">New</label>
                </div>
                <span class="text-danger" id="condition-type-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label">Condition</label>
                <select class="form-select" aria-label="Condition select" name="condition">
                  <option value="">Select Condition</option>
                  <option value="Very Poor">Very Poor</option>
                  <option value="Poor">Poor</option>
                  <option value="Fair">Fair</option>
                  <option value="Average">Average</option>
                  <option value="Good">Good</option>
                  <option value="Excellent">Excellent</option>
                </select>
                <span class="text-danger" id="condition-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label">Show Car on Website?</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="visibility" id="visibility_yes" value="Yes" checked>
                  <label class="form-check-label" for="visibility_yes">Yes</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="visibility" id="visibility_no" value="No">
                  <label class="form-check-label" for="visibility_no">No</label>
                </div>
                <span class="text-danger" id="visibility-error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <label for="images" class="form-label">Car Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" style="display: none;">
                <div id="drop-zone" class="border rounded p-4 text-center" style="border: 2px dashed #ccc; background: #f8f9fa; cursor: pointer; transition: all 0.3s;">
                  <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                  <h5 class="mt-3">Drag & Drop images here</h5>
                  <p class="text-muted mb-3">or</p>
                  <button type="button" class="btn btn-primary" id="custom-images-btn">
                    <i class="bi bi-images"></i> Select Images in Order
                  </button>
                  <p class="text-muted small mt-2">Supports: JPG, PNG, WEBP, GIF</p>
                  <p class="text-warning small mt-1"><i class="bi bi-info-circle"></i> Choose your images in the order you want them to appear</p>
                </div>
                <span class="text-danger" id="images-error"></span>
                <div id="preview-container" class="mt-3"></div>
              </div>
            </div>

            <div class="modal fade" id="imagePickerModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                  <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                      <i class="bi bi-images"></i> Select Images
                      <span id="picker-counter" class="badge bg-light text-primary ms-2">0 selected</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body p-0" style="height: calc(100vh - 180px); overflow-y: auto;">
                    <div class="container-fluid py-3">
                      <div class="alert alert-info mb-3">
                        <div class="d-flex align-items-center">
                          <i class="bi bi-info-circle fs-4 me-3"></i>
                          <div>
                            <strong>How to select images in order:</strong>
                            <ul class="mb-0 mt-2">
                              <li><strong>Click images in the exact order</strong> you want them to appear on your listing</li>
                              <li>The <strong>first image you select will be the main/primary image</strong></li>
                              <li>Numbered badges (#1, #2, #3...) show the selection order</li>
                              <li>Click again to deselect an image</li>
                              <li>View your selection sequence at the bottom</li>
                              <li>The order you choose here determines how images appear to customers</li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div id="picker-grid" class="row g-2"></div>
                    </div>
                  </div>
                  <div class="modal-footer d-block bg-light" style="height: 120px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <strong>Selected Images (in order):</strong>
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.clearPickerSelection()">
                        <i class="bi bi-x-circle"></i> Clear All
                      </button>
                    </div>
                    <div id="picker-preview" class="d-flex gap-2 overflow-auto mb-2" style="height: 60px;">
                      <div class="text-muted small">No images selected</div>
                    </div>
                    <div class="text-end">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary" onclick="window.confirmImageSelection()">
                        <i class="bi bi-check-circle"></i> Done
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background: rgba(0,0,0,0.95);">
                  <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="modalImageName">Image Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center p-3">
                    <img id="modalImagePreview" src="" alt="Preview" class="img-fluid" style="max-height: 70vh; object-fit: contain;">
                  </div>
                  <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                      <i class="bi bi-x-circle"></i> Close
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div id="progress" class="mt-3" style="display: none;">
              <div class="progress">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
              </div>
              <p id="progressText" class="mt-2"></p>
            </div>

            <div id="message" class="mt-3" style="display: none;"></div>

            <div class="row mb-3">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary"><?= $car ? 'Save Changes' : 'Submit Form' ?></button>
              </div>
            </div>
          </form>

          <?php if ($car): ?>
          <div class="mt-4">
            <h5 class="mb-3">Images</h5>
            <div class="row g-3">
              <?php foreach ($images as $img): ?>
                <div class="col-md-3">
                  <div class="card">
                    <img src="../<?= h(normalize_image_path($img['image_url'] ?? '')) ?>" class="card-img-top" alt="car image">
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                      <span class="badge bg-<?= ((int) $img['is_primary'] === 1) ? 'success' : 'secondary' ?>"><?= ((int) $img['is_primary'] === 1) ? 'Primary' : 'Image' ?></span>
                      <a href="actions/delete_image.php?id=<?= (int) $img['image_id'] ?>&car_id=<?= (int) $car['car_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete image?')">Delete</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
window.existingCar = <?= json_encode($car ?: new stdClass(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>

<?php require __DIR__ . '/_footer.php'; ?>
