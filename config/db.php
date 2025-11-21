<?php
$config = (object)[
  'db' => (object)[
    'host' => '127.0.0.1',
    'name' => 'projet_groupe3',
    'user' => 'root',
    'pass' => ''
  ]
];

$dsn = "mysql:host={$config->db->host};dbname={$config->db->name};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $config->db->user, $config->db->pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    die("DB Connection failed: " . $e->getMessage());
}