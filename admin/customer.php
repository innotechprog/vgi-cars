<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Customer History';
require __DIR__ . '/_header.php';

$customerId = (int) ($_GET['id'] ?? 0);
$customer = $salesService->findCustomer($customerId);
$sales = $customerId > 0 ? $salesService->listCustomerSales($customerId) : [];

if (!$customer) {
    echo '<div class="alert alert-danger">Customer not found.</div>';
    require __DIR__ . '/_footer.php';
    return;
}
?>
<div class="pagetitle">
  <h1>Customer History</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item"><a href="customers.php">Customers</a></li>
      <li class="breadcrumb-item active">History</li>
    </ol>
  </nav>
</div>

<section class="section profile">
  <div class="row">
    <div class="col-xl-4">
      <div class="card">
        <div class="card-body pt-4 d-flex flex-column">
          <h5 class="card-title"><?= h(trim(($customer['first_names'] ?? '') . ' ' . ($customer['last_name'] ?? ''))) ?></h5>
          <div class="mb-3">
            <a href="sale-form.php?customer_id=<?= (int) $customer['customer_id'] ?>" class="btn btn-primary btn-sm">Generate Invoice</a>
          </div>
          <p class="mb-1"><strong>ID:</strong> <?= h($customer['id_number'] ?? '') ?></p>
          <p class="mb-1"><strong>Email:</strong> <?= h($customer['email'] ?? '') ?></p>
          <p class="mb-1"><strong>Cellphone:</strong> <?= h($customer['cellphone'] ?? '') ?></p>
          <p class="mb-1"><strong>Address:</strong> <?= h(trim(($customer['address_line1'] ?? '') . ' ' . ($customer['address_line2'] ?? '') . ', ' . ($customer['city'] ?? '') . ' ' . ($customer['state_region'] ?? '') . ' ' . ($customer['postal_code'] ?? ''))) ?></p>
          <hr>
          <p class="mb-1"><strong>Purchases:</strong> <?= (int) ($customer['total_sales'] ?? 0) ?></p>
          <p class="mb-1"><strong>Total Spent:</strong> R<?= number_format((float) ($customer['total_spent'] ?? 0), 2) ?></p>
          <p class="mb-1"><strong>Last Purchase:</strong> <?= h($customer['last_purchase_date'] ?? '') ?></p>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card">
        <div class="card-body pt-3">
          <h5 class="card-title">Cars Purchased</h5>
          <?php foreach ($sales as $sale): ?>
            <?php $items = $salesService->listSaleItems((int) $sale['sale_id']); ?>
            <div class="border rounded p-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <strong>Invoice #<?= h($sale['invoice_number']) ?></strong><br>
                  <small><?= h($sale['sale_date']) ?> | <?= h($sale['sale_number']) ?></small>
                </div>
                <div class="text-end">
                  <strong>R<?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?></strong><br>
                  <a href="invoice.php?sale_id=<?= (int) $sale['sale_id'] ?>" class="btn btn-sm btn-outline-primary">Invoice</a>
                  <a href="agreement.php?sale_id=<?= (int) $sale['sale_id'] ?>" class="btn btn-sm btn-outline-secondary">Agreement</a>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Description</th>
                      <th>Reg No</th>
                      <th>VIN</th>
                      <th>Engine</th>
                      <th>Color</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($items as $item): ?>
                      <tr>
                        <td><?= h($item['vehicle_description']) ?></td>
                        <td><?= h($item['registration_number'] ?? '') ?></td>
                        <td><?= h($item['vin_number'] ?? '') ?></td>
                        <td><?= h($item['engine_number'] ?? '') ?></td>
                        <td><?= h($item['color'] ?? '') ?></td>
                        <td>R<?= number_format((float) ($item['line_total'] ?? 0), 2) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>