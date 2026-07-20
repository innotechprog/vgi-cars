<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/sales_documents.php';
$pageTitle = 'S&B Auto Group | Sales';
require __DIR__ . '/_header.php';

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 15;
$q = trim((string) ($_GET['q'] ?? ''));
$filters = ['q' => $q];
$total = $salesService->count($filters);
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}
$sales = $salesService->listPaginated($page, $perPage, $filters);
$msg = $_GET['msg'] ?? '';
?>
<div class="pagetitle">
  <h1>Sales</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item active">Sales</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h5 class="card-title mb-0">Invoices and Purchases</h5>
            <a href="sale-form.php" class="btn btn-primary">Generate Invoice</a>
          </div>
          <?php if ($msg): ?><div class="alert alert-info"><?= h($msg) ?></div><?php endif; ?>
          <form method="get" class="row g-2 my-3">
            <div class="col-md-5">
              <input type="text" name="q" class="form-control" placeholder="Search invoice, sale no, customer or ID" value="<?= h($q) ?>">
            </div>
            <div class="col-md-2">
              <button class="btn btn-outline-secondary w-100" type="submit">Search</button>
            </div>
          </form>
          <table class="table datatable align-middle">
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Sale Number</th>
                <th>Customer</th>
                <th>ID Number</th>
                <th>Date</th>
                <th>Total</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sales as $sale): ?>
                <tr>
                  <td>#<?= h($sale['invoice_number']) ?></td>
                  <td><?= h($sale['sale_number']) ?></td>
                  <td><a href="customer.php?id=<?= (int) $sale['customer_id'] ?>"><?= h(trim(($sale['first_names'] ?? '') . ' ' . ($sale['last_name'] ?? ''))) ?></a></td>
                  <td><?= h($sale['id_number'] ?? '') ?></td>
                  <td><?= h($sale['sale_date']) ?></td>
                  <td>R<?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?></td>
                  <td>
                    <a href="invoice.php?sale_id=<?= (int) $sale['sale_id'] ?>" class="btn btn-sm btn-outline-primary">Invoice</a>
                    <a href="agreement.php?sale_id=<?= (int) $sale['sale_id'] ?>" class="btn btn-sm btn-outline-secondary">Agreement</a>
                    <a href="invoice-pdf.php?sale_id=<?= (int) $sale['sale_id'] ?>" class="btn btn-sm btn-outline-dark">Invoice PDF</a>
                    <a href="agreement-pdf.php?sale_id=<?= (int) $sale['sale_id'] ?>" class="btn btn-sm btn-outline-dark">Agreement PDF</a>
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