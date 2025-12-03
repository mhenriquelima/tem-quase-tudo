<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$cart_count = count($_SESSION['carrinho'] ?? []);
$fav_count = count($_SESSION['favoritos'] ?? []);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tem Quase Tudo - E-commerce</title>
    <link rel="stylesheet" href="/tem-quase-tudo/assets/styles.css">
</head>
<body>
    <!-- Header Top -->
    <header>
        <div class="header-top">
            <div>
                Bem-vindo ao <strong>Tem Quase Tudo</strong>
            </div>
            <div>
                <a href="/tem-quase-tudo/sobre.php">Sobre</a>
                <a href="/tem-quase-tudo/contato.php">Contato</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <a href="/tem-quase-tudo/admin/">Admin</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Header Principal -->
        <div class="header-main">
            <a href="/tem-quase-tudo/" class="logo">Tem Quase Tudo</a>

            <form class="search-container" method="GET" action="/tem-quase-tudo/search.php">
                <input type="text" name="q" placeholder="Pesquisar produtos (ex: caneca, fone...)" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit">🔍 Buscar</button>
            </form>

            <div class="header-actions">
                <a href="/tem-quase-tudo/favoritos.php" class="header-action" title="Ver meus favoritos">
                    <span class="header-action-icon">❤️</span>
                    <span>Favoritos</span>
                    <?php if ($fav_count > 0): ?>
                        <span id="fav-count" class="fav-count"><?php echo $fav_count; ?></span>
                    <?php else: ?>
                        <span id="fav-count" class="fav-count">0</span>
                    <?php endif; ?>
                </a>
                <?php if (isset($_SESSION['cliente_id'])): ?>
                    <a href="/tem-quase-tudo/conta.php" class="header-action">
                        <span class="header-action-icon">👤</span>
                        <span>Conta</span>
                    </a>
                <?php else: ?>
                    <a href="/tem-quase-tudo/admin/login/login.php" class="header-action">
                        <span class="header-action-icon">👤</span>
                        <span>Conta</span>
                    </a>
                <?php endif; ?>
                <a href="/tem-quase-tudo/carrinho.php" class="header-action">
                    <span class="header-action-icon">🛒</span>
                    <span>Carrinho</span>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <div class="categories-bar">
        </div>
    </header>

    <main class="container">
