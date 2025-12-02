<?php include __DIR__ . '/includes/header.php'; ?>

<!-- Hero Section -->
<div class="hero">
    <h1>🎉 Bem-vindo ao Tem Quase Tudo</h1>
    <p>Encontre produtos incríveis com os melhores preços do mercado</p>
    <button class="hero-btn">🛍️ Começar a Comprar</button>
</div>

<!-- Banner Promoção -->
<div class="banner-promo">
    <div>
        <h2>🚀 Super Oferta do Dia!</h2>
        <p>Desconto de até 50% em produtos selecionados</p>
    </div>
    <button class="banner-promo-btn">Ver Todas as Ofertas</button>
</div>

<?php
include __DIR__ . '/includes/products.php';

// Dividir em 3 seções: 0-5, 6-11, 12+
$section1 = array_slice($products, 0, 6);
$section2 = array_slice($products, 6, 6);
$section3 = array_slice($products, 12);
?>

<!-- Produtos em Destaque -->
<?php if (!empty($section1)): ?>
<section>
    <h2 class="section-title">✨ Produtos em Destaque</h2>
    <div class="products-grid">
        <?php foreach ($section1 as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php echo $product['emoji']; ?>
                </div>
                <div class="product-info">
                    <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-rating"><?php echo $product['rating']; ?></div>
                    <?php if ($product['original_price'] > $product['price']): ?>
                        <span class="product-original-price">R$ <?php echo number_format($product['original_price'], 2, ',', '.'); ?></span>
                    <?php endif; ?>
                    <div class="product-price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></div>
                    <div class="product-actions">
                        <button class="btn-add-cart">🛒 Adicionar</button>
                        <button class="btn-wishlist">❤️</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Mais Vendidos -->
<?php if (!empty($section2)): ?>
<section>
    <h2 class="section-title">🔥 Mais Vendidos</h2>
    <div class="products-grid">
        <?php foreach ($section2 as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php echo $product['emoji']; ?>
                </div>
                <div class="product-info">
                    <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-rating"><?php echo $product['rating']; ?></div>
                    <?php if ($product['original_price'] > $product['price']): ?>
                        <span class="product-original-price">R$ <?php echo number_format($product['original_price'], 2, ',', '.'); ?></span>
                    <?php endif; ?>
                    <div class="product-price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></div>
                    <div class="product-actions">
                        <button class="btn-add-cart">🛒 Adicionar</button>
                        <button class="btn-wishlist">❤️</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Outros Produtos -->
<?php if (!empty($section3)): ?>
<section>
    <h2 class="section-title">📦 Outros Produtos</h2>
    <div class="products-grid">
        <?php foreach ($section3 as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php echo $product['emoji']; ?>
                </div>
                <div class="product-info">
                    <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-rating"><?php echo $product['rating']; ?></div>
                    <?php if ($product['original_price'] > $product['price']): ?>
                        <span class="product-original-price">R$ <?php echo number_format($product['original_price'], 2, ',', '.'); ?></span>
                    <?php endif; ?>
                    <div class="product-price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></div>
                    <div class="product-actions">
                        <button class="btn-add-cart">🛒 Adicionar</button>
                        <button class="btn-wishlist">❤️</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>