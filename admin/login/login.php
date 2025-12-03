<?php
ob_start();
require_once __DIR__ . '/../../admin/config.inc.php';
ob_end_clean();

session_start();

$error = '';

if (isset($_SESSION['cliente_id'])) {
		header('Location: /tem-quase-tudo/index.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = trim($_POST['email'] ?? '');
	$senha = $_POST['senha'] ?? '';

	if ($email === '' || $senha === '') {
		$error = 'Informe e-mail e senha.';
	} else {
		if (!isset($conexao) || !$conexao) {
			$error = 'Conexão com o banco não disponível.';
		} else {
			$sql = "SELECT id, nome, email, senha FROM clientes WHERE email = ? LIMIT 1";
			$stmt = mysqli_prepare($conexao, $sql);
			if ($stmt) {
				mysqli_stmt_bind_param($stmt, 's', $email);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $id, $nome, $email_db, $senha_hash);
				if (mysqli_stmt_fetch($stmt)) {
					mysqli_stmt_close($stmt);
					if (password_verify($senha, $senha_hash)) {
						session_regenerate_id(true);
						$_SESSION['cliente_id'] = $id;
						$_SESSION['cliente_nome'] = $nome;
						// Marcar usuário como administrador somente se o email bater com ADMIN_EMAIL
						if (defined('ADMIN_EMAIL') && strtolower($email) === strtolower(ADMIN_EMAIL)) {
							$_SESSION['is_admin'] = true;
							header('Location: /tem-quase-tudo/admin/index.php');
							exit;
						} else {
							// Não é admin - garantir flag falsa
							unset($_SESSION['is_admin']);
							header('Location: /tem-quase-tudo/index.php');
							exit;
						}
					} else {
						$error = 'E-mail ou senha inválidos.';
					}
				} else {
					mysqli_stmt_close($stmt);
					$error = 'E-mail ou senha inválidos.';
				}
			} else {
				$error = 'Erro ao preparar consulta: ' . mysqli_error($conexao);
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
	<title>Login - Administração</title>
	<link rel="stylesheet" href="/tem-quase-tudo/assets/styles.css">
</head>
<body class="admin-login-page">
	<div class="admin-login-box">
		<h2>🔐 Login</h2>
		<?php if ($error): ?>
			<div class="admin-login-error"><?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>

		<form method="post" action="" class="admin-login-form">
			<label for="email">E-mail</label>
			<input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="seu.email@exemplo.com">

			<label for="senha">Senha</label>
			<input type="password" id="senha" name="senha" required placeholder="Sua senha">

			<div class="admin-login-actions">
				<button type="submit">Entrar</button>
				<a href="/tem-quase-tudo/">← Voltar ao site</a>
				<a href="/tem-quase-tudo/admin/clientes/cadastrar_cliente.php">Cadastre-se</a>
			</div>
		</form>
	</div>
</body>
</html>
