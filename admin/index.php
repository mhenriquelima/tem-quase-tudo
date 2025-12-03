<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tem Quase Tudo - Administração</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 40px;
            font-size: 32px;
        }
        
        .menu-list {
            list-style: none;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .menu-list li {
            border-bottom: 1px solid #eee;
        }
        
        .menu-list li:last-child {
            border-bottom: none;
        }
        
        .menu-list a {
            display: block;
            padding: 16px 20px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .menu-list li:hover a {
            background: #f8f9fa;
            color: #667eea;
            padding-left: 24px;
        }
        
        .menu-list a .icon {
            font-size: 20px;
        }
        
        .menu-list li.highlight:hover a {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge {
            display: inline-block;
            background: #ff6b6b;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Administração</h1>
        
        <ul class="menu-list">
            <li>
                <a href="database-setup.php">
                    <span class="icon">⚙️</span>
                    <span>Setup do Banco de Dados</span>
                </a>
            </li>
            <li>
                <a href="produtos/index.php">
                    <span class="icon">📦</span>
                    <span>Gerenciar Produtos</span>
                </a>
            </li>
            <li>
                <a href="clientes/index.php">
                    <span class="icon">👥</span>
                    <span>Gerenciar Clientes</span>
                </a>
            </li>
            <li>
                <a href="login/login.php">
                    <span class="icon">🔐</span>
                    <span>Login</span>
                </a>
            </li>
        </ul>
    </div>
</body>
</html>