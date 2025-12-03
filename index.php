<?php include __DIR__ . '/includes/header.php'; ?>

<!-- Hero Section -->
<div class="hero">
    <h1>Bem-vindo ao Tem Quase Tudo</h1>
    <p>Encontre produtos incríveis com os melhores preços do mercado</p>
    <button class="hero-btn">Começar a Comprar</button>
</div>

<!-- Banner Promoção -->
<div class="banner-promo">
    <div>
        <h2>Super Oferta do Dia!</h2>
        <p>Desconto de até 50% em produtos selecionados</p>
    </div>
    <button class="banner-promo-btn">Ver Todas as Ofertas</button>
</div>

<?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
    <div class="notice success">Produto adicionado ao carrinho.</div>
<?php endif; ?>

<?php
include __DIR__ . '/includes/products.php';

// favoritos da sessão
$favorites = $_SESSION['favoritos'] ?? [];

// Dividir em 3 seções: 0-5, 6-11, 12+
$section1 = array_slice($products, 0, 6);
$section2 = array_slice($products, 6, 6);
$section3 = array_slice($products, 12);
?>

<!-- Produtos em Destaque -->
<?php if (!empty($section1)): ?>
<section>
    <h2 class="section-title">Produtos em Destaque</h2>
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
                        <a class="btn-add-cart" href="add_carrinho.php?id=<?php echo $product['id']; ?>">🛒 Adicionar</a>
                        <button class="btn-wishlist<?php echo isset($favorites[$product['id']]) ? ' favorited' : ''; ?>" data-id="<?php echo $product['id']; ?>">❤️</button>
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
    <h2 class="section-title">Mais Vendidos</h2>
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
                        <a class="btn-add-cart" href="add_carrinho.php?id=<?php echo $product['id']; ?>">🛒 Adicionar</a>
                        <button class="btn-wishlist<?php echo isset($favorites[$product['id']]) ? ' favorited' : ''; ?>" data-id="<?php echo $product['id']; ?>">❤️</button>
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
    <h2 class="section-title">Outros Produtos</h2>
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
                        <a class="btn-add-cart" href="add_carrinho.php?id=<?php echo $product['id']; ?>">🛒 Adicionar</a>
                        <button class="btn-wishlist<?php echo isset($favorites[$product['id']]) ? ' favorited' : ''; ?>" data-id="<?php echo $product['id']; ?>">❤️</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const favButtons = document.querySelectorAll('.btn-wishlist');
    const favCountEl = document.getElementById('fav-count');

    async function toggleFav(id) {
        const fd = new FormData();
        fd.append('id', id);
        try {
            const res = await fetch('toggle_favorito.php', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            return await res.json();
        } catch (e) {
            console.error('Erro ao alternar favorito', e);
            return null;
        }
    }

    favButtons.forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const data = await toggleFav(id);
            if (!data) return;
            if (data.success) {
                if (data.favorited) {
                    this.classList.add('favorited');
                } else {
                    this.classList.remove('favorited');
                }
                if (favCountEl) favCountEl.textContent = data.count;
            }
        });
    });
});
</script>