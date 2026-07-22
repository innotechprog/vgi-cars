<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Generate Invoice';
require __DIR__ . '/_header.php';

$prefillCar = null;
if (isset($_GET['car_id'])) {
    $prefillCar = $carService->find((int) $_GET['car_id']);
}
$selectedCustomerId = (int) ($_GET['customer_id'] ?? 0);
$selectedCustomer = $selectedCustomerId > 0 ? $salesService->findCustomer($selectedCustomerId) : null;
$cars = $carService->listPaginated(1, 250, []);
$customers = $salesService->listCustomerOptions(500);
$msg = $_GET['msg'] ?? '';

$companyName = $settingsService->get('company_name', 'S&B AUTO GROUP PTY LTD') ?? 'S&B AUTO GROUP PTY LTD';
$companyAddress = $settingsService->get('company_address', '25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001') ?? '25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001';
$companyPhone = $settingsService->get('company_phone', '+27 78 979 6523') ?? '+27 78 979 6523';
$selectedSaleBrand = (string) ($_GET['sale_brand'] ?? 'sb_autogroup');
if (!in_array($selectedSaleBrand, ['sb_autogroup', 'vgi_cars'], true)) {
  $selectedSaleBrand = 'sb_autogroup';
}

$saleBrandProfiles = [
  'sb_autogroup' => [
    'label' => 'SB Autogroup',
    'company_name' => $companyName,
    'company_phone' => $companyPhone,
    'company_address' => $companyAddress,
  ],
  'vgi_cars' => [
    'label' => 'VGI Cars',
    'company_name' => $settingsService->get('vgi_company_name', 'VGI Cars') ?? 'VGI Cars',
    'company_phone' => $settingsService->get('vgi_company_phone', $companyPhone) ?? $companyPhone,
    'company_address' => $settingsService->get('vgi_company_address', $companyAddress) ?? $companyAddress,
  ],
];
$selectedBrandProfile = $saleBrandProfiles[$selectedSaleBrand];

function sale_form_customer_lookup_label(array $customer): string
{
  $customerId = (int) ($customer['customer_id'] ?? 0);
  $customerName = trim(($customer['first_names'] ?? '') . ' ' . ($customer['last_name'] ?? ''));
  $parts = [];

  if ($customerName !== '') {
    $parts[] = $customerName;
  }
  if (!empty($customer['email'])) {
    $parts[] = $customer['email'];
  }
  if (!empty($customer['cellphone'])) {
    $parts[] = $customer['cellphone'];
  }

  $suffix = implode(' | ', $parts);

  return '#' . $customerId . ($suffix !== '' ? ' ' . $suffix : '');
}

$selectedCustomerLookupLabel = $selectedCustomer ? sale_form_customer_lookup_label($selectedCustomer) : '';
?>
<div class="pagetitle">
  <h1>Generate Invoice</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item"><a href="sales">Sales</a></li>
      <li class="breadcrumb-item active">Generate Invoice</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-4">
          <?php if ($msg): ?><div class="alert alert-danger"><?= h($msg) ?></div><?php endif; ?>
          <form method="post" action="actions/process_sale.php" id="saleForm">
            <input type="hidden" name="customer_id" id="customerIdField" value="<?= $selectedCustomerId > 0 ? (int) $selectedCustomerId : '' ?>">
            <div class="row g-3 mb-4">
              <div class="col-md-4">
                <label class="form-label" for="saleBrand">Company Profile</label>
                <select name="sale_brand" id="saleBrand" class="form-select">
                  <?php foreach ($saleBrandProfiles as $brandKey => $profile): ?>
                    <option value="<?= h($brandKey) ?>" data-company-name="<?= h($profile['company_name']) ?>" data-company-phone="<?= h($profile['company_phone']) ?>" data-company-address="<?= h($profile['company_address']) ?>" <?= $selectedSaleBrand === $brandKey ? 'selected' : '' ?>><?= h($profile['label']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4"><label class="form-label">Company Name</label><input id="companyNameDisplay" class="form-control" value="<?= h($selectedBrandProfile['company_name']) ?>" disabled></div>
              <div class="col-md-4"><label class="form-label">Company Phone</label><input id="companyPhoneDisplay" class="form-control" value="<?= h($selectedBrandProfile['company_phone']) ?>" disabled></div>
              <div class="col-md-4"><label class="form-label">Sale Date</label><input type="date" name="sale_date" class="form-control" value="<?= h(date('Y-m-d')) ?>" required></div>
              <div class="col-12"><label class="form-label">Company Address</label><input id="companyAddressDisplay" class="form-control" value="<?= h($selectedBrandProfile['company_address']) ?>" disabled></div>
            </div>

            <h5 class="card-title">Customer Details</h5>
            <div class="row g-3 mb-4">
              <div class="col-12">
                <label class="form-label" for="customerLookup">Search Existing Customer</label>
                <input type="search" id="customerLookup" class="form-control" list="customerLookupList" placeholder="Type name, email, ID number, or phone" value="<?= h($selectedCustomerLookupLabel) ?>" autocomplete="off" spellcheck="false">
                <datalist id="customerLookupList">
                  <?php foreach ($customers as $customer): ?>
                    <option value="<?= h(sale_form_customer_lookup_label($customer)) ?>"
                      data-customer-id="<?= (int) $customer['customer_id'] ?>"
                      data-first-names="<?= h($customer['first_names'] ?? '') ?>"
                      data-last-name="<?= h($customer['last_name'] ?? '') ?>"
                      data-id-number="<?= h($customer['id_number'] ?? '') ?>"
                      data-email="<?= h($customer['email'] ?? '') ?>"
                      data-cellphone="<?= h($customer['cellphone'] ?? '') ?>"
                      data-address-line1="<?= h($customer['address_line1'] ?? '') ?>"
                      data-address-line2="<?= h($customer['address_line2'] ?? '') ?>"
                      data-city="<?= h($customer['city'] ?? '') ?>"
                      data-state-region="<?= h($customer['state_region'] ?? '') ?>"
                      data-postal-code="<?= h($customer['postal_code'] ?? '') ?>"
                      data-country="<?= h($customer['country'] ?? '') ?>"></option>
                  <?php endforeach; ?>
                </datalist>
                <small class="text-muted">Choose a suggestion to auto-fill the invoice form, or leave it as manual entry.</small>
              </div>
              <div class="col-md-4"><label class="form-label">First Names</label><input name="first_names" class="form-control" required value="<?= h($selectedCustomer['first_names'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">Last Name</label><input name="last_name" class="form-control" value="<?= h($selectedCustomer['last_name'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">ID Number</label><input name="id_number" class="form-control" value="<?= h($selectedCustomer['id_number'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">Email</label><input name="email" type="email" class="form-control" value="<?= h($selectedCustomer['email'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">Cellphone</label><input name="cellphone" class="form-control" value="<?= h($selectedCustomer['cellphone'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">Country</label><input name="country" class="form-control" value="<?= h($selectedCustomer['country'] ?? 'South Africa') ?>"></div>
              <div class="col-md-6"><label class="form-label">Address Line 1</label><input name="address_line1" class="form-control" value="<?= h($selectedCustomer['address_line1'] ?? '') ?>"></div>
              <div class="col-md-6"><label class="form-label">Address Line 2</label><input name="address_line2" class="form-control" value="<?= h($selectedCustomer['address_line2'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">City</label><input name="city" class="form-control" value="<?= h($selectedCustomer['city'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">Province/Region</label><input name="state_region" class="form-control" value="<?= h($selectedCustomer['state_region'] ?? '') ?>"></div>
              <div class="col-md-4"><label class="form-label">Postal Code</label><input name="postal_code" class="form-control" value="<?= h($selectedCustomer['postal_code'] ?? '') ?>"></div>
            </div>

            <h5 class="card-title">Purchased Vehicle Records</h5>
            <div id="saleItemsWrap">
              <div class="border rounded p-3 mb-3 sale-item-row">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Car From Inventory</label>
                    <select name="line_car_id[]" class="form-select sale-car-select">
                      <option value="">Manual Entry</option>
                      <?php foreach ($cars as $car): ?>
                        <?php $label = trim(($car['year'] ?? '') . ' ' . ($car['make'] ?? '') . ' ' . ($car['model'] ?? '')); ?>
                        <option value="<?= (int) $car['car_id'] ?>"
                          data-make="<?= h($car['make'] ?? '') ?>"
                          data-model="<?= h($car['model'] ?? '') ?>"
                          data-year="<?= h((string) ($car['year'] ?? '')) ?>"
                          data-vin="<?= h($car['vin'] ?? '') ?>"
                          data-color="<?= h($car['color'] ?? '') ?>"
                          data-price="<?= h((string) ($car['price'] ?? '0')) ?>"
                          <?= $prefillCar && (int) $prefillCar['car_id'] === (int) $car['car_id'] ? 'selected' : '' ?>><?= h($label) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4"><label class="form-label">Registration No</label><input name="line_registration_number[]" class="form-control"></div>
                  <div class="col-md-4"><label class="form-label">Description of the Car</label><input name="line_description[]" class="form-control" value="<?= $prefillCar ? h(trim(($prefillCar['year'] ?? '') . ' ' . ($prefillCar['make'] ?? '') . ' ' . ($prefillCar['model'] ?? ''))) : '' ?>"></div>
                  <div class="col-md-3"><label class="form-label">Make</label><input name="line_make[]" class="form-control" value="<?= h($prefillCar['make'] ?? '') ?>"></div>
                  <div class="col-md-3"><label class="form-label">Model</label><input name="line_model[]" class="form-control" value="<?= h($prefillCar['model'] ?? '') ?>"></div>
                  <div class="col-md-2"><label class="form-label">Year</label><input name="line_year[]" class="form-control" value="<?= h((string) ($prefillCar['year'] ?? '')) ?>"></div>
                  <div class="col-md-4"><label class="form-label">VIN Number</label><input name="line_vin[]" class="form-control" value="<?= h($prefillCar['vin'] ?? '') ?>"></div>
                  <div class="col-md-4"><label class="form-label">Engine No</label><input name="line_engine_number[]" class="form-control"></div>
                  <div class="col-md-2"><label class="form-label">Color</label><input name="line_color[]" class="form-control" value="<?= h($prefillCar['color'] ?? '') ?>"></div>
                  <div class="col-md-2"><label class="form-label">Qty</label><input type="number" min="1" name="line_quantity[]" class="form-control line-qty" value="1"></div>
                  <div class="col-md-3"><label class="form-label">Selling Price</label><input type="number" step="0.01" min="0" name="line_unit_price[]" class="form-control line-price" value="<?= h((string) ($prefillCar['price'] ?? '0')) ?>"></div>
                </div>
              </div>
            </div>
            <div class="mb-4"><button class="btn btn-outline-secondary" type="button" id="addSaleItemBtn">Add Another Vehicle</button></div>

            <h5 class="card-title">Totals</h5>
            <div class="row g-3 mb-4">
              <div class="col-md-3"><label class="form-label">Payment Method</label><select name="payment_method" class="form-select"><option value="cash">Cash</option><option value="eft">EFT</option><option value="finance">Finance</option><option value="card">Card</option></select></div>
              <div class="col-md-3"><label class="form-label">Deposit</label><input type="number" step="0.01" min="0" name="deposit_amount" id="depositAmount" class="form-control" value="0"></div>
              <div class="col-md-3"><label class="form-label">Admin Fee</label><input type="number" step="0.01" min="0" name="admin_fee_amount" id="adminFeeAmount" class="form-control" value="0"></div>
              <div class="col-md-3"><label class="form-label">Outstanding</label><input type="number" step="0.01" min="0" name="outstanding_amount" class="form-control" value="0"></div>
              <div class="col-md-3"><label class="form-label">Subtotal</label><input type="number" step="0.01" min="0" id="subtotalAmount" class="form-control" value="0" readonly></div>
              <div class="col-md-3"><label class="form-label">Total</label><input type="number" step="0.01" min="0" name="total_amount" id="totalAmount" class="form-control" value="0" readonly></div>
              <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="completed">Completed</option><option value="pending">Pending</option></select></div>
              <div class="col-md-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">Generate Invoice</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('saleForm');
  const wrap = document.getElementById('saleItemsWrap');
  const addButton = document.getElementById('addSaleItemBtn');
  const subtotalField = document.getElementById('subtotalAmount');
  const totalField = document.getElementById('totalAmount');
  const depositField = document.getElementById('depositAmount');
  const adminFeeField = document.getElementById('adminFeeAmount');
  const saleBrandField = document.getElementById('saleBrand');
  const companyNameDisplay = document.getElementById('companyNameDisplay');
  const companyPhoneDisplay = document.getElementById('companyPhoneDisplay');
  const companyAddressDisplay = document.getElementById('companyAddressDisplay');
  const customerLookup = document.getElementById('customerLookup');
  const customerLookupList = document.getElementById('customerLookupList');
  const customerIdField = document.getElementById('customerIdField');
  const customerLookupOptions = customerLookupList ? Array.from(customerLookupList.options) : [];

  function syncCompanyProfile() {
    if (!saleBrandField) {
      return;
    }

    const option = saleBrandField.options[saleBrandField.selectedIndex];
    if (!option) {
      return;
    }

    if (companyNameDisplay) {
      companyNameDisplay.value = option.dataset.companyName || '';
    }
    if (companyPhoneDisplay) {
      companyPhoneDisplay.value = option.dataset.companyPhone || '';
    }
    if (companyAddressDisplay) {
      companyAddressDisplay.value = option.dataset.companyAddress || '';
    }
  }

  if (saleBrandField) {
    saleBrandField.addEventListener('change', syncCompanyProfile);
    syncCompanyProfile();
  }

  const customerFieldMap = {
    'first_names': form.querySelector('[name="first_names"]'),
    'last_name': form.querySelector('[name="last_name"]'),
    'id_number': form.querySelector('[name="id_number"]'),
    'email': form.querySelector('[name="email"]'),
    'cellphone': form.querySelector('[name="cellphone"]'),
    'country': form.querySelector('[name="country"]'),
    'address_line1': form.querySelector('[name="address_line1"]'),
    'address_line2': form.querySelector('[name="address_line2"]'),
    'city': form.querySelector('[name="city"]'),
    'state_region': form.querySelector('[name="state_region"]'),
    'postal_code': form.querySelector('[name="postal_code"]'),
  };

  function clearCustomerFields(keepLookupValue) {
    if (customerIdField) {
      customerIdField.value = '';
    }

    customerFieldMap['first_names'].value = '';
    customerFieldMap['last_name'].value = '';
    customerFieldMap['id_number'].value = '';
    customerFieldMap['email'].value = '';
    customerFieldMap['cellphone'].value = '';
    customerFieldMap['country'].value = 'South Africa';
    customerFieldMap['address_line1'].value = '';
    customerFieldMap['address_line2'].value = '';
    customerFieldMap['city'].value = '';
    customerFieldMap['state_region'].value = '';
    customerFieldMap['postal_code'].value = '';

    if (!keepLookupValue && customerLookup) {
      customerLookup.value = '';
    }
  }

  function populateCustomerFields(option) {
    if (!option) {
      clearCustomerFields(true);
      return;
    }

    if (customerIdField) {
      customerIdField.value = option.dataset.customerId || '';
    }

    customerFieldMap['first_names'].value = option.dataset.firstNames || '';
    customerFieldMap['last_name'].value = option.dataset.lastName || '';
    customerFieldMap['id_number'].value = option.dataset.idNumber || '';
    customerFieldMap['email'].value = option.dataset.email || '';
    customerFieldMap['cellphone'].value = option.dataset.cellphone || '';
    customerFieldMap['country'].value = option.dataset.country || 'South Africa';
    customerFieldMap['address_line1'].value = option.dataset.addressLine1 || '';
    customerFieldMap['address_line2'].value = option.dataset.addressLine2 || '';
    customerFieldMap['city'].value = option.dataset.city || '';
    customerFieldMap['state_region'].value = option.dataset.stateRegion || '';
    customerFieldMap['postal_code'].value = option.dataset.postalCode || '';
  }

  function getCustomerOptionByValue(value) {
    if (!value) {
      return null;
    }

    return customerLookupOptions.find(function (option) {
      return option.value === value;
    }) || null;
  }

  function getCustomerOptionById(customerId) {
    if (!customerId) {
      return null;
    }

    return customerLookupOptions.find(function (option) {
      return option.dataset.customerId === String(customerId);
    }) || null;
  }

  if (customerLookup) {
    const initialOption = getCustomerOptionById(customerIdField ? customerIdField.value : '') || getCustomerOptionByValue(customerLookup.value);
    if (initialOption) {
      populateCustomerFields(initialOption);
      customerLookup.value = initialOption.value;
    }

    customerLookup.addEventListener('input', function () {
      if (!customerLookup.value.trim()) {
        clearCustomerFields(true);
        return;
      }

      clearCustomerFields(true);
    });

    customerLookup.addEventListener('change', function () {
      const typedValue = customerLookup.value.trim();
      if (!typedValue) {
        clearCustomerFields();
        return;
      }

      let option = getCustomerOptionByValue(typedValue);
      if (!option) {
        const idMatch = typedValue.match(/^#(\d+)\b/);
        if (idMatch) {
          option = getCustomerOptionById(idMatch[1]);
        }
      }

      if (!option) {
        clearCustomerFields(true);
        return;
      }

      customerLookup.value = option.value;
      populateCustomerFields(option);
    });
  }

  function updateTotals() {
    let subtotal = 0;
    wrap.querySelectorAll('.sale-item-row').forEach(function (row) {
      const qty = Number(row.querySelector('.line-qty')?.value || 0);
      const price = Number(row.querySelector('.line-price')?.value || 0);
      subtotal += qty * price;
    });
    const deposit = Number(depositField.value || 0);
    const adminFee = Number(adminFeeField.value || 0);
    subtotalField.value = subtotal.toFixed(2);
    totalField.value = (subtotal + adminFee - deposit).toFixed(2);
  }

  function bindRow(row) {
    row.querySelectorAll('.line-qty, .line-price').forEach(function (field) {
      field.addEventListener('input', updateTotals);
    });

    const select = row.querySelector('.sale-car-select');
    if (select) {
      select.addEventListener('change', function () {
        const option = select.options[select.selectedIndex];
        if (!option || !option.value) {
          return;
        }
        row.querySelector('input[name="line_make[]"]').value = option.dataset.make || '';
        row.querySelector('input[name="line_model[]"]').value = option.dataset.model || '';
        row.querySelector('input[name="line_year[]"]').value = option.dataset.year || '';
        row.querySelector('input[name="line_vin[]"]').value = option.dataset.vin || '';
        row.querySelector('input[name="line_color[]"]').value = option.dataset.color || '';
        row.querySelector('input[name="line_unit_price[]"]').value = option.dataset.price || '';
        row.querySelector('input[name="line_description[]"]').value = [option.dataset.year, option.dataset.make, option.dataset.model].filter(Boolean).join(' ');
        updateTotals();
      });
    }
  }

  addButton.addEventListener('click', function () {
    const first = wrap.querySelector('.sale-item-row');
    const clone = first.cloneNode(true);
    clone.querySelectorAll('input').forEach(function (input) {
      if (input.classList.contains('line-qty')) {
        input.value = '1';
      } else {
        input.value = '';
      }
    });
    const select = clone.querySelector('.sale-car-select');
    if (select) {
      select.selectedIndex = 0;
    }
    wrap.appendChild(clone);
    bindRow(clone);
    updateTotals();
  });

  [depositField, adminFeeField].forEach(function (field) {
    field.addEventListener('input', updateTotals);
  });

  wrap.querySelectorAll('.sale-item-row').forEach(bindRow);
  updateTotals();
});
</script>

<?php require __DIR__ . '/_footer.php'; ?>