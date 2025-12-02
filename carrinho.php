<?php
session_start();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$selectedProducts = $_SESSION['carrinho'] ?? [];

$total = 0;
foreach ($selectedProducts as $item) {
    $total += ($item['preco'] * $item['quantidade']);
}
?>

<h1 style="margin-bottom: 30px; font-size: 32px;">🛒 Seu Carrinho</h1>

<?php if (empty($selectedProducts)): ?>
    <div style="background-color: #F5F5F5; padding: 40px; border-radius: 8px; text-align: center;">
        <p style="font-size: 18px; color: #666;">Seu carrinho está vazio.</p>
        <a href="/tem-quase-tudo/index.php" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #FF9900; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">← Voltar às Compras</a>
    </div>

<?php else: ?>

    <form method="post" action="update_carrinho.php">
    <div class="cart-container" style="display:flex;gap:24px;">
        <div class="cart-table" style="flex:1;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;border-bottom:1px solid #eee;padding:8px;">Produto</th>
                        <th style="border-bottom:1px solid #eee;padding:8px;">Qtd</th>
                        <th style="border-bottom:1px solid #eee;padding:8px;">Preço</th>
                        <th style="border-bottom:1px solid #eee;padding:8px;">Subtotal</th>
                        <th style="border-bottom:1px solid #eee;padding:8px;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selectedProducts as $idx => $p): ?>
                        <tr>
                            <td style="padding:8px;vertical-align:middle;">
                                <?= htmlspecialchars($p['nome']) ?>
                            </td>
                            <td style="padding:8px;vertical-align:middle;">
                                <input type="number" name="quantidade[<?= $p['id'] ?>]" value="<?= $p['quantidade'] ?>" min="1" max="<?= ($p['estoque'] ?? 9999) ?>" style="width:70px;padding:6px;border:1px solid #DDD;border-radius:4px;">
                            </td>
                            <td style="padding:8px;vertical-align:middle;"><?= format_price($p['preco']) ?></td>
                            <td style="padding:8px;vertical-align:middle;"><strong><?= format_price($p['preco'] * $p['quantidade']) ?></strong></td>
                            <td style="padding:8px;vertical-align:middle;">
                                <button type="submit" name="remove" value="<?= $p['id'] ?>" style="background-color:#FF6B6B;color:white;border:none;padding:8px 10px;border-radius:4px;cursor:pointer;">🗑️ Remover</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="margin-top:12px;">
                <button type="submit" name="update" style="padding:10px 14px;background:#2ecc71;color:#fff;border:none;border-radius:6px;cursor:pointer;">Atualizar Quantidades</button>
                <button type="submit" name="clear" value="1" style="padding:10px 14px;background:#e74c3c;color:#fff;border:none;border-radius:6px;cursor:pointer;margin-left:8px;">Limpar Carrinho</button>
            </div>
        </div>

        <div class="cart-summary" style="width:320px;border:1px solid #eee;padding:14px;border-radius:8px;height:fit-content;">
            <h3>Resumo do Pedido</h3>
            <div class="summary-row" style="display:flex;justify-content:space-between;">
                <span>Subtotal:</span>
                <span><?= format_price($total) ?></span>
            </div>
            <div class="summary-row" style="display:flex;justify-content:space-between;">
                <span>Frete:</span>
                <span>R$ 0,00</span>
            </div>
            <div class="summary-row" style="display:flex;justify-content:space-between;">
                <span>Desconto:</span>
                <span>-R$ 0,00</span>
            </div>
            <div class="summary-row total" style="display:flex;justify-content:space-between;font-weight:700;margin-top:10px;">
                <span>Total:</span>
                <span><?= format_price($total) ?></span>
            </div>

            <div style="margin-top:12px;">
                <a href="checkout.php" class="checkout-btn" style="display:inline-block;padding:10px 14px;background:#FF9900;color:#fff;border-radius:6px;text-decoration:none;">✓ Finalizar Compra</a>
                <a href="/tem-quase-tudo/index.php" style="display:block;text-align:center;margin-top:10px;color:#FF9900;text-decoration:none;font-weight:600;">← Continuar Comprando</a>
            </div>
        </div>
    </div>
    </form>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>