<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Settings';
require __DIR__ . '/_header.php';

$msg = $_GET['msg'] ?? '';
$settings = [];
foreach ($settingsService->all() as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<div class="pagetitle">
  <h1>Settings</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item active">Settings</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-4">
          <?php if ($msg): ?><div class="alert alert-info"><?= h($msg) ?></div><?php endif; ?>

          <form method="post" action="actions/process_settings.php" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Company Name</label>
              <input name="company_name" class="form-control" value="<?= h($settings['company_name'] ?? 'S&B AUTO GROUP PTY LTD') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Company Registration Number</label>
              <input name="company_registration_number" class="form-control" value="<?= h($settings['company_registration_number'] ?? '240688030015') ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Company Address</label>
              <input name="company_address" class="form-control" value="<?= h($settings['company_address'] ?? '25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Company Phone</label>
              <input name="company_phone" class="form-control" value="<?= h($settings['company_phone'] ?? '+27 78 979 6523') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Site Contact Email</label>
              <input name="site_contact_email" type="email" class="form-control" value="<?= h($settings['site_contact_email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Site Phone</label>
              <input name="site_phone" class="form-control" value="<?= h($settings['site_phone'] ?? '+27 78 979 6523') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Facebook URL</label>
              <input name="social_facebook" type="url" class="form-control" value="<?= h($settings['social_facebook'] ?? '') ?>" placeholder="https://www.facebook.com/yourpage">
            </div>
            <div class="col-md-6">
              <label class="form-label">Instagram URL</label>
              <input name="social_instagram" type="url" class="form-control" value="<?= h($settings['social_instagram'] ?? '') ?>" placeholder="https://www.instagram.com/yourprofile">
            </div>
            <div class="col-md-6">
              <label class="form-label">LinkedIn URL</label>
              <input name="social_linkedin" type="url" class="form-control" value="<?= h($settings['social_linkedin'] ?? '') ?>" placeholder="https://www.linkedin.com/company/yourcompany">
            </div>
            <div class="col-md-6">
              <label class="form-label">WhatsApp URL (Optional)</label>
              <input name="social_whatsapp" type="url" class="form-control" value="<?= h($settings['social_whatsapp'] ?? '') ?>" placeholder="https://wa.me/27789796523">
            </div>
            <div class="col-md-6">
              <label class="form-label">SMTP Host</label>
              <input name="smtp_host" class="form-control" value="<?= h($settings['smtp_host'] ?? '') ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label">SMTP Port</label>
              <input name="smtp_port" class="form-control" value="<?= h($settings['smtp_port'] ?? '587') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">SMTP Username</label>
              <input name="smtp_username" class="form-control" value="<?= h($settings['smtp_username'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">SMTP Password</label>
              <input name="smtp_password" type="password" class="form-control" value="<?= h($settings['smtp_password'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">From Email</label>
              <input name="smtp_from_email" type="email" class="form-control" value="<?= h($settings['smtp_from_email'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">From Name</label>
              <input name="smtp_from_name" class="form-control" value="<?= h($settings['smtp_from_name'] ?? 'VGI Cars') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Enable Alerts</label>
              <select name="mail_enabled" class="form-select">
                <option value="0" <?= (($settings['mail_enabled'] ?? '0') === '0') ? 'selected' : '' ?>>No</option>
                <option value="1" <?= (($settings['mail_enabled'] ?? '0') === '1') ? 'selected' : '' ?>>Yes</option>
              </select>
            </div>
            <div class="col-12 text-center">
              <button class="btn btn-primary" type="submit">Save Settings</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
