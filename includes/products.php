<?php
require_once __DIR__ . '/db.php';

$products = [];

// Se a conexão com o banco foi estabelecida (arquivo includes/db.php)
// tentamos carregar os produtos da tabela `produtos`.
// Caso não exista conexão ou a consulta falhe, deixamos $products como array vazio.
if (isset($dbError) && $dbError === false && isset($conexao) && $conexao) {
    $query = "SELECT id, produto AS name, preco, IFNULL(desconto,0) AS desconto FROM produtos ORDER BY id ASC";
    $result = @mysqli_query($conexao, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $original_price = isset($row['preco']) ? (float) $row['preco'] : 0.0;
            $desconto = isset($row['desconto']) ? (float) $row['desconto'] : 0.0;
            $price = $original_price - ($original_price * ($desconto / 100));

            $products[] = [
                'id' => isset($row['id']) ? (int) $row['id'] : 0,
                'name' => $row['name'] ?? '',
                'price' => round($price, 2),
                'original_price' => round($original_price, 2),
                'category' => 'Geral',
                'rating' => ($desconto >= 30) ? '⭐⭐⭐⭐⭐' : '⭐⭐⭐⭐',
                'emoji' => '📦'
            ];
        }
        mysqli_free_result($result);
    }
}

// Não criar produtos fictícios aqui — manter $products vazio se não houver dados reais

?>
