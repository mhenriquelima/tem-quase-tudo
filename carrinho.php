<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
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
        <form action="update_carrinho.php" method="post">
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
                        <?php foreach ($selectedProducts as $id => $p): ?>
                            <tr data-id="<?= (int)$id ?>">
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td>
                                    <input class="qty-input" type="number" name="quantidade[<?= (int)$id ?>]" value="<?= (int)$p['quantidade'] ?>" min="1" data-id="<?= (int)$id ?>" data-price="<?= $p['preco'] ?>" style="width: 60px; padding: 5px; border: 1px solid #DDD; border-radius: 4px;">
                                </td>
                                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                <td><strong><span class="item-subtotal" id="subtotal-<?= (int)$id ?>">R$ <?= number_format($p['preco'] * $p['quantidade'], 2, ',', '.') ?></span></strong></td>
                                <td>
                                    <button type="submit" class="btn-remove" name="remove" value="<?= (int)$id ?>" style="background-color: #FF6B6B; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">🗑️ Remover</button>
                                </td>
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
                    <span id="cart-total">R$ <?= number_format($total, 2, ',', '.') ?></span>
                </div>

                <div style="margin-top: 14px; display: flex; gap: 10px;">
                    <button type="submit" id="btn-clear" name="clear" value="1" style="background-color: #E67E22; color: white; border: none; padding: 10px 14px; border-radius: 4px; cursor: pointer;">Limpar Carrinho</button>
                    <a href="/tem-quase-tudo/index.php" style="display: inline-block; margin-left: auto; padding: 10px 14px; background-color: #FF9900; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">← Continuar Comprando</a>
                </div>

                <div style="margin-top: 12px;">
                    <button class="checkout-btn" type="button" onclick="alert('Implementar checkout');" style="margin-top:10px;">✓ Finalizar Compra</button>
                </div>
            </div>
        </form>
    </div>

<?php endif; ?>

<script>
// Helper to format number as BRL (simple)
function formatBRL(value) {
    return 'R$ ' + Number(value).toFixed(2).replace('.', ',');
}

document.addEventListener('DOMContentLoaded', function () {
    const qtyInputs = document.querySelectorAll('.qty-input');
    const cartTotalEl = document.getElementById('cart-total');
    const form = document.querySelector('form[action="update_carrinho.php"]');

    async function postUpdate(formData) {
        formData.append('ajax', '1');
        const resp = await fetch('update_carrinho.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        return resp.json();
    }

    qtyInputs.forEach(input => {
        input.addEventListener('change', async function (e) {
            const id = this.dataset.id;
            const qty = parseInt(this.value, 10) || 0;
            const fd = new FormData();
            fd.append('update', '1');
            fd.append(`quantidade[${id}]`, qty);

            try {
                const data = await postUpdate(fd);
                if (data && data.success) {
                    if (data.items_subtotals && data.items_subtotals[id] !== undefined) {
                        const sub = document.getElementById('subtotal-' + id);
                        if (sub) sub.textContent = formatBRL(data.items_subtotals[id]);
                    }
                    if (data.total !== undefined && cartTotalEl) {
                        cartTotalEl.textContent = formatBRL(data.total);
                    }
                }
            } catch (err) {
                console.error('Erro ao atualizar quantidade', err);
            }
        });
    });

    // Handle remove buttons via AJAX
    const removeButtons = document.querySelectorAll('.btn-remove');
    removeButtons.forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const id = this.value;
            const fd = new FormData();
            fd.append('remove', id);
            try {
                const data = await postUpdate(fd);
                if (data && data.success) {
                    // remove row
                    const row = document.querySelector('tr[data-id="' + id + '"]');
                    if (row) row.remove();
                    if (cartTotalEl && data.total !== undefined) cartTotalEl.textContent = formatBRL(data.total);
                }
            } catch (err) {
                console.error('Erro ao remover item', err);
            }
        });
    });

    // Clear cart button via AJAX
    const btnClear = document.getElementById('btn-clear');
    if (btnClear) {
        btnClear.addEventListener('click', async function (e) {
            e.preventDefault();
            if (!confirm('Tem certeza que deseja limpar o carrinho?')) return;
            const fd = new FormData();
            fd.append('clear', '1');
            try {
                const data = await postUpdate(fd);
                if (data && data.success) {
                    // reload to show empty state
                    window.location.reload();
                }
            } catch (err) {
                console.error('Erro ao limpar carrinho', err);
            }
        });
    }

    // Update all quantities via AJAX when clicking update button
    const btnUpdate = document.getElementById('btn-update');
    if (btnUpdate) {
        btnUpdate.addEventListener('click', async function (e) {
            e.preventDefault();
            const fd = new FormData();
            fd.append('update', '1');
            document.querySelectorAll('.qty-input').forEach(input => {
                const id = input.dataset.id;
                fd.append(`quantidade[${id}]`, input.value);
            });
            try {
                const data = await postUpdate(fd);
                if (data && data.success) {
                    if (data.items_subtotals) {
                        for (const iid in data.items_subtotals) {
                            const subEl = document.getElementById('subtotal-' + iid);
                            if (subEl) subEl.textContent = formatBRL(data.items_subtotals[iid]);
                        }
                    }
                    if (data.total !== undefined && cartTotalEl) cartTotalEl.textContent = formatBRL(data.total);
                }
            } catch (err) {
                console.error('Erro ao atualizar carrinho', err);
            }
        });
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>