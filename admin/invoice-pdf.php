<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/sales_documents.php';

$auth->requireLogin('login.php');
$auth->requireRole('admin', 'login.php');

$saleId = (int) ($_GET['sale_id'] ?? 0);
$sale = $salesService->findSale($saleId);
$items = $saleId > 0 ? $salesService->listSaleItems($saleId) : [];

if (!$sale) {
    http_response_code(404);
    echo 'Invoice not found.';
    exit;
}

$company = sales_company_details($settingsService);
$html = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:helvetica,sans-serif;font-size:12px;color:#111;}h2,h3{text-align:center;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #999;padding:6px;} .text-right{text-align:right;} .mb{margin-bottom:20px;} .no-border td{border:none;padding:2px 0;} .totals{width:45%;margin-left:auto;} </style></head><body>'
    . render_invoice_content($sale, $items, $company, true)
    . '</body></html>';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(12, 12, 12);
$pdf->SetAutoPageBreak(true, 12);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('invoice-' . $sale['invoice_number'] . '.pdf', 'D');
exit;