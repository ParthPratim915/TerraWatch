<?php
$pageTitle = $pageTitle ?? 'Natural hazard monitoring';
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="TerraWatch is a natural hazard monitoring and incident exploration portfolio project.">
  <meta name="theme-color" content="#0b1424">
  <title><?= e($pageTitle) ?> · TerraWatch</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(base_path('assets/css/style.css')) ?>">
</head>
<body>
<div class="site-shell">
<header class="topbar">
  <a class="brand" href="<?= e(base_path('index.php')) ?>" aria-label="TerraWatch home">
    <span class="brand-mark"><span></span><span></span><span></span></span>
    <span>terra<span class="brand-light">watch</span><small>EARTH OBSERVATION</small></span>
  </a>
  <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Menu <span>☰</span></button>
  <nav class="main-nav" id="main-nav">
    <a class="<?= $currentPage === 'index.php' ? 'active' : '' ?>" href="<?= e(base_path('index.php')) ?>">Overview</a>
    <a class="<?= $currentPage === 'incidents.php' ? 'active' : '' ?>" href="<?= e(base_path('incidents.php')) ?>">Incident explorer</a>
    <a class="<?= $currentPage === 'report.php' ? 'active' : '' ?>" href="<?= e(base_path('report.php')) ?>">Report an event</a>
  </nav>
  <div class="topbar-status"><span class="pulse-dot"></span> DEMO ENVIRONMENT</div>
</header>
<main>
