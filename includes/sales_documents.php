<?php

function sales_company_details(SettingsService $settingsService): array
{
    return [
        'company_name' => $settingsService->get('company_name', 'S&B AUTO GROUP PTY LTD') ?? 'S&B AUTO GROUP PTY LTD',
        'company_address' => $settingsService->get('company_address', '27 Heidelberg Road, Village Main Johannesburg') ?? '27 Heidelberg Road, Village Main Johannesburg',
        'company_phone' => $settingsService->get('company_phone', '061 508 3008 | 064 525 8326 | 073 490 3109 | 010 746 3535') ?? '061 508 3008 | 064 525 8326 | 073 490 3109 | 010 746 3535',
        'company_email' => $settingsService->get('site_contact_email', 'autogroupsb@gmail.com') ?? 'autogroupsb@gmail.com',
        'company_registration' => $settingsService->get('company_registration_number', '240688030015') ?? '240688030015',
    ];
}

  function sales_logo_path(bool $forPdf = false): string
  {
    if ($forPdf) {
        return realpath(__DIR__ . '/../admin/assets/img/logo.jpg') ?: (__DIR__ . '/../admin/assets/img/logo.jpg');
    }

      return 'assets/img/logo.jpg';
  }

  function sales_logo_src(bool $forPdf = false): string
  {
    $path = sales_logo_path($forPdf);
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

function render_invoice_content(array $sale, array $items, array $company, bool $forPdf = false): string
{
    $docFont = $forPdf ? '12px' : '12px';
    $logoSrc = sales_logo_src($forPdf);
    ob_start();
    ?>
    <div class="document-wrap<?= $forPdf ? ' document-pdf' : '' ?>" style="font-size: <?= $docFont ?>; color: #111;">
      <div style="text-align: center; border-bottom: 1px solid #222; padding-bottom: 12px; margin-bottom: 18px;">
        <?php if ($logoSrc !== ''): ?>
          <div style="margin-bottom: 10px; text-align: center;"><img src="<?= h($logoSrc) ?>" alt="Company Logo" style="max-width: 760px; width: 100%; height: auto; display: inline-block;"></div>
        <?php endif; ?>
        <div style="font-size: 28px; font-weight: 700; letter-spacing: 0.02em;"><?= h($company['company_name']) ?></div>
        <div style="margin-top: 6px; font-size: 11px;">Tel: <?= h($company['company_phone']) ?></div>
        <div style="font-size: 11px;">Address: <?= h($company['company_address']) ?> | Email: <?= h($company['company_email']) ?></div>
      </div>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 18px; font-size: 12px;">
        <tr>
          <td style="width: 50%; padding: 0 10px 0 0; vertical-align: top;">
            <div style="border: 1px solid #777; padding: 4px 8px; display: inline-block; font-weight: 700;">INVOICE #<?= h($sale['invoice_number']) ?></div>
          </td>
          <td style="width: 50%; padding: 0; vertical-align: top; text-align: right;">
            <table style="margin-left: auto; border-collapse: collapse; width: 220px;">
              <tr>
                <td style="border: 1px solid #777; text-align: center; padding: 4px 8px; font-weight: 700;"><?= h(date('d/m/Y', strtotime($sale['sale_date']))) ?></td>
              </tr>
              <tr>
                <td style="border: 1px solid #777; border-top: 0; text-align: center; padding: 3px 8px; font-weight: 700;"><?= strtoupper(date('F', strtotime($sale['sale_date']))) ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 18px; font-size: 12px;">
        <tr>
          <td style="padding: 3px 0; width: 120px;"><strong>TO:</strong></td>
          <td style="padding: 3px 0;"><?= h(sales_customer_name($sale)) ?></td>
        </tr>
        <tr>
          <td style="padding: 3px 0;"><strong>ID:</strong></td>
          <td style="padding: 3px 0;"><?= h($sale['id_number'] ?? '') ?></td>
        </tr>
        <tr>
          <td style="padding: 3px 0;"><strong>ADDRESS:</strong></td>
          <td style="padding: 3px 0;"><?= h(sales_customer_address($sale)) ?></td>
        </tr>
        <tr>
          <td style="padding: 3px 0;"><strong>CELL NUMBER:</strong></td>
          <td style="padding: 3px 0;"><?= h($sale['cellphone'] ?? '') ?></td>
        </tr>
      </table>

      <?php $firstItem = $items[0] ?? []; ?>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 18px; font-size: 12px;">
        <thead>
          <tr>
            <th style="border: 1px solid #b7b7b7; padding: 8px; text-align: left; font-style: italic;">Make</th>
            <th style="border: 1px solid #b7b7b7; padding: 8px; text-align: left; font-style: italic;">Model</th>
            <th style="border: 1px solid #b7b7b7; padding: 8px; text-align: left; font-style: italic;">Year</th>
            <th style="border: 1px solid #b7b7b7; padding: 8px; text-align: left; font-style: italic;">Vin Number</th>
            <th style="border: 1px solid #b7b7b7; padding: 8px; text-align: left; font-style: italic;">Engine no</th>
            <th style="border: 1px solid #b7b7b7; padding: 8px; text-align: left; font-style: italic;">Color</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="border: 1px solid #b7b7b7; padding: 10px 8px;"><?= h($firstItem['vehicle_make'] ?? '') ?></td>
            <td style="border: 1px solid #b7b7b7; padding: 10px 8px;"><?= h($firstItem['vehicle_model'] ?? '') ?></td>
            <td style="border: 1px solid #b7b7b7; padding: 10px 8px;"><?= h((string) ($firstItem['vehicle_year'] ?? '')) ?></td>
            <td style="border: 1px solid #b7b7b7; padding: 10px 8px;"><?= h($firstItem['vin_number'] ?? '') ?></td>
            <td style="border: 1px solid #b7b7b7; padding: 10px 8px;"><?= h($firstItem['engine_number'] ?? '') ?></td>
            <td style="border: 1px solid #b7b7b7; padding: 10px 8px;"><?= h($firstItem['color'] ?? '') ?></td>
          </tr>
        </tbody>
      </table>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 18px; font-size: 12px;">
        <thead>
          <tr>
            <th style="border: 1px solid #d0d0d0; padding: 8px; text-align: left; width: 15%; font-style: italic;">Reg no</th>
            <th style="border: 1px solid #d0d0d0; padding: 8px; text-align: left; width: 50%; font-style: italic;">Description of the car</th>
            <th style="border: 1px solid #d0d0d0; padding: 8px; text-align: left; width: 17.5%; font-style: italic;">&nbsp;</th>
            <th style="border: 1px solid #d0d0d0; padding: 8px; text-align: center; width: 17.5%; font-style: italic;">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td style="border: 1px solid #d0d0d0; padding: 12px 8px; vertical-align: top;"><?= h($item['registration_number'] ?? '') ?></td>
              <td style="border: 1px solid #d0d0d0; padding: 12px 8px; vertical-align: top;"><?= h($item['vehicle_description']) ?></td>
              <td style="border: 1px solid #d0d0d0; padding: 12px 8px; vertical-align: top; text-align: right;">SELLING PRICE</td>
              <td style="border: 1px solid #d0d0d0; padding: 12px 8px; vertical-align: top; text-align: right;">R<?= number_format((float) ($item['line_total'] ?? 0), 2) ?></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right;">DEPOSIT</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right;">R<?= number_format((float) ($sale['deposit_amount'] ?? 0), 2) ?></td>
          </tr>
          <tr>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right;">ADMIN FEE</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right;">R<?= number_format((float) ($sale['admin_fee_amount'] ?? 0), 2) ?></td>
          </tr>
          <tr>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right;">OUTSTANDING</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right;">R<?= number_format((float) ($sale['outstanding_amount'] ?? 0), 2) ?></td>
          </tr>
          <tr>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px;">&nbsp;</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right; font-weight: 700;">TOTAL</td>
            <td style="border: 1px solid #d0d0d0; padding: 12px 8px; text-align: right; font-weight: 700;">R<?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php
    return (string) ob_get_clean();
}

function render_agreement_content(array $sale, array $items, array $company, bool $forPdf = false): string
{
    $item = $items[0] ?? [];
    $signatureGap = $forPdf ? '60px' : '80px';
    $logoSrc = sales_logo_src($forPdf);
    ob_start();
    ?>
    <div class="document-wrap<?= $forPdf ? ' document-pdf' : '' ?>">
      <?php if ($logoSrc !== ''): ?>
        <div style="text-align: center; margin-bottom: 16px;"><img src="<?= h($logoSrc) ?>" alt="Company Logo" style="max-width: 760px; width: 100%; height: auto; display: inline-block;"></div>
      <?php endif; ?>
      <h3 class="text-center mb-4">SALES AGREEMENT</h3>
      <p>This Sales Agreement ("Agreement") is made and entered into on this <?= h(date('jS', strtotime($sale['sale_date']))) ?> day of <?= strtoupper(date('F', strtotime($sale['sale_date']))) ?>, <?= h(date('Y', strtotime($sale['sale_date']))) ?>, by and between:</p>
      <p><strong>SELLER:</strong> Company Name: <?= h($company['company_name']) ?><br>
      Company Registration Number: <?= h($company['company_registration']) ?><br>
      Address: <?= h($company['company_address']) ?><br>
      Contact Number: <?= h($company['company_phone']) ?></p>

      <p><strong>BUYER Name:</strong> <?= h(sales_customer_name($sale)) ?><br>
      <strong>I.D Number:</strong> <?= h($sale['id_number'] ?? '') ?><br>
      <strong>ADDRESS:</strong> <?= h(sales_customer_address($sale)) ?><br>
      <strong>CELL NUMBER:</strong> <?= h($sale['cellphone'] ?? '') ?></p>

      <p><strong>VEHICLE:</strong> <?= h($item['vehicle_description'] ?? '') ?><br>
      <strong>Year:</strong> <?= h((string) ($item['vehicle_year'] ?? '')) ?><br>
      <strong>VIN (Vehicle Identification Number):</strong> <?= h($item['vin_number'] ?? '') ?><br>
      <strong>Colour:</strong> <?= h($item['color'] ?? '') ?></p>

      <p><strong>PURCHASE PRICE:</strong> The total purchase price of the vehicle is ZA R<?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?> to be paid by the Buyer to the Seller at the time of signing this Agreement.</p>
      <p><strong>PAYMENT TERMS:</strong> The payment of ZAR <?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?> has been recorded under invoice #<?= h($sale['invoice_number']) ?>.</p>
      <p><strong>WARRANTY:</strong> No warranty buy as is.</p>
      <p>As such, we are selling the vehicle strictly "As Is, Where Is" (without any warranty, guarantee, or representation of condition, either expressed or implied, from us, the seller).</p>
      <p>This condition is confirmed and acknowledged by you (the buyer) based on the following. Prior to payment, you have conducted a satisfactory test drive and inspection of the vehicle. You have confirmed your complete satisfaction with the current physical and mechanical condition of the vehicle.</p>
      <p>Upon receipt of cleared payment, the sale will be final, and no subsequent claims regarding the vehicle's condition will be accepted.</p>
      <p><strong>NO LIES OR ENCUMBRANCES:</strong> The Seller guarantees that the vehicle is free from any lien, charges, or encumbrances and has full authority to sell the vehicle.</p>

      <div style="margin-top: <?= $signatureGap ?>; display: table; width: 100%; table-layout: fixed;">
        <div style="display: table-cell; width: 50%; padding-right: 20px; vertical-align: bottom;">
          <div style="border-top: 1px solid #222; padding-top: 8px; text-align: center;">Customer Signature</div>
          <div style="margin-top: 10px; text-align: center;">Name: <?= h(sales_customer_name($sale)) ?></div>
          <div style="margin-top: 10px; text-align: center;">Date: ____________________</div>
        </div>
        <div style="display: table-cell; width: 50%; padding-left: 20px; vertical-align: bottom;">
          <div style="border-top: 1px solid #222; padding-top: 8px; text-align: center;">Seller Signature</div>
          <div style="margin-top: 10px; text-align: center;">For: <?= h($company['company_name']) ?></div>
          <div style="margin-top: 10px; text-align: center;">Date: ____________________</div>
        </div>
      </div>
    </div>
    <?php
    return (string) ob_get_clean();
}
