<?php
session_start();
$cart_count = count($_SESSION['carrinho'] ?? []);
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
                <a href="/tem-quase-tudo/">Sobre</a>
                <a href="/tem-quase-tudo/">Contato</a>
                <a href="/tem-quase-tudo/admin/">Admin</a>
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
                <a href="/tem-quase-tudo/" class="header-action">
                    <span class="header-action-icon">❤️</span>
                    <span>Favoritos</span>
                </a>
                <a href="/tem-quase-tudo/" class="header-action">
                    <span class="header-action-icon">👤</span>
                    <span>Conta</span>
                </a>
                <a href="/tem-quase-tudo/carrinho.php" class="header-action">
                    <span class="header-action-icon">🛒</span>
                    <span>Carrinho</span>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Barra de Categorias -->
        <div class="categories-bar">
            <a href="/tem-quase-tudo/" class="category-link">🏠 Início</a>
            <a href="/tem-quase-tudo/" class="category-link">📱 Eletrônicos</a>
            <a href="/tem-quase-tudo/" class="category-link">👔 Roupas</a>
            <a href="/tem-quase-tudo/" class="category-link">📚 Livros</a>
            <a href="/tem-quase-tudo/" class="category-link">🏠 Casa</a>
            <a href="/tem-quase-tudo/" class="category-link">🎮 Jogos</a>
            <a href="/tem-quase-tudo/" class="category-link">⚽ Esportes</a>
            <a href="/tem-quase-tudo/" class="category-link">🎨 Artes</a>
        </div>
    </header>

    <main class="container">
