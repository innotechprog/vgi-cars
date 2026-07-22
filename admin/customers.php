<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Customers';
require __DIR__ . '/_header.php';

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 15;
$q = trim((string) ($_GET['q'] ?? ''));
$filters = ['q' => $q];
$total = $salesService->countCustomers($filters);
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}
$customers = $salesService->listCustomersPaginated($page, $perPage, $filters);
?>
<div class="pagetitle">
  <h1>Customers</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item active">Customers</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h5 class="card-title mb-0">Customer Records</h5>
            <a href="sale-form.php" class="btn btn-primary">Generate Invoice</a>
          </div>
          <form method="get" class="row g-2 my-3">
            <div class="col-md-5">
              <input type="text" name="q" class="form-control" placeholder="Search customer, ID, email or phone" value="<?= h($q) ?>">
            </div>
            <div class="col-md-2">
              <button class="btn btn-outline-secondary w-100" type="submit">Search</button>
            </div>
          </form>
          <table class="table datatable align-middle">
            <thead>
              <tr>
                <th>Customer</th>
                <th>ID Number</th>
                <th>Email</th>
                <th>Cellphone</th>
                <th>Purchases</th>
                <th>Total Spent</th>
                <th>Last Purchase</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($customers as $customer): ?>
                <tr>
                  <td><?= h(trim(($customer['first_names'] ?? '') . ' ' . ($customer['last_name'] ?? ''))) ?></td>
                  <td><?= h($customer['id_number'] ?? '') ?></td>
                  <td><?= h($customer['email'] ?? '') ?></td>
                  <td><?= h($customer['cellphone'] ?? '') ?></td>
                  <td><?= (int) ($customer['total_sales'] ?? 0) ?></td>
                  <td>R<?= number_format((float) ($customer['total_spent'] ?? 0), 2) ?></td>
                  <td><?= h($customer['last_purchase_date'] ?? '') ?></td>
                  <td>
                    <a href="customer.php?id=<?= (int) $customer['customer_id'] ?>" class="btn btn-sm btn-outline-primary">View History</a>
                    <a href="sale-form.php?customer_id=<?= (int) $customer['customer_id'] ?>" class="btn btn-sm btn-outline-secondary">Generate Invoice</a>
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