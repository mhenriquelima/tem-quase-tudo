<?php
include __DIR__ . '/includes/header.php';
$q = isset($_GET['q']) ? trim(strip_tags($_GET['q'])) : '';
$results = [];
if (file_exists(__DIR__ . '/includes/products.php')) {
    include __DIR__ . '/includes/products.php';
    if ($q !== '' && !empty($products)) {
        foreach ($products as $p) {
            $hay = ($p['name'] ?? '') . ' ' . ($p['category'] ?? '');
            if (mb_stripos($hay, $q) !== false) {
                $results[] = $p;
            }
        }
    }
}
?>

<h1 style="margin-bottom: 20px; font-size: 28px;">🔍 Resultados de Busca</h1>

<?php if ($q === ''): ?>
    <div style="background-color: #F5F5F5; padding: 40px; border-radius: 8px; text-align: center;">
        <p style="font-size: 18px; color: #666;">Digite algo no campo de busca acima para encontrar produtos.</p>
    </div>
<?php else: ?>
    <div style="margin-bottom: 20px;">
        <p style="font-size: 16px; color: #666;">Resultados para: <strong style="color: #FF9900; font-size: 18px;"><?php echo htmlspecialchars($q); ?></strong></p>
    </div>

    <?php if (count($results) === 0): ?>
        <div style="background-color: #F5F5F5; padding: 40px; border-radius: 8px; text-align: center;">
            <p style="font-size: 18px; color: #666;">Nenhum produto encontrado para "<strong><?php echo htmlspecialchars($q); ?></strong>"</p>
            <a href="/tem-quase-tudo/index.php" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #FF9900; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">← Voltar ao Início</a>
        </div>
    <?php else: ?>
        <p style="color: #666; margin-bottom: 30px;"><strong><?php echo count($results); ?></strong> produto(s) encontrado(s)</p>
        
        <div class="search-results">
            <ul class="search-results-list">
                <?php foreach ($results as $r): ?>
                    <li class="search-result-item">
                        <div class="search-result-image">
                            <?php echo $r['emoji']; ?>
                        </div>
                        <div class="search-result-content">
                            <div class="search-result-category"><?php echo htmlspecialchars($r['category']); ?></div>
                            <div class="search-result-name"><?php echo htmlspecialchars($r['name']); ?></div>
                            <div style="color: #999; font-size: 14px; margin-bottom: 12px;">Produto em Destaque</div>
                            
                            <?php if ($r['original_price'] > $r['price']): ?>
                                <span style="color: #999; text-decoration: line-through; font-size: 14px;">R$ <?php echo number_format($r['original_price'], 2, ',', '.'); ?></span><br>
                            <?php endif; ?>
                            
                            <div class="search-result-price">R$ <?php echo number_format($r['price'], 2, ',', '.'); ?></div>
                            <div style="margin-top: 15px;">
                                <button style="padding: 10px 20px; background-color: #FF9900; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; margin-right: 10px;">🛒 Adicionar ao Carrinho</button>
                                <button style="padding: 10px 15px; background-color: #F5F5F5; color: #FF9900; border: 2px solid #FF9900; border-radius: 4px; cursor: pointer; font-weight: 600;">❤️ Favoritar</button>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
