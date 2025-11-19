<?php
$q = isset($_GET['q']) ? trim(strip_tags($_GET['q'])) : '';
$results = [];
if (file_exists(__DIR__ . '/includes/products.php')) {
    include __DIR__ . '/includes/products.php';
    if ($q !== '' && !empty($products)) {
        foreach ($products as $p) {
            $hay = ($p['name'] ?? '') . ' ' . ($p['description'] ?? '') . ' ' . ($p['category'] ?? '');
            if (mb_stripos($hay, $q) !== false) {
                $results[] = $p;
            }
        }
    }
}
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
    <h1>Tem Quase Tudo — Busca de Produtos</h1>
    
    <form method="get" action="">
      <input name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Pesquisar produtos (ex: caneca)">
      <button type="submit">Buscar</button>
    </form>

    <?php if ($q === ''): ?>
      <p>Digite algo no campo acima para pesquisar.</p>
    <?php else: ?>
      <p>Resultados para: <strong><?php echo htmlspecialchars($q); ?></strong></p>
      <?php if (count($results) === 0): ?>
        <p>Nenhum produto encontrado.</p>
      <?php else: ?>
        <p><?php echo count($results); ?> produto(s) encontrado(s):</p>
        <ul>
          <?php foreach ($results as $r): ?>
            <li>
              <strong><?php echo htmlspecialchars($r['name']); ?></strong><br>
              Preço: R$ <?php echo number_format($r['price'], 2, ',', '.'); ?><br>
              Descrição: <?php echo htmlspecialchars($r['description']); ?><br>
              Categoria: <?php echo htmlspecialchars($r['category']); ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php endif; ?>
  </main>
</body>
</html>
