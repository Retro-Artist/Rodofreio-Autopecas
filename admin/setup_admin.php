<?php
// setup_admin.php
require_once 'includes/database.php';

$username = 'rodofreios';
$password = 'Acesso@2025';
$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $hash, 'admin@simplesquare.local']);

echo "Admin user created successfully!";
?>