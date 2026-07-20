<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Subscribers';
require __DIR__ . '/_header.php';

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$q = trim($_GET['q'] ?? '');
$active = ($_GET['active'] ?? '') === '' ? '' : (string) (int) $_GET['active'];

$filters = ['q' => $q, 'active' => $active];
$total = $subscriberService->count($filters);
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}

$subs = $subscriberService->listPaginated($page, $perPage, $filters);
$msg = $_GET['msg'] ?? '';
?>
<div class="pagetitle">
  <h1>Subscribers</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item active">Subscribers</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <?php if ($msg): ?><div class="alert alert-info mt-3"><?= h($msg) ?></div><?php endif; ?>

          <form method="get" class="row g-2 my-3">
            <div class="col-md-4">
              <input type="text" name="q" class="form-control" placeholder="Search name/email/make" value="<?= h($q) ?>">
            </div>
            <div class="col-md-3">
              <select name="active" class="form-select">
                <option value="" <?= $active === '' ? 'selected' : '' ?>>All Status</option>
                <option value="1" <?= $active === '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $active === '0' ? 'selected' : '' ?>>Inactive</option>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
            </div>
          </form>

          <table class="table datatable align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Preferred Make</th>
                <th>Price Range</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($subs as $sub): ?>
                <tr>
                  <td><?= h($sub['name']) ?></td>
                  <td><?= h($sub['email']) ?></td>
                  <td><?= h($sub['preferred_make']) ?></td>
                  <td><?= h($sub['price_range']) ?></td>
                  <td>
                    <span class="badge bg-<?= ((int) $sub['is_active'] === 1) ? 'success' : 'secondary' ?>">
                      <?= ((int) $sub['is_active'] === 1) ? 'Active' : 'Inactive' ?>
                    </span>
                  </td>
                  <td>
                    <a class="btn btn-sm btn-outline-warning" href="actions/subscriber_action.php?action=toggle&id=<?= (int) $sub['id'] ?>">Toggle</a>
                    <a class="btn btn-sm btn-outline-danger" href="actions/subscriber_action.php?action=delete&id=<?= (int) $sub['id'] ?>" onclick="return confirm('Delete subscriber?')">Delete</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
