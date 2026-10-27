<?php
require_once __DIR__ . '/../app/bootstrap.php';
$pageTitle = 'Report an event';
$errors = [];
$success = isset($_GET['submitted']) && $_GET['submitted'] === '1';
$values = ['title'=>'','hazard_type'=>'Earthquake','severity'=>'Moderate','status'=>'Unverified','location'=>'','region'=>'','latitude'=>'','longitude'=>'','magnitude'=>'','occurred_at'=>'','summary'=>'','source_name'=>'','source_url'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($values as $key => $default) {
        if (isset($_POST[$key]) && is_string($_POST[$key])) $values[$key] = trim($_POST[$key]);
    }
    if ($values['title'] === '' || mb_strlen($values['title']) > 180) $errors[] = 'Enter a title up to 180 characters.';
    if (!in_array($values['hazard_type'], hazard_types(), true)) $errors[] = 'Choose a valid hazard type.';
    if (!in_array($values['severity'], severities(), true)) $errors[] = 'Choose a valid severity.';
    if (!in_array($values['status'], statuses(), true)) $errors[] = 'Choose a valid status.';
    if ($values['location'] === '' || mb_strlen($values['location']) > 180) $errors[] = 'Enter a location up to 180 characters.';
    if ($values['summary'] === '' || mb_strlen($values['summary']) < 20) $errors[] = 'Add a summary of at least 20 characters.';
    foreach (['latitude'=>[-90,90], 'longitude'=>[-180,180], 'magnitude'=>[0,99]] as $key => [$min,$max]) {
        if ($values[$key] !== '' && (!is_numeric($values[$key]) || (float)$values[$key] < $min || (float)$values[$key] > $max)) $errors[] = ucfirst($key) . ' is outside the accepted range.';
    }
    if ($values['source_url'] !== '' && (!filter_var($values['source_url'], FILTER_VALIDATE_URL) || !in_array(parse_url($values['source_url'], PHP_URL_SCHEME), ['http','https'], true))) $errors[] = 'Source URL must be a valid http or https URL.';
    $date = $values['occurred_at'] !== '' ? str_replace('T', ' ', $values['occurred_at']) : date('Y-m-d H:i:s');
    if ($values['occurred_at'] !== '' && strtotime($values['occurred_at']) === false) $errors[] = 'Enter a valid occurrence date and time.';
    if (!$errors) {
        $sql = 'INSERT INTO incidents (title,hazard_type,severity,status,location,region,latitude,longitude,magnitude,occurred_at,summary,source_name,source_url,is_demo) VALUES (:title,:hazard_type,:severity,:status,:location,:region,:latitude,:longitude,:magnitude,:occurred_at,:summary,:source_name,:source_url,1)';
        $stmt = db()->prepare($sql);
        $stmt->execute([
            'title'=>$values['title'], 'hazard_type'=>$values['hazard_type'], 'severity'=>$values['severity'],
            'status'=>$values['status'], 'location'=>$values['location'], 'region'=>$values['region'] ?: null,
            'latitude'=>$values['latitude'] !== '' ? $values['latitude'] : null,
            'longitude'=>$values['longitude'] !== '' ? $values['longitude'] : null,
            'magnitude'=>$values['magnitude'] !== '' ? $values['magnitude'] : null,
            'occurred_at'=>date('Y-m-d H:i:s', strtotime($date)), 'summary'=>$values['summary'],
            'source_name'=>$values['source_name'] ?: 'Community submission — unverified',
            'source_url'=>$values['source_url'] ?: null,
        ]);
        header('Location: ' . base_path('report.php?submitted=1'));
        exit;
    }
}
require __DIR__ . '/../app/layout/header.php';
?>
<section class="page-hero compact-hero">
  <div><div class="eyebrow"><span class="eyebrow-line"></span> COMMUNITY INPUT / 003</div><h1>Report an event<span class="accent">.</span></h1><p>Submit an incident record for this demo database. Community submissions are not automatically verified.</p></div>
  <div class="page-hero-mark">＋</div>
</section>
<section class="form-layout section-block">
  <div class="form-intro panel"><div class="eyebrow">BEFORE YOU SUBMIT</div><h2>Good data starts with context.</h2><p>Provide a clear location, an approximate time, and a concise summary. If you include a source URL, use an official or reputable source where possible.</p>
    <div class="detail-callout warning-callout"><span>!</span><p>This starter has no moderation queue. Submitted records are stored as unverified/demo entries and should not be treated as confirmed alerts.</p></div>
    <div class="form-tip"><span>01</span><div><strong>Be specific</strong><p>Use the nearest known town, district, or geographic area.</p></div></div>
    <div class="form-tip"><span>02</span><div><strong>Share the source</strong><p>Prefer official agencies or reputable scientific organizations.</p></div></div>
    <div class="form-tip"><span>03</span><div><strong>Never report emergencies here</strong><p>Contact your local emergency services for immediate danger.</p></div></div>
  </div>
  <div class="panel report-form-panel">
    <?php if ($success): ?><div class="success-banner"><span>✓</span><div><strong>Demo record submitted</strong><p>Your record was added to the incident register. It is not independently verified.</p></div></div><?php endif; ?>
    <?php if ($errors): ?><div class="error-banner"><strong>Please review the following:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" action="<?= e(base_path('report.php')) ?>" class="stacked-form">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <div class="form-section-title"><span>01</span><div><h2>Event information</h2><p>Required fields are marked with *</p></div></div>
      <label for="title">Incident title <span>*</span><input id="title" name="title" maxlength="180" required value="<?= e($values['title']) ?>" placeholder="e.g. River flooding reported near district"></label>
      <div class="form-two-col">
        <label for="hazard_type">Hazard type <span>*</span><select id="hazard_type" name="hazard_type" required><?php foreach (hazard_types() as $option): ?><option <?= $values['hazard_type'] === $option ? 'selected' : '' ?> value="<?= e($option) ?>"><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label for="severity">Severity <span>*</span><select id="severity" name="severity" required><?php foreach (severities() as $option): ?><option <?= $values['severity'] === $option ? 'selected' : '' ?> value="<?= e($option) ?>"><?= e($option) ?></option><?php endforeach; ?></select></label>
      </div>
      <div class="form-two-col">
        <label for="status">Initial status<select id="status" name="status"><?php foreach (statuses() as $option): ?><option <?= $values['status'] === $option ? 'selected' : '' ?> value="<?= e($option) ?>"><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label for="occurred_at">Date and time<input id="occurred_at" type="datetime-local" name="occurred_at" value="<?= e($values['occurred_at']) ?>"></label>
      </div>
      <div class="form-section-title spaced"><span>02</span><div><h2>Location & measurements</h2><p>Coordinates and magnitude are optional.</p></div></div>
      <label for="location">Location <span>*</span><input id="location" name="location" maxlength="180" required value="<?= e($values['location']) ?>" placeholder="Town, district, body of water, or region"></label>
      <label for="region">Region / country<input id="region" name="region" maxlength="120" value="<?= e($values['region']) ?>" placeholder="e.g. South Asia"></label>
      <div class="form-three-col">
        <label for="latitude">Latitude<input id="latitude" name="latitude" type="number" step="any" min="-90" max="90" value="<?= e($values['latitude']) ?>" placeholder="e.g. 25.59"></label>
        <label for="longitude">Longitude<input id="longitude" name="longitude" type="number" step="any" min="-180" max="180" value="<?= e($values['longitude']) ?>" placeholder="e.g. 85.13"></label>
        <label for="magnitude">Magnitude<input id="magnitude" name="magnitude" type="number" step="0.1" min="0" max="99" value="<?= e($values['magnitude']) ?>" placeholder="If applicable"></label>
      </div>
      <div class="form-section-title spaced"><span>03</span><div><h2>Summary & source</h2><p>Help others understand the context.</p></div></div>
      <label for="summary">Incident summary <span>*</span><textarea id="summary" name="summary" rows="4" required minlength="20" placeholder="Describe what is reported, where it happened, and what is known."><?= e($values['summary']) ?></textarea></label>
      <label for="source_name">Source / reporting organization<input id="source_name" name="source_name" maxlength="180" value="<?= e($values['source_name']) ?>" placeholder="e.g. official agency name"></label>
      <label for="source_url">Source URL<input id="source_url" name="source_url" type="url" maxlength="500" value="<?= e($values['source_url']) ?>" placeholder="https://example.gov/incident-update"></label>
      <button class="button button-primary submit-button" type="submit">Submit demo record <span>↗</span></button>
      <p class="form-legal">By submitting, you understand this record is stored in a demo portal and may be visible to anyone with access to this site.</p>
    </form>
  </div>
</section>
<?php require __DIR__ . '/../app/layout/footer.php'; ?>
