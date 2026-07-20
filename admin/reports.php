<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'S&B Auto Group | Reports';
require __DIR__ . '/_header.php';

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;
$actionFilter = trim($_GET['action'] ?? '');
$entityFilter = trim($_GET['entity'] ?? '');

$filters = ['action' => $actionFilter, 'entity' => $entityFilter];
$totalLogs = $auditLogService->count($filters);
$totalPages = max(1, (int) ceil($totalLogs / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}

$logs = $auditLogService->listPaginated($page, $perPage, $filters);
$totalCars = $carService->count();
$totalSubs = $subscriberService->count();
?>
<div class="pagetitle">
  <h1>Reports</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
      <li class="breadcrumb-item active">Reports</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card info-card"><div class="card-body"><h5 class="card-title">Total Cars</h5><h6><?= (int) $totalCars ?></h6></div></div></div>
    <div class="col-md-4"><div class="card info-card"><div class="card-body"><h5 class="card-title">Total Subscribers</h5><h6><?= (int) $totalSubs ?></h6></div></div></div>
    <div class="col-md-4"><div class="card info-card"><div class="card-body"><h5 class="card-title">Audit Events</h5><h6><?= (int) $totalLogs ?></h6></div></div></div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <form method="get" class="row g-2 my-3">
            <div class="col-md-3">
              <input name="action" class="form-control" placeholder="Action (e.g. car_created)" value="<?= h($actionFilter) ?>">
            </div>
            <div class="col-md-3">
              <input name="entity" class="form-control" placeholder="Entity (e.g. car)" value="<?= h($entityFilter) ?>">
            </div>
            <div class="col-md-2">
              <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
            </div>
          </form>

          <table class="table table-sm datatable align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>When</th>
                <th>User</th>
                <th>Action</th>
                <th>Entity</th>
                <th>Entity ID</th>
                <th>Details</th>
                <th>IP</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($logs as $log): ?>
                <tr>
                  <td><?= (int) $log['id'] ?></td>
                  <td><?= h($log['created_at']) ?></td>
                  <td><?= h($log['username'] ?? 'system') ?></td>
                  <td><code><?= h($log['action']) ?></code></td>
                  <td><?= h($log['entity'] ?? '') ?></td>
                  <td><?= h($log['entity_id'] ?? '') ?></td>
                  <td><?= h($log['details'] ?? '') ?></td>
                  <td><?= h($log['ip_address'] ?? '') ?></td>
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
