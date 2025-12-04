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
    <div class="admin-main" style="max-width: 700px;">
        <h1 class="admin-page-title">⚙️ Setup do Banco de Dados</h1>
        <p class="admin-page-subtitle">Configure e popule o banco de dados do sistema</p>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $type === 'success' ? 'success' : 'error'; ?>" style="margin-top: 20px;">
                <span class="alert-icon"><?php echo $type === 'success' ? '✓' : '⚠️'; ?></span>
                <div class="alert-content">
                    <strong><?php echo $type === 'success' ? 'Sucesso!' : 'Erro!'; ?></strong>
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (empty($_POST)): ?>
            <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); padding: 16px; border-radius: 6px; margin-top: 20px; margin-bottom: 20px;">
                <p style="margin-bottom: 12px; font-weight: 600; color: var(--dark-gray);">O que será feito:</p>
                <ul style="margin-left: 20px; color: var(--text-gray); line-height: 1.8;">
                    <li>✓ Criar banco de dados (se não existir)</li>
                    <li>✓ Criar tabelas (clientes, produtos, carrinho)</li>
                    <li>✓ Inserir produtos de exemplo</li>
                    <li>✓ Inserir clientes de exemplo</li>
                </ul>
            </div>
            
            <div style="background: #FEF3C7; border: 1px solid #FCD34D; border-left: 4px solid var(--warning-yellow); padding: 16px; border-radius: 6px; margin-bottom: 20px;">
                <p style="margin: 0; color: #78350F;"><strong>⚠️ Atenção:</strong> Esta ação irá <strong>sobrescrever</strong> todos os dados existentes no banco de dados. Certifique-se de ter um backup antes de continuar!</p>
            </div>
            
            <form method="POST" class="admin-form">
                <input type="hidden" name="action" value="setup_database">
                <button type="submit" class="btn btn-primary btn-large" onclick="return confirm('Tem certeza que deseja popular o banco de dados? Todos os dados existentes serão perdidos.');">
                    🚀 Popular Banco de Dados
                </button>
            </form>
            
            <div style="background: var(--light-gray); border: 1px solid var(--border-gray); border-radius: 6px; padding: 20px; margin-top: 30px;">
                <h3 style="color: var(--dark-gray); margin-bottom: 12px; font-size: 16px;">📋 Alternativas:</h3>
                <ol style="margin-left: 20px; color: var(--text-gray); line-height: 1.8;">
                    <li style="margin-bottom: 10px;">
                        <strong>Usar phpMyAdmin:</strong><br>
                        Acesse <a href="http://localhost/phpmyadmin" target="_blank" style="color: var(--primary-orange); text-decoration: underline;">http://localhost/phpmyadmin</a>, vá para a aba SQL e importe o arquivo <code style="background: var(--white); padding: 2px 6px; border-radius: 3px; font-family: monospace;">scripts/init_db.sql</code>
                    </li>
                    <li>
                        <strong>Usar MySQL CLI (PowerShell):</strong><br>
                        <code style="background: var(--white); padding: 2px 6px; border-radius: 3px; font-family: monospace; display: block; margin-top: 8px;">mysql -u root &lt; .\scripts\init_db.sql</code>
                    </li>
                </ol>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-gray); text-align: center;">
            <a href="index.php" class="btn btn-secondary">← Voltar ao Painel Admin</a>
        </div>
    </div>
</div>

</body>
</html>
