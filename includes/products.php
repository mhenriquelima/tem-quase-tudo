<?php
require_once __DIR__ . '/db.php';

$products = [];

if (isset($dbError) && $dbError === false && isset($conexao) && $conexao) {
    $query = "SELECT id, produto, preco, IFNULL(desconto,0) AS desconto FROM produtos ORDER BY id ASC";
    $result = @mysqli_query($conexao, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $original_price = (float) $row['preco'];
            $desconto = (float) $row['desconto'];
            $price = $original_price - ($original_price * ($desconto / 100));

            $products[] = [
                'id' => (int) $row['id'],
                'name' => $row['produto'],
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

if (empty($products)) {
    $products = [
        [
            'id' => 1,
            'name' => 'Fone de Ouvido Bluetooth',
            'price' => 89.90,
            'original_price' => 129.90,
            'category' => 'Eletrônicos',
            'rating' => '⭐⭐⭐⭐⭐',
            'emoji' => '🎧'
        ]
    ];
}

?>
