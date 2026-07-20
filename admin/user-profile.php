<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'My Profile';
require __DIR__ . '/_header.php';

$userId = $auth->id() ?? 0;
$user = $userService->findById($userId) ?? [];
$msg = $_GET['msg'] ?? '';
?>

<div class="pagetitle">
  <h1>Profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item">Users</li>
      <li class="breadcrumb-item active">Profile</li>
    </ol>
  </nav>
</div>

<?php if ($msg): ?>
  <div class="alert alert-info"><?= h($msg) ?></div>
<?php endif; ?>

<section class="section profile">
  <div class="row">
    <div class="col-xl-4">
      <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
          <img src="assets/img/male_avatar.png" alt="Profile" class="rounded-circle">
          <h2><?= h(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></h2>
          <h3><?= h($user['job'] ?? '') ?></h3>
          <div class="social-links mt-2">
            <a href="<?= h($user['twitter'] ?? '') ?>" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="<?= h($user['facebook'] ?? '') ?>" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="<?= h($user['instagram'] ?? '') ?>" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="<?= h($user['linkedin'] ?? '') ?>" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card">
        <div class="card-body pt-3">
          <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button></li>
          </ul>
          <div class="tab-content pt-2">
            <div class="tab-pane fade show active profile-overview" id="profile-overview">
              <h5 class="card-title">About</h5>
              <p class="small fst-italic"><?= h($user['about'] ?? '') ?></p>
              <h5 class="card-title">Profile Details</h5>
              <div class="row"><div class="col-lg-3 col-md-4 label">Full Name</div><div class="col-lg-9 col-md-8"><?= h(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></div></div>
              <div class="row"><div class="col-lg-3 col-md-4 label">Company</div><div class="col-lg-9 col-md-8"><?= h($user['company'] ?? '') ?></div></div>
              <div class="row"><div class="col-lg-3 col-md-4 label">Job</div><div class="col-lg-9 col-md-8"><?= h($user['job'] ?? '') ?></div></div>
              <div class="row"><div class="col-lg-3 col-md-4 label">Country</div><div class="col-lg-9 col-md-8"><?= h($user['country'] ?? '') ?></div></div>
              <div class="row"><div class="col-lg-3 col-md-4 label">Address</div><div class="col-lg-9 col-md-8"><?= h($user['address'] ?? '') ?></div></div>
              <div class="row"><div class="col-lg-3 col-md-4 label">Phone</div><div class="col-lg-9 col-md-8"><?= h($user['phone_number'] ?? '') ?></div></div>
              <div class="row"><div class="col-lg-3 col-md-4 label">Email</div><div class="col-lg-9 col-md-8"><?= h($user['email'] ?? '') ?></div></div>
            </div>

            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
              <form method="post" action="actions/process_user.php">
                <input type="hidden" name="action" value="profile">
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">First Name</label><div class="col-md-8 col-lg-9"><input name="first_name" type="text" class="form-control" value="<?= h($user['first_name'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Last Name</label><div class="col-md-8 col-lg-9"><input name="last_name" type="text" class="form-control" value="<?= h($user['last_name'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">About</label><div class="col-md-8 col-lg-9"><textarea name="about" class="form-control" style="height: 100px"><?= h($user['about'] ?? '') ?></textarea></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Company</label><div class="col-md-8 col-lg-9"><input name="company" type="text" class="form-control" value="<?= h($user['company'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Job</label><div class="col-md-8 col-lg-9"><input name="job" type="text" class="form-control" value="<?= h($user['job'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Country</label><div class="col-md-8 col-lg-9"><input name="country" type="text" class="form-control" value="<?= h($user['country'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Address</label><div class="col-md-8 col-lg-9"><input name="address" type="text" class="form-control" value="<?= h($user['address'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Phone</label><div class="col-md-8 col-lg-9"><input name="phone_number" type="text" class="form-control" value="<?= h($user['phone_number'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Email</label><div class="col-md-8 col-lg-9"><input name="email" type="email" class="form-control" value="<?= h($user['email'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label><div class="col-md-8 col-lg-9"><input name="twitter" type="text" class="form-control" value="<?= h($user['twitter'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label><div class="col-md-8 col-lg-9"><input name="facebook" type="text" class="form-control" value="<?= h($user['facebook'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label><div class="col-md-8 col-lg-9"><input name="instagram" type="text" class="form-control" value="<?= h($user['instagram'] ?? '') ?>"></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">LinkedIn Profile</label><div class="col-md-8 col-lg-9"><input name="linkedin" type="text" class="form-control" value="<?= h($user['linkedin'] ?? '') ?>"></div></div>
                <div class="text-center"><button type="submit" class="btn btn-primary">Save Changes</button></div>
              </form>
            </div>

            <div class="tab-pane fade pt-3" id="profile-change-password">
              <form method="post" action="actions/process_user.php">
                <input type="hidden" name="action" value="password">
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Current Password</label><div class="col-md-8 col-lg-9"><input name="current_password" type="password" class="form-control" required></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">New Password</label><div class="col-md-8 col-lg-9"><input name="new_password" type="password" class="form-control" required></div></div>
                <div class="row mb-3"><label class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label><div class="col-md-8 col-lg-9"><input name="confirm_password" type="password" class="form-control" required></div></div>
                <div class="text-center"><button type="submit" class="btn btn-primary">Change Password</button></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>