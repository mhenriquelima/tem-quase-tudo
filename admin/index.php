<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - Tem Quase Tudo</title>
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
            <h1 class="admin-page-title">Painel de Administração</h1>
            <p class="admin-page-subtitle">Gerencie produtos, clientes e configurações do sistema</p>

            <nav class="admin-nav" style="margin-top: 30px;">
                <li class="admin-nav-item">
                    <a href="database-setup.php">
                        <span class="icon">⚙️</span>
                        <span>Setup do Banco de Dados</span>
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="produtos/index.php">
                        <span class="icon">📦</span>
                        <span>Gerenciar Produtos</span>
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="clientes/index.php">
                        <span class="icon">👥</span>
                        <span>Gerenciar Clientes</span>
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="login/login.php">
                        <span class="icon">🔐</span>
                        <span>Login</span>
                    </a>
                </li>
            </nav>
        </div>
    </div>
</body>
</html>