<?php
    require_once("../config.inc.php");

    $errors = [];
    $sucesso = false;
    $produto = '';
    $descricao = '';
    $preco = '';
    $estoque = '';
    $desconto = '0';

    if (isset($_POST['cadastrar'])) {
        $produto = trim($_POST['produto'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $preco = trim($_POST['preco'] ?? '');
        $estoque = trim($_POST['estoque'] ?? '');
        $desconto = trim($_POST['desconto'] ?? '0');

        // Validação
        if ($produto === '') {
            $errors[] = 'O nome do produto é obrigatório.';
        }
        if ($preco === '' || !is_numeric($preco)) {
            $errors[] = 'O preço é obrigatório e deve ser um número.';
        }
        if ($estoque === '' || !is_numeric($estoque)) {
            $errors[] = 'O estoque é obrigatório e deve ser um número.';
        }

        if (empty($errors)) {
            $query = "INSERT INTO produtos (produto, descricao, preco, estoque, desconto) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ssdid', $produto, $descricao, $preco, $estoque, $desconto);
                if (mysqli_stmt_execute($stmt)) {
                    $sucesso = true;
                    $produto = '';
                    $descricao = '';
                    $preco = '';
                    $estoque = '';
                    $desconto = '0';
                } else {
                    $errors[] = 'Erro ao cadastrar produto: ' . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $errors[] = 'Erro ao preparar consulta: ' . mysqli_error($conexao);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto - Tem Quase Tudo</title>
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
        <h1 class="admin-page-title">Adicionar Produto</h1>
        <p class="admin-page-subtitle">Preencha os dados do novo produto</p>

        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <div class="alert-content">
                    <strong>Sucesso!</strong>
                    <p>Produto cadastrado com sucesso. <a href="listar_produtos.php" style="color: inherit; text-decoration: underline; font-weight: 600;">Ver produtos</a></p>
                </div>
            </div>
        <?php endif; ?>

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

        <form method="post" class="admin-form">
            <div class="form-group">
                <label for="produto">Nome do Produto <span>*</span></label>
                <input type="text" name="produto" id="produto" value="<?= htmlspecialchars($produto) ?>" required placeholder="Ex: Caneca Autopensante">
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" placeholder="Descrição detalhada do produto"><?= htmlspecialchars($descricao) ?></textarea>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="preco">Preço (R$) <span>*</span></label>
                    <input type="number" step="0.01" name="preco" id="preco" value="<?= htmlspecialchars($preco) ?>" required placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="estoque">Estoque (Unidades) <span>*</span></label>
                    <input type="number" name="estoque" id="estoque" value="<?= htmlspecialchars($estoque) ?>" required placeholder="0">
                </div>

                <div class="form-group">
                    <label for="desconto">Desconto (%)</label>
                    <input type="number" step="0.01" name="desconto" id="desconto" value="<?= htmlspecialchars($desconto) ?>" placeholder="0">
                    <span class="form-help">Deixe em branco ou 0 para sem desconto</span>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" name="cadastrar" class="btn btn-primary btn-large">Cadastrar Produto</button>
                <a href="index.php" class="btn btn-secondary btn-large">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>