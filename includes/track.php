<?php
// includes/track.php - Endpoint para rastreamento de eventos

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/database.php';

header('Content-Type: application/json');

// Log para depuração
$logFile = __DIR__ . '/../logs/track.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Received request\n", FILE_APPEND);

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Method not allowed: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Obtém dados do corpo da requisição
$input = file_get_contents('php://input');
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Request body: " . $input . "\n", FILE_APPEND);

$input = json_decode($input, true);

// Valida dados
if (!isset($input['product_id']) || !isset($input['event_type'])) {
    http_response_code(400);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Missing required fields\n", FILE_APPEND);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$validEventTypes = ['view', 'cart_add', 'whatsapp_click'];
if (!in_array($input['event_type'], $validEventTypes)) {
    http_response_code(400);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Invalid event type: " . $input['event_type'] . "\n", FILE_APPEND);
    echo json_encode(['error' => 'Invalid event type']);
    exit;
}

try {
    // Verifica se o produto existe
    $checkProduct = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
    $checkProduct->execute([$input['product_id']]);
    
    if (!$checkProduct->fetch()) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Product not found: " . $input['product_id'] . "\n", FILE_APPEND);
        throw new Exception('Product not found');
    }
    
    // Insere o evento
    $stmt = $pdo->prepare("INSERT INTO product_events 
                          (product_id, event_type, user_ip, user_agent, referrer) 
                          VALUES (?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $input['product_id'],
        $input['event_type'],
        $_SERVER['REMOTE_ADDR'] ?? null,
        $_SERVER['HTTP_USER_AGENT'] ?? null,
        $_SERVER['HTTP_REFERER'] ?? null
    ]);
    
    $insertId = $pdo->lastInsertId();
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Event recorded successfully: " . $insertId . "\n", FILE_APPEND);
    
    echo json_encode(['success' => true, 'id' => $insertId]);
    
} catch (Exception $e) {
    http_response_code(500);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}