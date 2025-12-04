<?php
    echo "index de clientes";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Clientes - Tem Quase Tudo</title>
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
        <h1 class="admin-page-title">Gerenciar Clientes</h1>
        <p class="admin-page-subtitle">Visualize, edite e delete clientes do sistema</p>

        <nav class="admin-nav" style="margin-top: 20px;">
            <li class="admin-nav-item">
                <a href="cadastrar_cliente.php">
                    <span class="icon">➕</span>
                    <span>Adicionar Cliente</span>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="listar_cliente.php">
                    <span class="icon">📋</span>
                    <span>Listar Clientes</span>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="../index.php">
                    <span class="icon">⬅️</span>
                    <span>Voltar ao Painel Principal</span>
                </a>
            </li>
        </nav>
    </div>
</div>

</body>
</html>

