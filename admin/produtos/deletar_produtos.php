<?php
require_once("../config.inc.php");

$sucesso = false;
$erro = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "DELETE FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (mysqli_stmt_execute($stmt)) {
            $sucesso = true;
        } else {
            $erro = 'Erro ao deletar produto: ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $erro = 'Erro ao preparar query: ' . mysqli_error($conexao);
    }
} else {
    $erro = 'ID do produto inválido';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Produto - Tem Quase Tudo</title>
    <link rel="stylesheet" href="/tem-quase-tudo/assets/admin.css">
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000);
        });
    </script>
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
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <div class="alert-content">
                    <strong>Sucesso!</strong>
                    <p>Produto deletado com sucesso. Redirecionando em 2 segundos...</p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <div class="alert-content">
                    <strong>Erro!</strong>
                    <p><?= htmlspecialchars($erro) ?></p>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <a href="index.php" class="btn btn-secondary">← Voltar</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>