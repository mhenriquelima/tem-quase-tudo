<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['remove'])) {
        $id = (int) $_POST['remove'];
        if (isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }
        if ($isAjax) {
            $total = array_sum(array_map(fn($p) => $p['preco'] * $p['quantidade'], $_SESSION['carrinho']));
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'action' => 'remove', 'total' => $total, 'items' => count($_SESSION['carrinho'])]);
            exit;
        }
        header("Location: carrinho.php");
        exit;
    }

    if (isset($_POST['clear']) && $_POST['clear'] == '1') {
        $_SESSION['carrinho'] = [];
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'action' => 'clear', 'total' => 0, 'items' => 0]);
            exit;
        }
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

        $total = array_sum(array_map(fn($p) => $p['preco'] * $p['quantidade'], $_SESSION['carrinho']));

        if ($isAjax) {
            $items = [];
            foreach ($_SESSION['carrinho'] as $iid => $it) {
                $items[(int)$iid] = $it['preco'] * $it['quantidade'];
            }
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'action' => 'update', 'total' => $total, 'items_subtotals' => $items]);
            exit;
        }
    }
}

header("Location: carrinho.php");
exit;