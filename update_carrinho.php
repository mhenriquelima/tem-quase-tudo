<?php
session_start();

if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['remove'])) {
        $id = (int) $_POST['remove'];
        if (isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }
        header("Location: carrinho.php");
        exit;
    }

    if (isset($_POST['clear']) && $_POST['clear'] == '1') {
        $_SESSION['carrinho'] = [];
        header("Location: carrinho.php");
        exit;
    }

    if (isset($_POST['update']) && isset($_POST['quantidade']) && is_array($_POST['quantidade'])) {
        foreach ($_POST['quantidade'] as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($_SESSION['carrinho'][$id]);
                continue;
            }
            if (isset($_SESSION['carrinho'][$id])) {
                $max = $_SESSION['carrinho'][$id]['estoque'] ?? null;
                if ($max !== null && is_numeric($max) && $qty > $max) {
                    $_SESSION['carrinho'][$id]['quantidade'] = (int)$max;
                } else {
                    $_SESSION['carrinho'][$id]['quantidade'] = $qty;
                }
            }
        }
    }
}

header("Location: carrinho.php");
exit;