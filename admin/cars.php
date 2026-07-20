<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Cars';
require __DIR__ . '/_header.php';

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$q = trim($_GET['q'] ?? '');

$filters = ['q' => $q];
$total = $carService->count($filters);
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}

$cars = $carService->listPaginated($page, $perPage, $filters);
?>
<div class="pagetitle">
  <h1>Cars</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item active">Cars</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <a href="add-car" class="btn btn-secondary mt-4">Add a Car</a>
          <form method="get" class="row g-2 my-3">
            <div class="col-md-5">
              <input type="text" name="q" class="form-control" placeholder="Search make, model, year..." value="<?= h($q) ?>">
            </div>
            <div class="col-md-2">
              <button class="btn btn-outline-secondary w-100" type="submit">Search</button>
            </div>
          </form>
          <table class="table datatable align-middle">
            <thead>
              <tr>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price</th>
                <th>Mileage</th>
                <th>Date Added</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cars as $car): ?>
                <tr>
                  <td><?= h($car['make']) ?></td>
                  <td><?= h($car['model']) ?></td>
                  <td><?= (int) $car['year'] ?></td>
                  <td><?= h((string) $car['price']) ?></td>
                  <td><?= (int) ($car['mileage'] ?? 0) ?></td>
                  <td><?= h($car['created_at'] ?? '') ?></td>
                  <td>
                    <a href="edit-car?id=<?= (int) $car['car_id'] ?>" class="edit-btn"><i class="bi bi-pencil"></i></a>
                    <a href="sale-form.php?car_id=<?= (int) $car['car_id'] ?>" class="edit-btn" title="Generate Invoice"><i class="bi bi-receipt"></i></a>
                    <a href="actions/delete_car.php?id=<?= (int) $car['car_id'] ?>" class="delete-btn" onclick="return confirm('Delete this car?')">Delete</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
