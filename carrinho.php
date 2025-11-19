<?php $selectedProducts = $_SESSION['carrinho'] ?? []; ?>

<h1>Seu Carrinho 🛒</h1>

<?php if (empty($selectedProducts)): ?>
    <p>Seu carrinho está vazio.</p>

<?php else: ?>

    <table border="1" width="60%">
        <tr>
            <th>Produto</th>
            <th>Qtd</th>
            <th>Preço</th>
            <th>Subtotal</th>
        </tr>

        <?php foreach ($selectedProducts as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>

                <td><?= $p['quantidade'] ?></td>

                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>

                <td>R$ <?= number_format($p['preco'] * $p['quantidade'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Total: 
        R$ 
        <?= number_format(array_sum(
            array_map(fn($p) => $p['preco'] * $p['quantidade'], $selectedProducts)
        ), 2, ',', '.') ?>
    </h2>

<?php endif; ?>