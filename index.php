<?php
include __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config.inc.php';
require_once __DIR__ . '/includes/functions.php';

$products = get_products($conexao, 100);

$section1 = array_slice($products, 0, 6);
$section2 = array_slice($products, 6, 6);
$section3 = array_slice($products, 12, 9999);
?>

<div class="hero">
    <h1>🎉 Bem-vindo ao Tem Quase Tudo</h1>
    <p>Encontre produtos incríveis com os melhores preços do mercado</p>
    <a href="#produtos" class="hero-btn">🛍️ Começar a Comprar</a>
</div>

<div class="banner-promo">
    <div>
        <h2>🚀 Super Oferta do Dia!</h2>
        <p>Desconto de até 50% em produtos selecionados</p>
    </div>
    <a class="banner-promo-btn" href="#produtos">Ver Todas as Ofertas</a>
</div>

<?php function render_products_section($title, $items) {
    ?>
    <?php if (!empty($items)): ?>
    <section id="produtos" style="margin:24px 0;">
        <h2 class="section-title"><?= htmlspecialchars($title) ?></h2>
        <div class="products-grid" style="display:flex;flex-wrap:wrap;gap:14px;">
            <?php foreach ($items as $product): ?>
                <div class="product-card" style="width:220px;border:1px solid #eee;padding:12px;border-radius:8px;">
                    <div class="product-image" style="height:100px;display:flex;align-items:center;justify-content:center;font-size:32px;">
                        <?php
                            if (!empty($product['image']) && file_exists(__DIR__ . '/' . ltrim($product['image'], '/'))) {
                                echo '<img src="'.htmlspecialchars($product['image']).'" alt="'.htmlspecialchars($product['name']).'" style="max-height:90px;max-width:100%;">';
                            } else {
                                echo '🛍️';
                            }
                        ?>
                    </div>
                    <div class="product-info" style="margin-top:8px;">
                        <div class="product-category" style="font-size:0.85rem;color:#777;"><?php echo htmlspecialchars($product['category'] ?: ''); ?></div>
                        <h3 class="product-name" style="font-size:1rem;margin:6px 0;"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-rating" style="font-size:0.85rem;color:#f39c12;"><?php echo htmlspecialchars($product['rating'] ?: ''); ?></div>

                        <div style="margin-top:8px;">
                            <?php if ($product['desconto'] > 0): ?>
                                <div style="font-size:0.85rem;color:#888;text-decoration:line-through;"><?php echo format_price($product['preco']); ?></div>
                            <?php endif; ?>
                            <div class="product-price" style="font-weight:700;"><?php echo format_price($product['preco_final']); ?></div>
                        </div>

                        <div class="product-actions" style="margin-top:10px;display:flex;gap:8px;">
                            <?php if ($product['estoque'] > 0): ?>
                                <a href="add_carrinho.php?id=<?php echo $product['id']; ?>" class="btn-add-cart" style="padding:8px 10px;background:#FF9900;color:#fff;border-radius:6px;text-decoration:none;">🛒 Adicionar</a>
                            <?php else: ?>
                                <button disabled style="padding:8px 10px;border-radius:6px;background:#ccc;color:#fff;border:none;">Esgotado</button>
                            <?php endif; ?>
                            <a href="produto.php?id=<?php echo $product['id']; ?>" style="padding:8px 10px;border-radius:6px;border:1px solid #ddd;text-decoration:none;">Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    <?php
}
?>

<?php render_products_section('✨ Produtos em Destaque', $section1); ?>
<?php render_products_section('🔥 Mais Vendidos', $section2); ?>
<?php render_products_section('📦 Outros Produtos', $section3); ?>

<?php include __DIR__ . '/includes/footer.php'; ?>