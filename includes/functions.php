<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function get_products(mysqli $conexao, int $limit = null): array {
    $sql = "SELECT id, produto AS name, descricao AS description, preco, desconto, estoque, 
                   (preco - (preco * (desconto/100))) AS preco_final,
                   COALESCE(imagem, '') AS image,
                   COALESCE(categoria, '') AS category,
                   COALESCE(rating, '') AS rating
            FROM produtos
            ORDER BY id ASC";
    if ($limit !== null && $limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }

    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da query: " . $conexao->error);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $products = [];
    while ($row = $res->fetch_assoc()) {
        $row['id'] = (int)$row['id'];
        $row['preco'] = (float)$row['preco'];
        $row['desconto'] = (float)$row['desconto'];
        $row['preco_final'] = (float)$row['preco_final'];
        $row['estoque'] = isset($row['estoque']) ? (int)$row['estoque'] : 0;
        $products[] = $row;
    }

    $stmt->close();
    return $products;
}

function get_product_by_id(mysqli $conexao, int $id): ?array {
    $sql = "SELECT id, produto AS name, descricao AS description, preco, desconto, estoque,
                   (preco - (preco * (desconto/100))) AS preco_final,
                   COALESCE(imagem, '') AS image,
                   COALESCE(categoria, '') AS category,
                   COALESCE(rating, '') AS rating
            FROM produtos
            WHERE id = ?
            LIMIT 1";
    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc() ?: null;
    $stmt->close();

    if ($product) {
        $product['id'] = (int)$product['id'];
        $product['preco'] = (float)$product['preco'];
        $product['desconto'] = (float)$product['desconto'];
        $product['preco_final'] = (float)$product['preco_final'];
        $product['estoque'] = isset($product['estoque']) ? (int)$product['estoque'] : 0;
    }

    return $product;
}

function format_price($valor): string {
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}