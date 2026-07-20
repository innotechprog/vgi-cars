<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/includes/sales_documents.php';
$pageTitle = 'Invoice';
require __DIR__ . '/_header.php';

$saleId = (int) ($_GET['sale_id'] ?? 0);
$sale = $salesService->findSale($saleId);
$items = $saleId > 0 ? $salesService->listSaleItems($saleId) : [];

if (!$sale) {
    echo '<div class="alert alert-danger">Invoice not found.</div>';
    require __DIR__ . '/_footer.php';
    return;
}

$company = sales_company_details($settingsService);
?>
<style>
@media print {
  #header,
  #sidebar,
  #footer,
  .back-to-top,
  .pagetitle,
  .d-print-none {
    display: none !important;
  }

  html,
  body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    background: #fff !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  body,
  .main,
  .section,
  .row,
  .col-lg-10,
  .card,
  .card-body {
    margin: 0 !important;
    padding: 0 !important;
    background: #fff !important;
    box-shadow: none !important;
    border: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
  }

  .main {
    margin-left: 0 !important;
  }

  .section,
  .row,
  .col-lg-10,
  .card,
  .card-body {
    display: block !important;
  }

  .table,
  .table th,
  .table td,
  p,
  div,
  h1,
  h2,
  h3,
  h4,
  h5,
  h6,
  strong,
  span {
    color: #000 !important;
  }

  @page {
    margin: 8mm;
  }
}
</style>
<div class="pagetitle d-print-none">
  <h1>Invoice #<?= h($sale['invoice_number']) ?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="sales">Sales</a></li>
      <li class="breadcrumb-item active">Invoice</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card">
        <div class="card-body p-4">
          <div class="d-print-none d-flex justify-content-end gap-2 mb-3">
            <a href="agreement.php?sale_id=<?= (int) $saleId ?>" class="btn btn-outline-secondary">View Agreement</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Print Invoice</button>
          </div>
          <?= render_invoice_content($sale, $items, $company) ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>