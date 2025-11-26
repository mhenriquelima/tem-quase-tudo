<?php 
include __DIR__ . '/includes/header.php';
$selectedProducts = $_SESSION['carrinho'] ?? []; 
$total = array_sum(array_map(fn($p) => $p['preco'] * $p['quantidade'], $selectedProducts));
?>

<h1 style="margin-bottom: 30px; font-size: 32px;">🛒 Seu Carrinho</h1>

<?php if (empty($selectedProducts)): ?>
    <div style="background-color: #F5F5F5; padding: 40px; border-radius: 8px; text-align: center;">
        <p style="font-size: 18px; color: #666;">Seu carrinho está vazio.</p>
        <a href="/tem-quase-tudo/index.php" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #FF9900; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">← Voltar às Compras</a>
    </div>

<?php else: ?>

    <div class="cart-container">
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Preço</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selectedProducts as $idx => $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nome']) ?></td>
                            <td>
                                <input type="number" value="<?= $p['quantidade'] ?>" min="1" style="width: 50px; padding: 5px; border: 1px solid #DDD; border-radius: 4px;">
                            </td>
                            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                            <td><strong>R$ <?= number_format($p['preco'] * $p['quantidade'], 2, ',', '.') ?></strong></td>
                            <td><button style="background-color: #FF6B6B; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">🗑️ Remover</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <h3>Resumo do Pedido</h3>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
            </div>
            <div class="summary-row">
                <span>Frete:</span>
                <span>R$ 0,00</span>
            </div>
            <div class="summary-row">
                <span>Desconto:</span>
                <span>-R$ 0,00</span>
            </div>
            <div class="summary-row total">
                <span>Total:</span>
                <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
            </div>
            <button class="checkout-btn">✓ Finalizar Compra</button>
            <a href="/tem-quase-tudo/index.php" style="display: block; text-align: center; margin-top: 10px; color: #FF9900; text-decoration: none; font-weight: 600;">← Continuar Comprando</a>
        </div>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>