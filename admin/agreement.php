<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/includes/sales_documents.php';
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

.agreement-document {
  position: relative;
  overflow: hidden;
  isolation: isolate;
}

.agreement-watermark {
  z-index: 0 !important;
}

.agreement-content {
  position: relative;
  z-index: 2 !important;
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

  .document-wrap {
    font-size: 12px !important;
    line-height: 1.5 !important;
  }

  .agreement-document {
    position: relative !important;
    overflow: hidden !important;
    isolation: isolate !important;
  }

  .agreement-watermark {
    position: absolute !important;
    z-index: 0 !important;
    opacity: 0.06 !important;
  }

  .agreement-content {
    position: relative !important;
    z-index: 2 !important;
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
            <button type="button" class="btn btn-primary" onclick="window.print()">Print Agreement</button>
          </div>
          <?= render_agreement_content($sale, $items, $company) ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>