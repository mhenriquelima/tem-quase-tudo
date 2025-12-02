<?php
session_start();
require_once __DIR__ . '/config.inc.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$id = (int) $_GET['id'];

$produto = get_product_by_id($conexao, $id);
if (!$produto) {
    header("Location: index.php?error=not_found");
    exit;
}

if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($produto['estoque'] <= 0) {
    header("Location: index.php?error=out_of_stock");
    exit;
}

if (isset($_SESSION['carrinho'][$id])) {
    $currentQty = (int) $_SESSION['carrinho'][$id]['quantidade'];
    if ($currentQty < $produto['estoque']) {
        $_SESSION['carrinho'][$id]['quantidade'] = $currentQty + 1;
    }
} else {
    $_SESSION['carrinho'][$id] = [
        'id' => $produto['id'],
        'nome' => $produto['produto'],
        'preco' => $produto['preco_final'],
        'quantidade' => 1,
        'estoque' => $produto['estoque']
    ];
}

header("Location: index.php?added=1");
exit;