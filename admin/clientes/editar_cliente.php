<?php
require_once __DIR__ . '/../config.inc.php';

$errors = [];
$id = $_GET['id'] ?? null;

if (empty($id) || !ctype_digit((string)$id)) {
    header('Location: listar_cliente.php?error=invalid_id');
    exit;
}

$id = (int) $id;

$nome = '';
$email = '';
$telefone = '';
$endereco = '';
$cidade = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $senha_confirm = $_POST['senha_confirm'] ?? '';

    if ($nome === '') {
        $errors[] = 'O campo Nome é obrigatório.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Informe um e-mail válido.';
    }

    if ($senha !== '') {
        if (strlen($senha) < 6) {
            $errors[] = 'A senha deve ter ao menos 6 caracteres.';
        }
        if ($senha !== $senha_confirm) {
            $errors[] = 'A confirmação de senha não confere.';
        }
    }

    if (empty($errors)) {
        if ($senha !== '') {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE clientes SET nome = ?, email = ?, senha = ?, telefone = ?, endereco = ?, cidade = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ssssssi', $nome, $email, $senha_hash, $telefone, $endereco, $cidade, $id);
            }
        } else {
            $sql = "UPDATE clientes SET nome = ?, email = ?, telefone = ?, endereco = ?, cidade = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'sssssi', $nome, $email, $telefone, $endereco, $cidade, $id);
            }
        }

        if (!isset($stmt) || !$stmt) {
            $errors[] = 'Erro ao preparar a query: ' . mysqli_error($conexao);
        } else {
            $exec = mysqli_stmt_execute($stmt);
            if ($exec) {
                mysqli_stmt_close($stmt);
                header('Location: listar_cliente.php?msg=updated');
                exit;
            } else {
                $errors[] = 'Falha ao atualizar: ' . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

} else {
    $sql = "SELECT id, nome, email, telefone, endereco, cidade FROM clientes WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        if ($row) {
            $nome = $row['nome'];
            $email = $row['email'];
            $telefone = $row['telefone'];
            $endereco = $row['endereco'];
            $cidade = $row['cidade'];
        } else {
            header('Location: listar_cliente.php?error=not_found');
            exit;
        }
    } else {
        header('Location: listar_cliente.php?error=db_prepare');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Tem Quase Tudo</title>
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
    <div class="admin-main" style="max-width: 600px;">
        <h1 class="admin-page-title">Editar Cliente</h1>
        <p class="admin-page-subtitle">Atualize os dados do cliente</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <div class="alert-content">
                    <strong>Erros no formulário:</strong>
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="admin-form">
            <div class="form-group">
                <label for="nome">Nome <span>*</span></label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome) ?>" required placeholder="Nome completo">
            </div>

            <div class="form-group">
                <label for="email">E-mail <span>*</span></label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required placeholder="seu.email@exemplo.com">
            </div>

            <div class="form-group-inline">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" name="telefone" id="telefone" value="<?= htmlspecialchars($telefone) ?>" placeholder="(11) 98765-4321">
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($cidade) ?>" placeholder="São Paulo">
                </div>
            </div>

            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($endereco) ?>" placeholder="Rua, número, complemento">
            </div>

            <div style="background-color: var(--light-gray); padding: 14px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; color: var(--text-gray);">
                <strong>Deixe os campos de senha em branco para manter a senha atual.</strong>
            </div>

            <div class="form-group-inline">
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Nova senha (opcional)">
                    <span class="form-help">Mínimo de 6 caracteres</span>
                </div>

                <div class="form-group">
                    <label for="senha_confirm">Confirme a senha</label>
                    <input type="password" name="senha_confirm" id="senha_confirm" placeholder="Confirme a nova senha">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary btn-large">💾 Salvar Alterações</button>
                <a href="listar_cliente.php" class="btn btn-secondary btn-large">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
