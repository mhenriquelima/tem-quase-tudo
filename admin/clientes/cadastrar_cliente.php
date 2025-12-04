<?php
ob_start();
require_once __DIR__ . '/../config.inc.php';
ob_end_clean();

$errors = [];
$nome = '';
$email = '';
$telefone = '';
$endereco = '';
$cidade = '';
$senha = '';
 $senha_confirm = '';

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

	if ($senha === '') {
		$errors[] = 'O campo Senha é obrigatório.';
	} elseif (strlen($senha) < 6) {
		$errors[] = 'A senha deve ter ao menos 6 caracteres.';
	}
	if ($senha !== $senha_confirm) {
		$errors[] = 'A confirmação de senha não confere.';
	}

	if (empty($errors)) {
		if (!isset($conexao) || !$conexao) {
			$errors[] = 'Conexão com o banco não disponível.';
		} else {
			$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

			// Verifica se já existe um cliente com o mesmo email
			$check_sql = "SELECT id FROM clientes WHERE email = ? LIMIT 1";
			$check_stmt = mysqli_prepare($conexao, $check_sql);
			if ($check_stmt) {
				mysqli_stmt_bind_param($check_stmt, 's', $email);
				if (mysqli_stmt_execute($check_stmt)) {
					mysqli_stmt_store_result($check_stmt);
					if (mysqli_stmt_num_rows($check_stmt) > 0) {
						$errors[] = 'Já existe um cadastro com este e-mail.';
					}
				} else {
					$errors[] = 'Erro ao verificar e-mail existente: ' . mysqli_stmt_error($check_stmt);
				}
				mysqli_stmt_close($check_stmt);
			} else {
				$errors[] = 'Erro ao preparar verificação de e-mail: ' . mysqli_error($conexao);
			}

			if (empty($errors)) {
				$sql = "INSERT INTO clientes (nome, email, senha, telefone, endereco, cidade) VALUES (?, ?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($conexao, $sql);
			if ($stmt) {
				mysqli_stmt_bind_param($stmt, 'ssssss', $nome, $email, $senha_hash, $telefone, $endereco, $cidade);
				$exec = mysqli_stmt_execute($stmt);
				if ($exec) {
					$cliente_id = mysqli_insert_id($conexao);
					mysqli_stmt_close($stmt);
					
					// Login automático do cliente
					if (!isset($_SESSION)) {
						session_start();
					}
					$_SESSION['cliente_id'] = $cliente_id;
					$_SESSION['usuario_id'] = $cliente_id;
					$_SESSION['cliente_nome'] = $nome;
					
					// Redireciona para a home com mensagem de sucesso
					header('Location: /tem-quase-tudo/index.php?cadastro=ok');
					exit;
				} else {
					$errors[] = 'Falha ao inserir no banco: ' . mysqli_stmt_error($stmt);
					mysqli_stmt_close($stmt);
				}
			} else {
				$errors[] = 'Erro ao preparar a query: ' . mysqli_error($conexao);
			}
		}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Criar Conta - Tem Quase Tudo</title>
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
		<h1 class="admin-page-title">Criar Conta</h1>
		<p class="admin-page-subtitle">Preencha os dados para se cadastrar</p>

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
				<input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome) ?>" required placeholder="Seu nome completo">
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

			<div class="form-group-inline">
				<div class="form-group">
					<label for="senha">Senha <span>*</span></label>
					<input type="password" name="senha" id="senha" required placeholder="Mínimo 6 caracteres">
					<span class="form-help">Mínimo de 6 caracteres</span>
				</div>

				<div class="form-group">
					<label for="senha_confirm">Confirme a senha <span>*</span></label>
					<input type="password" name="senha_confirm" id="senha_confirm" required placeholder="Confirme sua senha">
				</div>
			</div>

			<div class="btn-group">
				<button type="submit" class="btn btn-primary btn-large">Cadastrar</button>
				<a href="index.php" class="btn btn-secondary btn-large">Cancelar</a>
			</div>
		</form>
	</div>
</div>

</body>
</html>

