<?php
session_start();
require_once "config.inc.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int) $_GET['id'];

$query = "SELECT *, (preco - (preco * (desconto/100))) AS preco_final 
          FROM produtos 
          WHERE id = $id 
          LIMIT 1";

$result = mysqli_query($conexao, $query);
$produto = mysqli_fetch_assoc($result);

if (!$produto) {
    die("Produto não encontrado.");
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id]['quantidade']++;
} else {
    $_SESSION['carrinho'][$id] = [
        'id' => $produto['id'],
        'nome' => $produto['produto'],
        'preco' => $produto['preco_final'],
        'quantidade' => 1
    ];
}

header("Location: index.php?added=1");
exit;