<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Generate Invoice';
require __DIR__ . '/_header.php';

$prefillCar = null;
if (isset($_GET['car_id'])) {
    $prefillCar = $carService->find((int) $_GET['car_id']);
}
$cars = $carService->listPaginated(1, 250, []);
$msg = $_GET['msg'] ?? '';

$companyName = $settingsService->get('company_name', 'S&B AUTO GROUP PTY LTD') ?? 'S&B AUTO GROUP PTY LTD';
$companyAddress = $settingsService->get('company_address', '27 Heidelberg Road, Village Main Johannesburg') ?? '27 Heidelberg Road, Village Main Johannesburg';
$companyPhone = $settingsService->get('company_phone', '061 508 3008 | 064 525 8326 | 073 490 3109 | 010 746 3535') ?? '061 508 3008 | 064 525 8326 | 073 490 3109 | 010 746 3535';
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
            <div class="row g-3 mb-4">
              <div class="col-md-4"><label class="form-label">Company Name</label><input class="form-control" value="<?= h($companyName) ?>" disabled></div>
              <div class="col-md-4"><label class="form-label">Company Phone</label><input class="form-control" value="<?= h($companyPhone) ?>" disabled></div>
              <div class="col-md-4"><label class="form-label">Sale Date</label><input type="date" name="sale_date" class="form-control" value="<?= h(date('Y-m-d')) ?>" required></div>
              <div class="col-12"><label class="form-label">Company Address</label><input class="form-control" value="<?= h($companyAddress) ?>" disabled></div>
            </div>

            <h5 class="card-title">Customer Details</h5>
            <div class="row g-3 mb-4">
              <div class="col-md-4"><label class="form-label">First Names</label><input name="first_names" class="form-control" required></div>
              <div class="col-md-4"><label class="form-label">Last Name</label><input name="last_name" class="form-control"></div>
              <div class="col-md-4"><label class="form-label">ID Number</label><input name="id_number" class="form-control"></div>
              <div class="col-md-4"><label class="form-label">Email</label><input name="email" type="email" class="form-control"></div>
              <div class="col-md-4"><label class="form-label">Cellphone</label><input name="cellphone" class="form-control"></div>
              <div class="col-md-4"><label class="form-label">Country</label><input name="country" class="form-control" value="South Africa"></div>
              <div class="col-md-6"><label class="form-label">Address Line 1</label><input name="address_line1" class="form-control"></div>
              <div class="col-md-6"><label class="form-label">Address Line 2</label><input name="address_line2" class="form-control"></div>
              <div class="col-md-4"><label class="form-label">City</label><input name="city" class="form-control"></div>
              <div class="col-md-4"><label class="form-label">Province/Region</label><input name="state_region" class="form-control"></div>
              <div class="col-md-4"><label class="form-label">Postal Code</label><input name="postal_code" class="form-control"></div>
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