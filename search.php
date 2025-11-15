<?php
// Ler a query 'q' da URL (ex: search.php?q=camiseta)
// Usamos strip_tags e trim para deixar simples e seguro para iniciantes.
$q = isset($_GET['q']) ? trim(strip_tags($_GET['q'])) : '';
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Busca — Tem Quase Tudo</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <main>
    <?php
    // Tentar carregar uma lista de produtos simples se o arquivo existir
    $results = [];
    if (file_exists(__DIR__ . '/includes/products.php')) {
      include __DIR__ . '/includes/products.php';
      // $products deve existir no arquivo incluído (array simples)
      if ($q !== '') {
        foreach ($products as $p) {
          $hay = $p['name'] . ' ' . $p['description'];
          if (mb_stripos($hay, $q) !== false) {
            $results[] = $p;
          }
        }
      }
    }

    if ($q === '') {
      echo '<p>A busca ainda não foi usada. Digite algo no campo de busca.</p>';
    } else {
      echo '<p>Resultados para: <strong>' . htmlspecialchars($q) . '</strong></p>';
      if (count($results) === 0) {
        echo '<p>Nenhum produto encontrado.</p>';
      } else {
        echo '<p>' . count($results) . ' produto(s) encontrado(s):</p>';
        echo '<ul>';
        foreach ($results as $r) {
          echo '<li>' . htmlspecialchars($r['name']) . ' - R$ ' . number_format($r['price'], 2, ',', '.') . ' (' . htmlspecialchars($r['category']) . ')</li>';
        }
        echo '</ul>';
      }
    }
    ?>
  </main>
</body>
</html>
