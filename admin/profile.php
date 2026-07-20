<?php
header('Location: user-profile.php');
exit;
?><?php
require_once __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/_header.php';

$userId = $auth->id() ?? 0;
$user = $userService->findById($userId);
$msg = $_GET['msg'] ?? '';
?>
<h2 class="mb-3">My Profile</h2>
<?php if ($msg): ?><div class="alert alert-info"><?= h($msg) ?></div><?php endif; ?>

<form method="post" action="actions/process_user.php" class="row g-3">
  <input type="hidden" name="action" value="profile">
  <div class="col-md-6"><label class="form-label">First Name</label><input name="first_name" class="form-control" value="<?= h($user['first_name'] ?? '') ?>"></div>
  <div class="col-md-6"><label class="form-label">Last Name</label><input name="last_name" class="form-control" value="<?= h($user['last_name'] ?? '') ?>"></div>
  <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control" value="<?= h($user['email'] ?? '') ?>"></div>
  <div class="col-md-6"><label class="form-label">Phone</label><input name="phone_number" class="form-control" value="<?= h($user['phone_number'] ?? '') ?>"></div>
  <div class="col-12"><label class="form-label">About</label><textarea name="about" class="form-control"><?= h($user['about'] ?? '') ?></textarea></div>
  <div class="col-md-6"><label class="form-label">Company</label><input name="company" class="form-control" value="<?= h($user['company'] ?? '') ?>"></div>
  <div class="col-md-6"><label class="form-label">Job</label><input name="job" class="form-control" value="<?= h($user['job'] ?? '') ?>"></div>
  <div class="col-md-6"><label class="form-label">Country</label><input name="country" class="form-control" value="<?= h($user['country'] ?? '') ?>"></div>
  <div class="col-md-6"><label class="form-label">Address</label><input name="address" class="form-control" value="<?= h($user['address'] ?? '') ?>"></div>
  <div class="col-md-4"><label class="form-label">Twitter</label><input name="twitter" class="form-control" value="<?= h($user['twitter'] ?? '') ?>"></div>
  <div class="col-md-4"><label class="form-label">Facebook</label><input name="facebook" class="form-control" value="<?= h($user['facebook'] ?? '') ?>"></div>
  <div class="col-md-4"><label class="form-label">Instagram</label><input name="instagram" class="form-control" value="<?= h($user['instagram'] ?? '') ?>"></div>
  <div class="col-md-6"><label class="form-label">LinkedIn</label><input name="linkedin" class="form-control" value="<?= h($user['linkedin'] ?? '') ?>"></div>
  <div class="col-12"><button class="btn btn-dark" type="submit">Save Profile</button></div>
</form>

<hr class="my-4">
<h5>Change Password</h5>
<form method="post" action="actions/process_user.php" class="row g-3">
  <input type="hidden" name="action" value="password">
  <div class="col-md-4"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control" required></div>
  <div class="col-md-4"><label class="form-label">New Password</label><input type="password" name="new_password" class="form-control" required></div>
  <div class="col-md-4"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
  <div class="col-12"><button class="btn btn-outline-dark" type="submit">Update Password</button></div>
</form>

<?php require __DIR__ . '/_footer.php'; ?>
