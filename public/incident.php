<?php
require_once __DIR__ . '/../app/bootstrap.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(404); $item = null; }
else {
    $stmt = db()->prepare('SELECT * FROM incidents WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch();
    if (!$item) http_response_code(404);
}
$pageTitle = $item ? $item['title'] : 'Incident not found';
require __DIR__ . '/../app/layout/header.php';
?>
<?php if (!$item): ?>
<section class="empty-state section-block"><span>⌕</span><h1>Incident not found</h1><p>This record may have been removed or the URL may be incorrect.</p><a class="button button-primary" href="<?= e(base_path('incidents.php')) ?>">Return to incidents</a></section>
<?php else: ?>
<section class="page-hero compact-hero detail-hero">
  <div><div class="eyebrow"><span class="eyebrow-line"></span> INCIDENT RECORD / #<?= (int)$item['id'] ?></div><h1><?= e($item['title']) ?><span class="accent">.</span></h1><p><?= e($item['location']) ?><?= $item['region'] ? ' · ' . e($item['region']) : '' ?></p></div>
  <span class="severity-badge large <?= e(incident_badge_class($item['severity'])) ?>"><?= e($item['severity']) ?> severity</span>
</section>
<section class="detail-layout section-block">
  <article class="panel detail-main">
    <div class="panel-heading"><div><div class="eyebrow">EVENT OVERVIEW</div><h2>Incident summary</h2></div><span class="demo-chip">SAMPLE RECORD</span></div>
    <p class="detail-summary"><?= nl2br(e($item['summary'])) ?></p>
    <div class="detail-callout"><span>ⓘ</span><p>This is a demonstration record. Verify event details with the cited official source before relying on or sharing this information.</p></div>
    <div class="detail-section"><div class="eyebrow">SOURCE INFORMATION</div><h3><?= e($item['source_name'] ?: 'Source not specified') ?></h3>
      <?php if (!empty($item['source_url']) && filter_var($item['source_url'], FILTER_VALIDATE_URL) && in_array(parse_url($item['source_url'], PHP_URL_SCHEME), ['http','https'], true)): ?>
      <a class="text-link" href="<?= e($item['source_url']) ?>" target="_blank" rel="noopener noreferrer">Open source <span>↗</span></a>
      <?php else: ?><p class="muted">No external source connected to this sample record.</p><?php endif; ?>
    </div>
  </article>
  <aside class="panel metadata-panel"><div class="eyebrow">RECORD METADATA</div><h2>Event details</h2>
    <div class="metadata-row"><span>Hazard type</span><strong><?= e($item['hazard_type']) ?></strong></div>
    <div class="metadata-row"><span>Status</span><strong><span class="status-badge <?= e(incident_badge_class($item['status'])) ?>"><?= e($item['status']) ?></span></strong></div>
    <div class="metadata-row"><span>Occurred</span><strong><?= e(format_date($item['occurred_at'])) ?></strong></div>
    <div class="metadata-row"><span>Magnitude / value</span><strong><?= $item['magnitude'] !== null ? e(number_format((float)$item['magnitude'], 1)) : 'Not applicable' ?></strong></div>
    <div class="metadata-row"><span>Latitude</span><strong><?= $item['latitude'] !== null ? e($item['latitude']) : 'Not available' ?></strong></div>
    <div class="metadata-row"><span>Longitude</span><strong><?= $item['longitude'] !== null ? e($item['longitude']) : 'Not available' ?></strong></div>
    <div class="metadata-row"><span>Record created</span><strong><?= e(format_date($item['created_at'])) ?></strong></div>
    <a class="button button-outline full-button" href="<?= e(base_path('incidents.php')) ?>">← Back to explorer</a>
  </aside>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../app/layout/footer.php'; ?>
