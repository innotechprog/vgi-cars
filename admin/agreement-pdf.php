<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/sales_documents.php';

$auth->requireLogin('login.php');
$auth->requireRole('admin', 'login.php');

$saleId = (int) ($_GET['sale_id'] ?? 0);
$sale = $salesService->findSale($saleId);
$items = $saleId > 0 ? $salesService->listSaleItems($saleId) : [];

if (!$sale || !$items) {
    http_response_code(404);
    echo 'Sales agreement not found.';
    exit;
}

$company = sales_company_details($settingsService);
$html = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:helvetica,sans-serif;font-size:12px;color:#111;line-height:1.5;}h3{text-align:center;}p{margin:0 0 12px;} .document-wrap{padding:12px;}</style></head><body>'
    . render_agreement_content($sale, $items, $company, true)
    . '</body></html>';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);
$pdf->setFooterMargin(8);
$pdf->setFooterFont(['helvetica', '', 10]);
$pdf->SetMargins(12, 12, 12);
$pdf->SetAutoPageBreak(true, 12);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('agreement-' . $sale['invoice_number'] . '.pdf', 'D');
exit;