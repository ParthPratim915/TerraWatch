<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$configFile = __DIR__ . '/../config/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Configuration missing. Copy config/config.example.php to config/config.php and set database credentials.');
}
$config = require $configFile;

function e(mixed $value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_path(string $path = ''): string {
    global $config;
    $base = rtrim((string)($config['site']['base_path'] ?? ''), '/');
    return $base . '/' . ltrim($path, '/');
}

function db(): PDO {
    static $pdo = null;
    global $config;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $db = $config['db'];
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $db['host'], $db['port'], $db['database'], $db['charset'] ?? 'utf8mb4'
    );
    try {
        $pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $ex) {
        error_log('TerraWatch database connection failed: ' . $ex->getMessage());
        http_response_code(500);
        exit('Unable to connect to the database. Check config/config.php and import database/schema.sql.');
    }
    return $pdo;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('Your form session expired. Go back, refresh the page, and try again.');
    }
}

function hazard_types(): array {
    return ['Earthquake','Flood','Wildfire','Cyclone','Volcanic Activity','Landslide','Extreme Weather','Other'];
}
function severities(): array { return ['Low','Moderate','High','Critical']; }
function statuses(): array { return ['Monitoring','Active','Contained','Resolved','Unverified']; }

function incident_badge_class(string $value): string {
    return strtolower(str_replace(' ', '-', $value));
}

function format_date(?string $value): string {
    if (!$value) return 'Date not available';
    try { return (new DateTimeImmutable($value))->format('M j, Y · H:i'); }
    catch (Throwable $e) { return $value; }
}
