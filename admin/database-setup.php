<?php
session_start();

$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'setup_database') {
    try {
        $mysqli = new mysqli("localhost", "root", "");
        
        if ($mysqli->connect_errno) {
            throw new Exception("Falha ao conectar: " . $mysqli->connect_error);
        }
        
        $sql_file = __DIR__ . '/../scripts/init_db.sql';
        if (!file_exists($sql_file)) {
            throw new Exception("Arquivo init_db.sql não encontrado em: " . $sql_file);
        }
        
        $sql_content = file_get_contents($sql_file);
        
        $queries = array_filter(
            array_map('trim', explode(';', $sql_content)),
            function($q) { return !empty($q) && strpos($q, '--') !== 0; }
        );
        
        $executed = 0;
        foreach ($queries as $query) {
            $query = preg_replace('/--.*$/m', '', $query);
            $query = trim($query);
            
            if (!empty($query)) {
                if (!$mysqli->query($query)) {
                    throw new Exception("Erro ao executar query: " . $mysqli->error . "\n Query: " . substr($query, 0, 100) . "...");
                }
                $executed++;
            }
        }
        
        $mysqli->close();
        
        $message = "✅ Banco de dados populado com sucesso! ($executed comandos executados)";
        $type = 'success';
        
    } catch (Exception $e) {
        $message = "❌ Erro: " . $e->getMessage();
        $type = 'error';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup do Banco de Dados - Tem Quase Tudo Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        
        .info-box strong {
            color: #667eea;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        
        .message.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .message.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-secondary {
            background: #e9ecef;
            color: #333;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-secondary:hover {
            background: #dee2e6;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .warning strong {
            color: #856404;
        }
        
        .steps {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .steps h3 {
            color: #333;
            margin-bottom: 12px;
            font-size: 16px;
        }
        
        .steps ol {
            margin-left: 20px;
            color: #666;
            line-height: 1.8;
        }
        
        .steps li {
            margin-bottom: 8px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        
        a {
            color: #667eea;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Setup do Banco de Dados</h1>
        <p class="subtitle">Tem Quase Tudo - Administração</p>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($_POST)): ?>
            <div class="info-box">
                <strong>O que será feito:</strong><br>
                ✓ Criar banco de dados (se não existir)<br>
                ✓ Criar tabelas (clientes, produtos, carrinho)<br>
                ✓ Inserir produtos de exemplo (15 itens)<br>
                ✓ Inserir clientes de exemplo (5 registros)
            </div>
            
            <div class="warning">
                <strong>⚠️ Atenção:</strong> Esta ação irá <strong>sobrescrever</strong> todos os dados existentes no banco de dados. Certifique-se de ter um backup antes de continuar!
            </div>
            
            <form method="POST">
                <input type="hidden" name="action" value="setup_database">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Tem certeza que deseja popular o banco de dados? Todos os dados existentes serão perdidos.');">
                    🚀 Popular Banco de Dados
                </button>
            </form>
            
            <div class="steps">
                <h3>📋 Alternativas:</h3>
                <ol>
                    <li><strong>Usar phpMyAdmin:</strong> Acesse <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a>, vá para a aba SQL e importe o arquivo <code>scripts/init_db.sql</code></li>
                    <li><strong>Usar MySQL CLI (PowerShell):</strong><br>
                        <code>mysql -u root < .\scripts\init_db.sql</code>
                    </li>
                </ol>
            </div>
        <?php endif; ?>
        
        <div class="footer">
            <a href="index.php">← Voltar ao Admin</a>
        </div>
    </div>
</body>
</html>
