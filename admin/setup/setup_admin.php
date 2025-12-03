<?php
/**
 * Script de Setup do Administrador
 * Acesse http://localhost/tem-quase-tudo/admin/setup/setup_admin.php
 * 
 * Este script cria ou atualiza a conta do administrador no banco de dados
 */

require_once __DIR__ . '/../config.inc.php';

$mensagem = '';
$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    
    // Validações
    if (empty($email)) {
        $erro = "Email não pode estar vazio";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido";
    } elseif (empty($nome)) {
        $erro = "Nome não pode estar vazio";
    } elseif (empty($senha)) {
        $erro = "Senha não pode estar vazia";
    } elseif ($senha !== $confirma_senha) {
        $erro = "As senhas não coincidem";
    } elseif (strlen($senha) < 6) {
        $erro = "Senha deve ter pelo menos 6 caracteres";
    } else {
        // Verificar conexão com banco
        if (!isset($conexao) || !$conexao) {
            $erro = "Conexão com banco de dados não disponível";
        } else {
            // Verificar se o email já existe
            $sql_check = "SELECT id FROM clientes WHERE email = ? LIMIT 1";
            $stmt_check = mysqli_prepare($conexao, $sql_check);
            
            if ($stmt_check) {
                mysqli_stmt_bind_param($stmt_check, 's', $email);
                mysqli_stmt_execute($stmt_check);
                mysqli_stmt_bind_result($stmt_check, $cliente_id);
                $existe = mysqli_stmt_fetch($stmt_check);
                mysqli_stmt_close($stmt_check);
                
                if ($existe) {
                    // Atualizar usuário existente
                    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);
                    $sql_update = "UPDATE clientes SET nome = ?, senha = ? WHERE email = ? LIMIT 1";
                    $stmt_update = mysqli_prepare($conexao, $sql_update);
                    
                    if ($stmt_update) {
                        mysqli_stmt_bind_param($stmt_update, 'sss', $nome, $hash_senha, $email);
                        if (mysqli_stmt_execute($stmt_update)) {
                            $sucesso = true;
                            $mensagem = "✓ Administrador '$nome' atualizado com sucesso!";
                        } else {
                            $erro = "Erro ao atualizar: " . mysqli_error($conexao);
                        }
                        mysqli_stmt_close($stmt_update);
                    } else {
                        $erro = "Erro ao preparar UPDATE: " . mysqli_error($conexao);
                    }
                } else {
                    // Inserir novo usuário
                    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);
                    $sql_insert = "INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)";
                    $stmt_insert = mysqli_prepare($conexao, $sql_insert);
                    
                    if ($stmt_insert) {
                        mysqli_stmt_bind_param($stmt_insert, 'sss', $nome, $email, $hash_senha);
                        if (mysqli_stmt_execute($stmt_insert)) {
                            $sucesso = true;
                            $mensagem = "✓ Administrador '$nome' criado com sucesso! Email: $email";
                        } else {
                            $erro = "Erro ao inserir: " . mysqli_error($conexao);
                        }
                        mysqli_stmt_close($stmt_insert);
                    } else {
                        $erro = "Erro ao preparar INSERT: " . mysqli_error($conexao);
                    }
                }
            } else {
                $erro = "Erro ao preparar consulta: " . mysqli_error($conexao);
            }
        }
    }
}

$admin_email = defined('ADMIN_EMAIL') ? constant('ADMIN_EMAIL') : 'admin@temquasetudo.com';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup do Administrador</title>
</head>
<body>
    <div class="container">
        <h1>⚙️ Setup do Administrador</h1>
        <p class="subtitle">Configure a conta de administrador do seu e-commerce</p>
        
        <div class="admin-email-info">
            <strong>Email Admin Configurado:</strong>
            <div class="admin-email-value"><?php echo htmlspecialchars($admin_email); ?></div>
        </div>
        
        <?php if ($erro): ?>
            <div class="alert alert-error">
                <strong>❌ Erro:</strong> <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <strong>✓ Sucesso!</strong> <?php echo htmlspecialchars($mensagem); ?><br><br>
                Você pode agora fazer <a href="/tem-quase-tudo/admin/login/login.php" style="color: #3c3; font-weight: bold;">login com suas credenciais</a>.
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: João Silva" required 
                           value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="admin@temquasetudo.com" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : $admin_email; ?>">
                    <div class="password-hint">💡 É necessário utilizar este email na criação</div>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite uma senha segura" required>
                    <div class="password-hint">Mínimo 6 caracteres</div>
                </div>
                
                <div class="form-group">
                    <label for="confirma_senha">Confirmar Senha:</label>
                    <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Repita a senha" required>
                </div>
                
                <button type="submit">🔒 Criar/Atualizar Administrador</button>
            </form>
            
            <div class="info-box">
                <strong>ℹ️ Informações Importantes:</strong>
                <ul style="margin-left: 15px;">
                    <li>Este formulário cria ou atualiza um usuário admin</li>
                    <li>A senha será criptografada com segurança</li>
                    <li>Use o email configurado para acesso à área administrativa</li>
                    <li>Após criar o admin, acesse <code>/admin/login/login.php</code></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
