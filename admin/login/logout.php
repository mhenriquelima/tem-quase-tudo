<?php
session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();

// Mostrar página de confirmação antes de redirecionar
header('Refresh: 2; url=/tem-quase-tudo/admin/login/login.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Saiu da Sessão</title>
	<link rel="stylesheet" href="/tem-quase-tudo/assets/styles.css">
</head>
<body class="admin-logout-page">
	<div class="admin-logout-card">
		<h2>👋 Você saiu da sessão</h2>
		<p>Obrigado por usar o Tem Quase Tudo! Você será redirecionado para a página de login em instantes.</p>
		<a href="/tem-quase-tudo/admin/login/login.php">Ir para Login Agora</a>
	</div>
</body>
</html>
<?php
exit;
