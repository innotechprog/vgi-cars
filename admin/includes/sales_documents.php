<?php

function sales_company_details(SettingsService $settingsService, ?string $saleBrand = null): array
{
    $brand = in_array((string) $saleBrand, ['sb_autogroup', 'vgi_cars'], true) ? (string) $saleBrand : 'sb_autogroup';

    $sbAddress = $settingsService->get('company_address', '25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001') ?? '25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001';
    $sbPhone = $settingsService->get('company_phone', '+27 78 979 6523') ?? '+27 78 979 6523';
    $sbEmail = $settingsService->get('site_contact_email', 'autogroupsb@gmail.com') ?? 'autogroupsb@gmail.com';

    $profiles = [
        'sb_autogroup' => [
            'company_name' => $settingsService->get('company_name', 'S&B AUTO GROUP PTY LTD') ?? 'S&B AUTO GROUP PTY LTD',
            'company_address' => $sbAddress,
            'company_phone' => $sbPhone,
            'company_email' => $sbEmail,
            'company_registration' => $settingsService->get('company_registration_number', '240688030015') ?? '240688030015',
            'logo_file' => 'logo.jpg',
            'sale_brand' => 'sb_autogroup',
        ],
        'vgi_cars' => [
            'company_name' => $settingsService->get('vgi_company_name', 'VGI Cars') ?? 'VGI Cars',
            'company_address' => $settingsService->get('vgi_company_address', $sbAddress) ?? $sbAddress,
            'company_phone' => $settingsService->get('vgi_company_phone', $sbPhone) ?? $sbPhone,
            'company_email' => $settingsService->get('vgi_company_email', $sbEmail) ?? $sbEmail,
            'company_registration' => $settingsService->get('vgi_company_registration_number', '240688030015') ?? '240688030015',
            'logo_file' => 'logovgi.jpg',
            'sale_brand' => 'vgi_cars',
        ],
    ];

    return $profiles[$brand];
}

function sales_logo_path(array $company, bool $forPdf = false): string
{
    $logoFile = basename((string) ($company['logo_file'] ?? 'logo.jpg'));
    $logoPath = __DIR__ . '/../assets/img/' . $logoFile;

    if ($forPdf) {
        return realpath($logoPath) ?: $logoPath;
    }
    return 'assets/img/' . $logoFile;
}

function sales_logo_src(array $company, bool $forPdf = false): string
{
    $path = sales_logo_path($company, $forPdf);
    if ($forPdf) {
        $data = @file_get_contents($path);
        if ($data === false) {
            return '';
        }
        return 'data:image/jpeg;base64,' . base64_encode($data);
    }
    return $path;
}

function sales_customer_name(array $sale): string
{
    return trim(($sale['first_names'] ?? '') . ' ' . ($sale['last_name'] ?? ''));
}

function sales_customer_address(array $sale): string
{
    return trim(($sale['address_line1'] ?? '') . ' ' . ($sale['address_line2'] ?? '') . ', ' . ($sale['city'] ?? '') . ' ' . ($sale['state_region'] ?? '') . ' ' . ($sale['postal_code'] ?? ''));
}

function sales_invoice_watermark_text(array $company): string
{
    return $company['company_name'] ?? 'S&B AUTO GROUP PTY LTD';
}

function sales_agreement_watermark_text(array $company): string
{
    return $company['company_name'] ?? 'S&B AUTO GROUP PTY LTD';
}

function sales_terms_conditions_block(bool $forPdf, string $fontFamily, string $fontSize): string
{
    $padding = $forPdf ? '10px 12px' : '14px 16px';
    $indent = $forPdf ? '18px' : '22px';

    ob_start();
    ?>
    <div style="margin-top: 20px; border: 1px solid #888; padding: <?= $padding ?>; font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; background: #fafafa;">
        <div style="font-weight: bold; text-transform: uppercase; margin-bottom: 8px; font-family: <?= $fontFamily ?>;">Terms &amp; Conditions Apply</div>
        <ol style="margin: 0; padding-left: <?= $indent ?>; font-family: <?= $fontFamily ?>;">
            <li style="margin-bottom: 4px;">Goods are sold <strong>as is (Voetstoots)</strong>.</li>
            <li style="margin-bottom: 4px;">No deposit or cash refunds will be issued.</li>
            <li style="margin-bottom: 4px;">No warranty is provided on any vehicle.</li>
            <li style="margin-bottom: 4px;">Any approved returns will be subject to a 20% deduction.</li>
            <li>No exchange of any vehicle is allowed.</li>
        </ol>
    </div>
    <?php

    return (string) ob_get_clean();
}

function render_invoice_content(array $sale, array $items, array $company, bool $forPdf = false): string
{
    $logoSrc = sales_logo_src($company, $forPdf);
    $watermarkText = sales_invoice_watermark_text($company);
    $fontFamily = 'Arial, Helvetica, sans-serif';
    $fontSize = $forPdf ? '10px' : '13px';
    $headerSize = $forPdf ? '16px' : '24px';
    $metaSize = $forPdf ? '9px' : '12px';
    $totalSize = $forPdf ? '12px' : '15px';
    $watermarkSize = $forPdf ? '36px' : '72px';
    $watermarkColor = $forPdf ? '#f0f0f0' : '#d8d8d8';
    $watermarkWeight = $forPdf ? '200' : '300';
    $watermarkOpacity = $forPdf ? '1' : '0.18';
    $sectionGap = $forPdf ? '12px' : '20px';
    $headerPaddingBottom = $forPdf ? '10px' : '15px';
    $wrapperStyle = $forPdf
        ? "font-family: {$fontFamily}; font-size: {$fontSize}; width: 100%; color: #111; position: relative; overflow: hidden; isolation: isolate;"
        : "font-family: {$fontFamily}; font-size: {$fontSize}; max-width: 900px; margin: 0 auto; padding: 20px; color: #111; position: relative; overflow: hidden; isolation: isolate;";
    $cellPadding = $forPdf ? '3px 5px' : '8px 12px';
    $bodyRowPadding = $forPdf ? '4px 6px' : '10px 12px';
    
    ob_start();
    ?>
    <div class="invoice-document" style="<?= $wrapperStyle ?>">
        <!-- Watermark -->
        <div class="invoice-watermark" style="position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%) rotate(-28deg); font-size: <?= $watermarkSize ?>; font-weight: <?= $watermarkWeight ?>; color: <?= $watermarkColor ?>; -webkit-text-fill-color: <?= $watermarkColor ?>; white-space: nowrap; pointer-events: none; z-index: 0; line-height: 1; text-transform: uppercase; letter-spacing: 0.08em; font-family: <?= $fontFamily ?>; opacity: <?= $watermarkOpacity ?>;">
            <?= h($watermarkText) ?>
        </div>
        
        <div class="invoice-content" style="position: relative; z-index: 3;">
            <!-- Company Header -->
            <div style="text-align: center; border-bottom: 1px solid #000; padding-bottom: <?= $headerPaddingBottom ?>; margin-bottom: <?= $sectionGap ?>;">
                <?php if ($logoSrc !== ''): ?>
                    <div style="margin-bottom: <?= $forPdf ? '6px' : '10px' ?>;">
                        <img src="<?= h($logoSrc) ?>" alt="Company Logo" style="max-width: 200px; height: auto;">
                    </div>
                <?php endif; ?>
                <div style="font-size: <?= $headerSize ?>; font-weight: bold; font-family: <?= $fontFamily ?>;"><?= h($company['company_name']) ?></div>
                <div style="font-size: <?= $metaSize ?>; color: #333; margin-top: 5px; font-family: <?= $fontFamily ?>;">
                    Tel: <?= h($company['company_phone']) ?>
                </div>
                <div style="font-size: <?= $metaSize ?>; color: #333; font-family: <?= $fontFamily ?>;">
                    Address: <?= h($company['company_address']) ?> | Email: <?= h($company['company_email']) ?>
                </div>
            </div>

            <!-- Invoice Number and Date -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: <?= $sectionGap ?>; font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>;">
                <tr>
                    <td style="width: 50%; border: none; padding: 0;">
                        <span style="border: 1px solid #000; padding: 5px 15px; font-weight: bold; display: inline-block; font-family: <?= $fontFamily ?>;">
                            INVOICE #<?= h($sale['invoice_number']) ?>
                        </span>
                    </td>
                    <td style="width: 50%; border: none; padding: 0; text-align: right;">
                        <table style="border-collapse: collapse; margin-left: auto;">
                            <tr>
                                <td style="border: 1px solid #000; padding: 5px 20px; font-weight: bold; text-align: center; font-family: <?= $fontFamily ?>;">
                                    <?= h(date('d/m/Y', strtotime($sale['sale_date']))) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000; border-top: none; padding: 5px 20px; font-weight: bold; text-align: center; font-family: <?= $fontFamily ?>;">
                                    <?= strtoupper(date('F', strtotime($sale['sale_date']))) ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Customer Details -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: <?= $sectionGap ?>; border: 1px solid #888; font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>;">
                <tr>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-weight: bold; width: 120px; background: #f9f9f9; font-family: <?= $fontFamily ?>;">TO:</td>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h(sales_customer_name($sale)) ?></td>
                </tr>
                <tr>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-weight: bold; background: #f9f9f9; font-family: <?= $fontFamily ?>;">ID:</td>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($sale['id_number'] ?? '') ?></td>
                </tr>
                <tr>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-weight: bold; background: #f9f9f9; font-family: <?= $fontFamily ?>;">ADDRESS:</td>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h(sales_customer_address($sale)) ?></td>
                </tr>
                <tr>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-weight: bold; background: #f9f9f9; font-family: <?= $fontFamily ?>;">CELL NUMBER:</td>
                    <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($sale['cellphone'] ?? '') ?></td>
                </tr>
            </table>

            <!-- Vehicle Details -->
            <?php $firstItem = $items[0] ?? []; ?>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: <?= $sectionGap ?>; border: 1px solid #888; font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; font-family: <?= $fontFamily ?>;">Make</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; font-family: <?= $fontFamily ?>;">Model</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; font-family: <?= $fontFamily ?>;">Year</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; font-family: <?= $fontFamily ?>;">Vin Number</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; font-family: <?= $fontFamily ?>;">Engine no</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; font-family: <?= $fontFamily ?>;">Color</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($firstItem['vehicle_make'] ?? '') ?></td>
                        <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($firstItem['vehicle_model'] ?? '') ?></td>
                        <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h((string) ($firstItem['vehicle_year'] ?? '')) ?></td>
                        <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($firstItem['vin_number'] ?? '') ?></td>
                        <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($firstItem['engine_number'] ?? '') ?></td>
                        <td style="border: 1px solid #888; padding: <?= $cellPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($firstItem['color'] ?? '') ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Pricing -->
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #888; font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; width: 16%; font-family: <?= $fontFamily ?>;">Reg no</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: left; font-style: italic; width: 42%; font-family: <?= $fontFamily ?>;">Description of the car</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: right; font-style: italic; width: 20%; font-family: <?= $fontFamily ?>;">Item</th>
                        <th style="border: 1px solid #888; padding: <?= $cellPadding ?>; text-align: right; font-style: italic; width: 22%; font-family: <?= $fontFamily ?>;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($item['registration_number'] ?? '') ?></td>
                            <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; font-family: <?= $fontFamily ?>;"><?= h($item['vehicle_description'] ?? '') ?></td>
                            <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">SELLING PRICE</td>
                            <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">R<?= number_format((float) ($item['line_total'] ?? 0), 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; font-family: <?= $fontFamily ?>;">&nbsp;</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">DEPOSIT</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">R<?= number_format((float) ($sale['deposit_amount'] ?? 0), 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; font-family: <?= $fontFamily ?>;">&nbsp;</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">ADMIN FEE</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">R<?= number_format((float) ($sale['admin_fee_amount'] ?? 0), 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; font-family: <?= $fontFamily ?>;">&nbsp;</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">OUTSTANDING</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-family: <?= $fontFamily ?>;">R<?= number_format((float) ($sale['outstanding_amount'] ?? 0), 2) ?></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td colspan="2" style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; font-family: <?= $fontFamily ?>;">&nbsp;</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-size: <?= $totalSize ?>; font-family: <?= $fontFamily ?>;">TOTAL</td>
                        <td style="border: 1px solid #888; padding: <?= $bodyRowPadding ?>; text-align: right; font-size: <?= $totalSize ?>; font-family: <?= $fontFamily ?>;">R<?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: <?= $forPdf ? '8px' : '14px' ?>; text-align: center; font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; font-weight: bold;">
                Thank you for choosing us
            </div>

            <?= sales_terms_conditions_block($forPdf, $fontFamily, $fontSize) ?>
        </div>
    </div>
    <?php
    return (string) ob_get_clean();
}

function render_invoice_pdf_content(array $sale, array $items, array $company): string
{
    return render_invoice_content($sale, $items, $company, true);
}

function render_agreement_content(array $sale, array $items, array $company, bool $forPdf = false): string
{
    $item = $items[0] ?? [];
    $signatureGap = $forPdf ? '34px' : '80px';
    $logoSrc = sales_logo_src($company, $forPdf);
    $watermarkText = sales_agreement_watermark_text($company);
    $fontFamily = 'Arial, Helvetica, sans-serif';
    $fontSize = $forPdf ? '10px' : '13px';
    $headerSize = $forPdf ? '16px' : '24px';
    $watermarkSize = $forPdf ? '34px' : '64px';
    $watermarkColor = $forPdf ? '#f0f0f0' : '#d8d8d8';
    $watermarkWeight = $forPdf ? '200' : '300';
    $watermarkOpacity = $forPdf ? '1' : '0.18';
    $titleSize = $forPdf ? '14px' : '22px';
    $paragraphGap = $forPdf ? '8px' : '12px';
    $wrapperPadding = $forPdf ? '8px' : '20px';
    
    ob_start();
    ?>
    <div class="agreement-document" style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; max-width: 900px; margin: 0 auto; padding: <?= $wrapperPadding ?>; position: relative; overflow: hidden; color: #111; isolation: isolate;">
        <!-- Watermark -->
        <div class="agreement-watermark" style="position: absolute; top: 44%; left: 50%; transform: translate(-50%, -50%) rotate(-28deg); font-size: <?= $watermarkSize ?>; font-weight: <?= $watermarkWeight ?>; color: <?= $watermarkColor ?>; -webkit-text-fill-color: <?= $watermarkColor ?>; white-space: nowrap; pointer-events: none; z-index: 0; line-height: 1; text-transform: uppercase; letter-spacing: 0.08em; font-family: <?= $fontFamily ?>; opacity: <?= $watermarkOpacity ?>;">
            <?= h($watermarkText) ?>
        </div>
        
        <div class="agreement-content" style="position: relative; z-index: 3;">
            <?php if ($logoSrc !== ''): ?>
                <div style="text-align: center; margin-bottom: <?= $forPdf ? '10px' : '20px' ?>;">
                    <img src="<?= h($logoSrc) ?>" alt="Company Logo" style="max-width: 200px; height: auto;">
                </div>
            <?php endif; ?>
            
            <h3 style="text-align: center; margin-bottom: <?= $forPdf ? '12px' : '25px' ?>; font-size: <?= $titleSize ?>; font-family: <?= $fontFamily ?>; font-weight: bold;">SALES AGREEMENT</h3>
            
            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                This Sales Agreement ("Agreement") is made and entered into on this <?= h(date('jS', strtotime($sale['sale_date']))) ?> day of <?= strtoupper(date('F', strtotime($sale['sale_date']))) ?>, <?= h(date('Y', strtotime($sale['sale_date']))) ?>, by and between:
            </p>
            
            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                <strong>SELLER:</strong> Company Name: <?= h($company['company_name']) ?><br>
                Company Registration Number: <?= h($company['company_registration']) ?><br>
                Address: <?= h($company['company_address']) ?><br>
                Contact Number: <?= h($company['company_phone']) ?>
            </p>

            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                <strong>BUYER:</strong> <?= h(sales_customer_name($sale)) ?><br>
                <strong>ID Number:</strong> <?= h($sale['id_number'] ?? '') ?><br>
                <strong>Address:</strong> <?= h(sales_customer_address($sale)) ?><br>
                <strong>Cell Number:</strong> <?= h($sale['cellphone'] ?? '') ?>
            </p>

            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                <strong>VEHICLE:</strong> <?= h($item['vehicle_description'] ?? '') ?><br>
                <strong>Year:</strong> <?= h((string) ($item['vehicle_year'] ?? '')) ?><br>
                <strong>VIN (Vehicle Identification Number):</strong> <?= h($item['vin_number'] ?? '') ?><br>
                <strong>Colour:</strong> <?= h($item['color'] ?? '') ?>
            </p>

            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                <strong>PURCHASE PRICE:</strong> The total purchase price of the vehicle is ZA R<?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?> to be paid by the Buyer to the Seller at the time of signing this Agreement.
            </p>
            
            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                <strong>PAYMENT TERMS:</strong> The payment of ZAR <?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?> has been recorded under invoice #<?= h($sale['invoice_number']) ?>.
            </p>

            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                <strong>WARRANTY:</strong> No warranty buy as is.
            </p>
            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                As such, we are selling the vehicle strictly "As Is, Where Is" (without any warranty, guarantee, or representation of condition, either expressed or implied, from us, the seller).
            </p>

            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                This condition is confirmed and acknowledged by you (the buyer) based on the following. Prior to payment, you have conducted a satisfactory test drive and inspection of the vehicle. You have confirmed your complete satisfaction with the current physical and mechanical condition of the vehicle.
            </p>
            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                Upon receipt of cleared payment, the sale will be final, and no subsequent claims regarding the vehicle's condition will be accepted.
            </p>
            
            <p style="font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>; line-height: <?= $forPdf ? '1.4' : '1.6' ?>; margin-bottom: <?= $paragraphGap ?>;">
                <strong>NO LIES OR ENCUMBRANCES:</strong> The Seller guarantees that the vehicle is free from any lies, charges, or encumbrances and has full authority to sell the vehicle.
            </p>

            <div style="margin-top: <?= $signatureGap ?>; display: table; width: 100%; font-family: <?= $fontFamily ?>; font-size: <?= $fontSize ?>;">
                <div style="display: table-cell; width: 50%; padding-right: 20px; vertical-align: bottom;">
                    <div style="border-top: 1px solid #000; padding-top: 8px; text-align: center; font-family: <?= $fontFamily ?>;">Customer Signature</div>
                    <div style="margin-top: 10px; text-align: center; font-family: <?= $fontFamily ?>;">Name: <?= h(sales_customer_name($sale)) ?></div>
                    <div style="margin-top: 10px; text-align: center; font-family: <?= $fontFamily ?>;">Date: ____________________</div>
                </div>
                <div style="display: table-cell; width: 50%; padding-left: 20px; vertical-align: bottom;">
                    <div style="border-top: 1px solid #000; padding-top: 8px; text-align: center; font-family: <?= $fontFamily ?>;">Seller Signature</div>
                    <div style="margin-top: 10px; text-align: center; font-family: <?= $fontFamily ?>;">For: <?= h($company['company_name']) ?></div>
                    <div style="margin-top: 10px; text-align: center; font-family: <?= $fontFamily ?>;">Date: ____________________</div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return (string) ob_get_clean();
}

function render_agreement_pdf_content(array $sale, array $items, array $company): string
{
    return render_agreement_content($sale, $items, $company, true);
}