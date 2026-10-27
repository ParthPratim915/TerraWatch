<?php
require_once __DIR__ . '/../app/bootstrap.php';
$pageTitle = 'Incident explorer';
$pdo = db();
$q = trim((string)($_GET['q'] ?? ''));
$type = (string)($_GET['type'] ?? '');
$severity = (string)($_GET['severity'] ?? '');
$status = (string)($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 8;
$where = [];
$params = [];
if ($q !== '') {
    $where[] = '(title LIKE :q OR location LIKE :q OR region LIKE :q OR summary LIKE :q)';
    $params['q'] = '%' . $q . '%';
}
if (in_array($type, hazard_types(), true)) { $where[] = 'hazard_type = :type'; $params['type'] = $type; }
if (in_array($severity, severities(), true)) { $where[] = 'severity = :severity'; $params['severity'] = $severity; }
if (in_array($status, statuses(), true)) { $where[] = 'status = :status'; $params['status'] = $status; }
$clause = $where ? ' WHERE ' . implode(' AND ', $where) : '';
$countStmt = $pdo->prepare('SELECT COUNT(*) FROM incidents' . $clause);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);
$stmt = $pdo->prepare('SELECT * FROM incidents' . $clause . ' ORDER BY occurred_at DESC, id DESC LIMIT :limit OFFSET :offset');
foreach ($params as $key => $value) $stmt->bindValue(':' . $key, $value);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll();
require __DIR__ . '/../app/layout/header.php';
?>
<section class="page-hero compact-hero">
  <div><div class="eyebrow"><span class="eyebrow-line"></span> EVENT DATABASE / 002</div><h1>Incident explorer<span class="accent">.</span></h1><p>Search and filter the sample hazard register. Every record is labelled as demonstration data.</p></div>
  <div class="page-hero-mark">⌕</div>
</section>
<section class="section-block">
  <form class="filter-panel" method="get" action="<?= e(base_path('incidents.php')) ?>">
    <div class="filter-search"><label for="q">Search incidents</label><div class="input-with-icon"><span>⌕</span><input id="q" name="q" value="<?= e($q) ?>" placeholder="Search by event, place, or summary"></div></div>
    <div><label for="type">Hazard type</label><select id="type" name="type"><option value="">All types</option><?php foreach (hazard_types() as $option): ?><option value="<?= e($option) ?>" <?= $type === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select></div>
    <div><label for="severity">Severity</label><select id="severity" name="severity"><option value="">All levels</option><?php foreach (severities() as $option): ?><option value="<?= e($option) ?>" <?= $severity === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select></div>
    <div><label for="status">Status</label><select id="status" name="status"><option value="">All statuses</option><?php foreach (statuses() as $option): ?><option value="<?= e($option) ?>" <?= $status === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select></div>
    <div class="filter-actions"><button class="button button-primary" type="submit">Apply filters</button><a class="reset-link" href="<?= e(base_path('incidents.php')) ?>">Reset</a></div>
  </form>
  <div class="results-heading"><div><h2>Incident register</h2><p><?= $total ?> record<?= $total === 1 ? '' : 's' ?> found</p></div><span class="demo-chip">DEMO DATASET</span></div>
  <?php if (!$items): ?>
    <div class="empty-state"><span>⌕</span><h3>No incidents found</h3><p>Try a different search phrase or clear one of the filters.</p><a class="button button-outline" href="<?= e(base_path('incidents.php')) ?>">Clear filters</a></div>
  <?php else: ?>
  <div class="incident-table-wrap">
    <table class="incident-table"><thead><tr><th>Incident</th><th>Hazard</th><th>Location</th><th>Severity</th><th>Status</th><th>Occurred</th><th></th></tr></thead><tbody>
    <?php foreach ($items as $item): ?>
      <tr>
        <td><a class="table-title" href="<?= e(base_path('incident.php?id=' . (int)$item['id'])) ?>"><?= e($item['title']) ?></a><small>ID #<?= (int)$item['id'] ?></small></td>
        <td><?= e($item['hazard_type']) ?></td><td><?= e($item['location']) ?><small><?= e($item['region'] ?? '') ?></small></td>
        <td><span class="severity-badge <?= e(incident_badge_class($item['severity'])) ?>"><?= e($item['severity']) ?></span></td>
        <td><span class="status-badge <?= e(incident_badge_class($item['status'])) ?>"><?= e($item['status']) ?></span></td>
        <td class="date-cell"><?= e(format_date($item['occurred_at'])) ?></td>
        <td><a class="row-arrow" aria-label="View incident" href="<?= e(base_path('incident.php?id=' . (int)$item['id'])) ?>">↗</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
  <?php if ($pages > 1): ?><nav class="pagination" aria-label="Pagination">
    <?php for ($i = 1; $i <= $pages; $i++): $query = $_GET; $query['page'] = $i; ?>
      <a class="<?= $i === $page ? 'current' : '' ?>" href="<?= e(base_path('incidents.php?' . http_build_query($query))) ?>"><?= $i ?></a>
    <?php endfor; ?>
  </nav><?php endif; ?>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../app/layout/footer.php'; ?>
