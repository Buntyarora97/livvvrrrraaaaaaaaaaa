<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

/* ===== HANDLE EMPTY / TEST ===== */

if (empty($data) || isset($data['type']) && $data['type'] === 'WEBHOOK_TEST') {
    http_response_code(200);
    echo "Webhook OK";
    exit;
}

/* ===== VALIDATE ORDER STRUCTURE ===== */

if (!isset($data['data']['order']['order_id'])) {
    http_response_code(200);
    echo "Ignored";
    exit;
}

$cf_order_id = $data['data']['order']['order_id'];
$order_status = $data['data']['order']['order_status'] ?? '';

/* ===== REMOVE TIMESTAMP ===== */

$parts = explode('_', $cf_order_id);
$order_number = $parts[0];

/* ===== FETCH ORDER ===== */

$stmt = db()->prepare("SELECT id, payment_status FROM orders WHERE order_number = ?");
$stmt->execute([$order_number]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    http_response_code(200);
    echo "Order not found";
    exit;
}

/* ===== PREVENT DUPLICATE UPDATE ===== */

if ($order['payment_status'] === 'paid') {
    http_response_code(200);
    echo "Already processed";
    exit;
}

/* ===== UPDATE STATUS ===== */

if ($order_status === 'PAID') {
    Order::updatePaymentStatus($order['id'], 'paid');
    echo "Payment confirmed";
} else {
    Order::updatePaymentStatus($order['id'], 'failed');
    echo "Payment failed";
}

http_response_code(200);