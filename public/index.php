<?php
require_once __DIR__ . '/../app/bootstrap.php';
$pageTitle = 'Overview';
$pdo = db();
$total = (int)$pdo->query('SELECT COUNT(*) FROM incidents')->fetchColumn();
$active = (int)$pdo->query("SELECT COUNT(*) FROM incidents WHERE status IN ('Active','Monitoring')")->fetchColumn();
$high = (int)$pdo->query("SELECT COUNT(*) FROM incidents WHERE severity IN ('High','Critical') AND status <> 'Resolved'")->fetchColumn();
$typesCount = (int)$pdo->query('SELECT COUNT(DISTINCT hazard_type) FROM incidents')->fetchColumn();
$latest = $pdo->query('SELECT * FROM incidents ORDER BY occurred_at DESC, id DESC LIMIT 5')->fetchAll();
$byType = $pdo->query('SELECT hazard_type, COUNT(*) AS total FROM incidents GROUP BY hazard_type ORDER BY total DESC')->fetchAll();
require __DIR__ . '/../app/layout/header.php';
?>
<section class="hero">
  <div class="hero-copy">
    <div class="eyebrow"><span class="eyebrow-line"></span> EARTH INTELLIGENCE / 001</div>
    <h1>Understand a changing planet<span class="accent">.</span></h1>
    <p class="hero-text">Explore natural hazards, follow incident updates, and bring scattered event information into one clear monitoring workspace.</p>
    <div class="hero-actions">
      <a class="button button-primary" href="<?= e(base_path('incidents.php')) ?>">Explore incidents <span>↗</span></a>
      <a class="button button-ghost" href="<?= e(base_path('report.php')) ?>">＋ Submit an event</a>
    </div>
    <div class="hero-footnote"><span class="status-dot"></span> Demo records loaded <span class="footnote-divider">/</span> Live feeds not connected</div>
  </div>
  <div class="hero-visual" aria-label="Abstract globe illustration">
    <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
    <div class="globe">
      <div class="globe-grid"></div>
      <div class="continent continent-one"></div><div class="continent continent-two"></div><div class="continent continent-three"></div>
      <span class="map-point point-one"></span><span class="map-point point-two"></span><span class="map-point point-three"></span>
    </div>
    <div class="visual-tag tag-top"><span class="tiny-pulse"></span> GLOBAL VIEW <b>DEMO</b></div>
    <div class="visual-tag tag-bottom"><span class="tag-coord">38° 13' N</span><span>Sample event marker</span></div>
    <div class="visual-caption">MONITOR · CONTEXTUALIZE · PREPARE</div>
  </div>
</section>

<section class="section-block">
  <div class="section-heading">
    <div><div class="eyebrow">SITUATION AT A GLANCE</div><h2>Monitoring overview</h2></div>
    <span class="updated-label"><span class="status-dot"></span> Sample dataset</span>
  </div>
  <div class="stats-grid">
    <article class="stat-card"><div class="stat-top"><span>Total incidents</span><span class="stat-icon">⌁</span></div><div class="stat-number"><?= $total ?></div><div class="stat-foot">Records in this demo database</div></article>
    <article class="stat-card"><div class="stat-top"><span>Active / monitoring</span><span class="stat-icon icon-amber">◉</span></div><div class="stat-number"><?= $active ?></div><div class="stat-foot"><span class="mini-dot amber"></span> Not a live status feed</div></article>
    <article class="stat-card"><div class="stat-top"><span>High severity</span><span class="stat-icon icon-red">↗</span></div><div class="stat-number"><?= $high ?></div><div class="stat-foot"><span class="mini-dot red"></span> Sample severity labels</div></article>
    <article class="stat-card"><div class="stat-top"><span>Hazard categories</span><span class="stat-icon icon-blue">◈</span></div><div class="stat-number"><?= $typesCount ?></div><div class="stat-foot">Across the demo records</div></article>
  </div>
</section>

<section class="dashboard-grid section-block">
  <div class="panel latest-panel">
    <div class="panel-heading"><div><div class="eyebrow">RECENT ACTIVITY</div><h2>Latest incidents</h2></div><a class="text-link" href="<?= e(base_path('incidents.php')) ?>">View all <span>→</span></a></div>
    <div class="incident-list">
    <?php foreach ($latest as $item): ?>
      <a class="incident-row" href="<?= e(base_path('incident.php?id=' . (int)$item['id'])) ?>">
        <span class="hazard-icon <?= e(strtolower(str_replace(' ', '-', $item['hazard_type']))) ?>"><?= e(hazard_symbol($item['hazard_type'])) ?></span>
        <span class="incident-main"><strong><?= e($item['title']) ?></strong><small><?= e($item['location']) ?> · <?= e(format_date($item['occurred_at'])) ?></small></span>
        <span class="severity-badge <?= e(incident_badge_class($item['severity'])) ?>"><?= e($item['severity']) ?></span>
        <span class="row-arrow">↗</span>
      </a>
    <?php endforeach; ?>
    </div>
  </div>
  <div class="panel category-panel">
    <div class="panel-heading"><div><div class="eyebrow">EVENT TAXONOMY</div><h2>Hazard mix</h2></div></div>
    <div class="category-list">
      <?php $maxCount = max(1, ...array_map(fn($r) => (int)$r['total'], $byType)); foreach ($byType as $row): ?>
      <div class="category-item">
        <div class="category-label"><span><?= e($row['hazard_type']) ?></span><b><?= (int)$row['total'] ?></b></div>
        <div class="bar-track"><span style="width: <?= (int)round(((int)$row['total'] / $maxCount) * 100) ?>%"></span></div>
      </div>
      <?php endforeach; ?>
    </div>
    <a class="button button-outline full-button" href="<?= e(base_path('incidents.php')) ?>">Browse all categories <span>→</span></a>
  </div>
</section>

<section class="callout section-block">
  <div class="callout-icon">⌖</div><div><div class="eyebrow">BUILD ON THIS STARTER</div><h2>Ready for real-world data integration</h2><p>Connect trusted public data feeds, add an interactive map, and build your own incident verification workflow.</p></div>
  <a class="button button-primary" href="<?= e(base_path('incidents.php')) ?>">Explore the portal <span>↗</span></a>
</section>
<?php require __DIR__ . '/../app/layout/footer.php'; ?>

<?php
function hazard_symbol(string $type): string {
    return match ($type) {
        'Earthquake' => '⌁', 'Flood' => '≈', 'Wildfire' => '♨', 'Cyclone' => '⟲',
        'Volcanic Activity' => '△', 'Landslide' => '⌇', 'Extreme Weather' => 'ϟ', default => '◈'
    };
}
?>
