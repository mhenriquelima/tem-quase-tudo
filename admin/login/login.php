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
						header('Location: /tem-quase-tudo/index.php');
						exit;
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
	<title>Login - Administração</title>
	<style>
		body{font-family: Arial, sans-serif; padding:20px}
		.box{max-width:420px;margin:40px auto;padding:16px;border:1px solid #ddd;border-radius:6px}
		label{display:block;margin-top:8px}
		input[type="email"],input[type="password"]{width:100%;padding:8px;margin-top:4px}
		.error{color:#a00;margin-bottom:10px}
	</style>
</head>
<body>
	<div class="box">
		<h2>Login de Cliente (Admin)</h2>
		<?php if ($error): ?>
			<div class="error"><?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>

		<form method="post" action="">
			<label for="email">E-mail</label>
			<input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

			<label for="senha">Senha</label>
			<input type="password" id="senha" name="senha" required>

			<div style="margin-top:12px">
				<button type="submit">Entrar</button>
				<a href="/tem-quase-tudo/">Voltar ao site</a>
				<a href="/tem-quase-tudo/admin/clientes/cadastrar_cliente.php" style="margin-left:12px">Ainda não tem conta? Cadastre-se</a>
			</div>
		</form>
	</div>
</body>
</html>
