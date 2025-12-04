<?php
require_once __DIR__ . '/../config.inc.php';

$query = "SELECT id, nome, email, telefone, endereco, cidade FROM clientes";
$resultado = mysqli_query($conexao, $query);

$clientes = [];
if ($resultado) {
    $clientes = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
} else {
    $error_msg = 'Erro ao buscar clientes: ' . mysqli_error($conexao);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes - Tem Quase Tudo</title>
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
                <h1 class="admin-page-title">Clientes</h1>
                <p class="admin-page-subtitle">Gerencie todos os clientes cadastrados no sistema</p>
            </div>
            <a href="cadastrar_cliente.php" class="btn btn-primary">➕ Adicionar Cliente</a>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <div class="alert-content">
                    <strong>Erro!</strong>
                    <p><?= htmlspecialchars($error_msg) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($clientes)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">👥</div>
                <h3>Nenhum cliente cadastrado</h3>
                <p>Comece adicionando um novo cliente ao sistema</p>
                <a href="cadastrar_cliente.php" class="btn btn-primary">➕ Adicionar Primeiro Cliente</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Cidade</th>
                            <th style="width: 180px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($c['id']) ?></strong></td>
                                <td><?= htmlspecialchars($c['nome']) ?></td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td><?= htmlspecialchars($c['telefone'] ?: '-') ?></td>
                                <td><?= htmlspecialchars($c['cidade'] ?: '-') ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="editar_cliente.php?id=<?= urlencode($c['id']) ?>" class="btn btn-info btn-small">✏️ Editar</a>
                                        <a href="deletar_cliente.php?id=<?= urlencode($c['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">🗑️ Excluir</a>
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
