<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int) $_GET['id'];

if (!isset($conexao) || !$conexao) {
    die("Erro ao conectar ao banco de dados.");
}

$stmt = mysqli_prepare($conexao, "SELECT id, produto, preco, IFNULL(desconto,0) AS desconto, (preco - (preco * (desconto/100))) AS preco_final FROM produtos WHERE id = ? LIMIT 1");
if (!$stmt) {
    die("Erro na preparação da consulta.");
}
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produto = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$produto) {
    die("Produto não encontrado.");
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$productId = (int) $produto['id'];
if (isset($_SESSION['carrinho'][$productId])) {
    $_SESSION['carrinho'][$productId]['quantidade']++;
} else {
    $_SESSION['carrinho'][$productId] = [
        'id' => $productId,
        'nome' => $produto['produto'],
        'preco' => (float) $produto['preco_final'],
        'quantidade' => 1
    ];
}

header("Location: index.php?added=1");
exit;