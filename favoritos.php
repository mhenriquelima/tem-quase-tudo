<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';

$favIds = array_keys($_SESSION['favoritos'] ?? []);
$products = [];

if (!empty($favIds) && isset($conexao) && !$dbError) {
    // Prepare statement with IN clause
    $placeholders = implode(',', array_fill(0, count($favIds), '?'));
    $types = str_repeat('i', count($favIds));
    $sql = "SELECT id, produto AS name, preco, IFNULL(desconto,0) AS desconto FROM produtos WHERE id IN ($placeholders)";
    $stmt = mysqli_prepare($conexao, $sql);
    if ($stmt) {
        // bind params dynamically
        $refs = [];
        $refs[] = &$types;
        foreach ($favIds as $k => $id) {
            $refs[] = &$favIds[$k];
        }
        call_user_func_array('mysqli_stmt_bind_param', array_merge([$stmt], $refs));
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $original_price = (float) $row['preco'];
            $desconto = (float) $row['desconto'];
            $price = $original_price - ($original_price * ($desconto / 100));
            $products[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'price' => round($price, 2),
                'original_price' => round($original_price, 2),
                'emoji' => '📦'
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

// If DB not available or no products found, fall back to simple placeholder for each fav id
if (empty($products) && !empty($favIds)) {
    foreach ($favIds as $id) {
        $products[] = [
            'id' => (int)$id,
            'name' => 'Produto #' . (int)$id,
            'price' => 0.00,
            'original_price' => 0.00,
            'emoji' => '📦'
        ];
    }
}
?>

<h1 style="margin-bottom: 20px; font-size: 32px;">❤️ Seus Favoritos</h1>

<?php if (empty($products)): ?>
    <div style="background-color: #F5F5F5; padding: 40px; border-radius: 8px; text-align: center;">
        <p style="font-size: 18px; color: #666;">Você não tem produtos nos favoritos.</p>
        <a href="/tem-quase-tudo/index.php" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #FF9900; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">← Voltar às Compras</a>
    </div>
<?php else: ?>
    <div class="favorites-grid" style="display:flex; flex-wrap:wrap; gap:16px;">
        <?php foreach ($products as $p): ?>
            <div class="fav-card" style="border:1px solid #EEE; padding:16px; border-radius:8px; width:220px; position:relative;">
                <div style="font-size:36px;"><?= $p['emoji'] ?></div>
                <h3 style="margin:6px 0; font-size:18px;"><?= htmlspecialchars($p['name']) ?></h3>
                <div style="color:#666;">R$ <?= number_format($p['price'], 2, ',', '.') ?></div>
                <div style="margin-top:10px; display:flex; gap:8px;">
                    <a href="add_carrinho.php?id=<?= $p['id'] ?>" style="background:#2ECC71; color:white; padding:8px 10px; border-radius:6px; text-decoration:none;">🛒 Adicionar ao Carrinho</a>
                    <button class="btn-fav-remove" data-id="<?= $p['id'] ?>" style="background:#FF6B6B; color:white; border:none; padding:8px 10px; border-radius:6px; cursor:pointer;">Remover</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-fav-remove').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            const id = this.dataset.id;
            const fd = new FormData();
            fd.append('id', id);
            try {
                const res = await fetch('toggle_favorito.php', { method: 'POST', body: fd, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                if (data && data.success) {
                    // remove card
                    this.closest('.fav-card').remove();
                    // update header count
                    const favCount = document.getElementById('fav-count');
                    if (favCount) favCount.textContent = data.count;
                    // if no more cards, reload to show empty state
                    if (document.querySelectorAll('.fav-card').length === 0) {
                        window.location.reload();
                    }
                }
            } catch (err) {
                console.error(err);
            }
        });
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>