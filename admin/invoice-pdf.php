<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/includes/sales_documents.php';

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

$company = sales_company_details($settingsService, (string) ($sale['sale_brand'] ?? 'sb_autogroup'));
$renderInvoicePdf = function (array $sale, array $items, array $company): string {
    if (function_exists('render_invoice_pdf_content')) {
        return render_invoice_pdf_content($sale, $items, $company);
    }

    return render_invoice_content($sale, $items, $company, true);
};
$html = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:helvetica,sans-serif;font-size:12px;color:#111;margin:0;padding:0;}</style></head><body>'
    . $renderInvoicePdf($sale, $items, $company)
    . '</body></html>';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(6, 6, 6);
$pdf->SetAutoPageBreak(true, 6);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('invoice-' . $sale['invoice_number'] . '.pdf', 'D');
exit;