<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/sales_documents.php';
$pageTitle = 'Sales Agreement';
require __DIR__ . '/_header.php';

$saleId = (int) ($_GET['sale_id'] ?? 0);
$sale = $salesService->findSale($saleId);
$items = $saleId > 0 ? $salesService->listSaleItems($saleId) : [];

if (!$sale || !$items) {
    echo '<div class="alert alert-danger">Sales agreement not found.</div>';
    require __DIR__ . '/_footer.php';
    return;
}

$company = sales_company_details($settingsService);
?>
<style>
.document-wrap {
  font-size: 12px;
  line-height: 1.5;
}

@media print {
  #header,
  #sidebar,
  #footer,
  .back-to-top,
  .pagetitle,
  .d-print-none {
    display: none !important;
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
  }

  .main {
    margin-left: 0 !important;
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
    margin: 12mm;
  }

  .document-wrap {
    font-size: 12px !important;
    line-height: 1.5 !important;
  }
}
</style>
<div class="pagetitle d-print-none">
  <h1>Sales Agreement</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="sales">Sales</a></li>
      <li class="breadcrumb-item active">Agreement</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card">
        <div class="card-body p-4">
          <div class="d-print-none text-end mb-3">
            <a href="agreement-pdf.php?sale_id=<?= (int) $saleId ?>" class="btn btn-outline-dark">Download PDF</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Print Agreement</button>
          </div>
          <?= render_agreement_content($sale, $items, $company) ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>