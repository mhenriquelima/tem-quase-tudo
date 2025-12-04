<?php
    require_once("../config.inc.php");

    $query = "SELECT * FROM produtos";
    $resultado = mysqli_query($conexao, $query);

    $produtos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos - Tem Quase Tudo</title>
    <link rel="stylesheet" href="/tem-quase-tudo/assets/admin.css">
</head>
<body>

<div class="admin-header">
    <a href="/tem-quase-tudo/" class="admin-header-title">
        <span>📦</span>
        <span>Tem Quase Tudo - Admin</span>
    </a>
    <div class="admin-header-actions">
        <a href="/tem-quase-tudo/" class="btn btn-secondary btn-small">← Voltar ao site</a>
    </div>
</div>

<div class="admin-container">
    <div class="admin-main">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1 class="admin-page-title">Produtos</h1>
                <p class="admin-page-subtitle">Total de <?= count($produtos) ?> produto(s) cadastrado(s)</p>
            </div>
            <a href="cadastrar_produto.php" class="btn btn-primary">➕ Adicionar Produto</a>
        </div>

        <?php if (empty($produtos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>Nenhum produto cadastrado</h3>
                <p>Comece adicionando um novo produto ao catálogo</p>
                <a href="cadastrar_produto.php" class="btn btn-primary">➕ Adicionar Primeiro Produto</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Produto</th>
                            <th style="text-align: right; width: 100px;">Preço</th>
                            <th style="text-align: center; width: 80px;">Desconto</th>
                            <th style="text-align: right; width: 100px;">Preço Final</th>
                            <th style="text-align: center; width: 80px;">Estoque</th>
                            <th style="width: 180px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $p): ?>
                            <?php 
                                $precoFinal = $p['preco'] - ($p['preco'] * ($p['desconto'] / 100));
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($p['id']) ?></strong></td>
                                <td><?= htmlspecialchars($p['produto']) ?></td>
                                <td style="text-align: right;">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                <td style="text-align: center;">
                                    <?php if ($p['desconto'] > 0): ?>
                                        <span class="badge badge-warning"><?= htmlspecialchars($p['desconto']) ?>%</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <strong style="color: var(--primary-orange);">R$ <?= number_format($precoFinal, 2, ',', '.') ?></strong>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($p['estoque'] > 0): ?>
                                        <span class="badge badge-success"><?= htmlspecialchars($p['estoque']) ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-error">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="atualizar_produtos.php?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-info btn-small">✏️ Editar</a>
                                        <a href="deletar_produtos.php?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Tem certeza que deseja excluir este produto?')">🗑️ Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px;">
            <a href="index.php" class="btn btn-secondary">← Voltar</a>
        </div>
    </div>
</div>

</body>
</html>