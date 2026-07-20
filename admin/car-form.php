<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'Car Information';
require __DIR__ . '/_header.php';

$car = null;
$images = [];
if (isset($_GET['id'])) {
    $car = $carService->find((int) $_GET['id']);
    if ($car) {
        $images = $carService->findImages((int) $car['car_id']);
    }
}
?>
<div class="pagetitle">
  <h1>Car Information</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item"><a href="cars">Cars</a></li>
      <li class="breadcrumb-item active"><?= $car ? 'Edit Car Info' : 'Add Car' ?></li>
    </ol>
  </nav>
</div>

<section class="section profile">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#car-edit"><?= $car ? 'Edit Car Information' : 'Add Car Information' ?></button></li>
            <?php if ($car): ?>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#images_tab">Images</button></li>
            <?php endif; ?>
          </ul>
          <div class="tab-content pt-2">
            <div class="tab-pane fade show active profile-edit pt-3" id="car-edit">
              <form method="post" action="actions/process_car.php" enctype="multipart/form-data">
                <?php if ($car): ?>
                  <input type="hidden" name="car_id" value="<?= (int) $car['car_id'] ?>">
                <?php endif; ?>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Year</label><div class="col-md-8 col-lg-9"><input name="year" class="form-control" value="<?= h($car['year'] ?? '') ?>" required></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">MM Code</label><div class="col-md-8 col-lg-9"><input name="mm_code" class="form-control" value="<?= h($car['mm_code'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Make</label><div class="col-md-8 col-lg-9"><input name="make" class="form-control" value="<?= h($car['make'] ?? '') ?>" required></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Model</label><div class="col-md-8 col-lg-9"><input name="model" class="form-control" value="<?= h($car['model'] ?? '') ?>" required></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Variant</label><div class="col-md-8 col-lg-9"><input name="variant" class="form-control" value="<?= h($car['variant'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Custom Variant</label><div class="col-md-8 col-lg-9"><input name="custom_variant" class="form-control" value="<?= h($car['custom_variant'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">VIN Optional</label><div class="col-md-8 col-lg-9"><input name="vin" class="form-control" value="<?= h($car['vin'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Mileage</label><div class="col-md-8 col-lg-9"><input name="mileage" type="number" min="0" class="form-control" value="<?= h($car['mileage'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Price</label><div class="col-md-8 col-lg-9"><input name="price" type="number" step="0.01" class="form-control" value="<?= h($car['price'] ?? '') ?>" required></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Color</label><div class="col-md-8 col-lg-9"><input name="color" class="form-control" value="<?= h($car['color'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Transmission</label><div class="col-md-8 col-lg-9"><input name="transmission" class="form-control" value="<?= h($car['transmission'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Fuel Type</label><div class="col-md-8 col-lg-9"><input name="fuel_type" class="form-control" value="<?= h($car['fuel_type'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Description</label><div class="col-md-8 col-lg-9"><textarea name="description" class="form-control" style="height: 100px"><?= h($car['description'] ?? '') ?></textarea></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Finance Eligible</label><div class="col-md-8 col-lg-9"><select name="finance_eligible" class="form-select"><option value="Yes" <?= (($car['finance_eligible'] ?? 'Yes') === 'Yes') ? 'selected' : '' ?>>Yes</option><option value="No" <?= (($car['finance_eligible'] ?? '') === 'No') ? 'selected' : '' ?>>No</option></select></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Condition Type</label><div class="col-md-8 col-lg-9"><input name="condition_type" class="form-control" value="<?= h($car['condition_type'] ?? 'Used') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Condition</label><div class="col-md-8 col-lg-9"><input name="condition" class="form-control" value="<?= h($car['car_condition'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Visibility</label><div class="col-md-8 col-lg-9"><select name="visibility" class="form-select"><option value="Yes" <?= (($car['visibility'] ?? 'Yes') === 'Yes') ? 'selected' : '' ?>>Yes</option><option value="No" <?= (($car['visibility'] ?? '') === 'No') ? 'selected' : '' ?>>No</option></select></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Status</label><div class="col-md-8 col-lg-9"><input name="status" class="form-control" value="<?= h($car['status'] ?? 'Available') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Upload Images</label><div class="col-md-8 col-lg-9"><input type="file" name="images[]" class="form-control" accept="image/*" multiple></div></div>
                <div class="text-center"><button type="submit" class="btn btn-primary"><?= $car ? 'Save Changes' : 'Add Car' ?></button></div>
              </form>
            </div>
            <?php if ($car): ?>
              <div class="tab-pane fade pt-3" id="images_tab">
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
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
