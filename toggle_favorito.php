<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('Content-Type: application/json');

$id = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
} else {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
}

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

if (!isset($_SESSION['favoritos']) || !is_array($_SESSION['favoritos'])) {
    $_SESSION['favoritos'] = [];
}

$wasFavorited = isset($_SESSION['favoritos'][$id]);
if ($wasFavorited) {
    unset($_SESSION['favoritos'][$id]);
    $favorited = false;
} else {
    $_SESSION['favoritos'][$id] = true;
    $favorited = true;
}

$count = count($_SESSION['favoritos']);

echo json_encode(['success' => true, 'id' => $id, 'favorited' => $favorited, 'count' => $count]);
exit;
