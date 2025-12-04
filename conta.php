<?php
// Página de Conta do Usuário
ob_start();
require_once __DIR__ . '/admin/config.inc.php';
ob_end_clean();

require_once __DIR__ . '/includes/header.php';

if (!isset($_SESSION['cliente_id'])) {
    header('Location: /tem-quase-tudo/admin/login/login.php');
    exit;
}

$cliente_id = (int) $_SESSION['cliente_id'];
$errors = [];
$cliente = null;
$pedidos = [];

if (!isset($conexao) || !$conexao) {
    $errors[] = 'Conexão com o banco não disponível.';
} else {
    // Buscar dados do cliente
    $sql = "SELECT id, nome, email, telefone, endereco, cidade FROM clientes WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conexao, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $cliente_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $nome, $email, $telefone, $endereco, $cidade);
        if (mysqli_stmt_fetch($stmt)) {
            $cliente = compact('id','nome','email','telefone','endereco','cidade');
        }
        mysqli_stmt_close($stmt);
    } else {
        $errors[] = 'Erro ao preparar consulta: ' . mysqli_error($conexao);
    }

    // Tentar buscar histórico de pedidos (se existir tabela 'pedidos')
    $sql2 = "SELECT id, data_pedido, total FROM pedidos WHERE cliente_id = ? ORDER BY data_pedido DESC LIMIT 20";
    $stmt2 = @mysqli_prepare($conexao, $sql2);
    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, 'i', $cliente_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $pid, $pdata, $ptotal);
        while (mysqli_stmt_fetch($stmt2)) {
            $pedidos[] = ['id' => $pid, 'data' => $pdata, 'total' => $ptotal];
        }
        mysqli_stmt_close($stmt2);
    }
}
?>

<section class="account-box" style="max-width:900px;margin:24px auto;padding:16px;background:#fff;border:1px solid #eee;border-radius:6px">
    <h1>Minha Conta</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <div><?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($cliente): ?>
        <div style="display:flex;gap:24px;flex-wrap:wrap">
            <div style="flex:1;min-width:280px">
                <h2>Informações Pessoais</h2>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($cliente['nome']); ?></p>
                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($cliente['email']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($cliente['telefone']); ?></p>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($cliente['endereco']); ?></p>
                <p><strong>Cidade:</strong> <?php echo htmlspecialchars($cliente['cidade']); ?></p>
                <p style="margin-top:12px"><a href="/tem-quase-tudo/admin/clientes/editar_cliente.php?id=<?php echo $cliente['id']; ?>">Editar informações</a></p>
            </div>

            <div style="flex:1;min-width:280px">
                <h2>Preferências</h2>
                <p>Notificações por e-mail: <strong>Sim</strong></p>
                <p>Newsletter: <strong>Inscrito</strong></p>
                <p><a href="/tem-quase-tudo/admin/login/logout.php">Sair</a></p>
            </div>
        </div>

        <hr style="margin:18px 0">

        <h2>Histórico de Pedidos</h2>
        <?php if (!empty($pedidos)): ?>
            <table border="0" cellpadding="8" style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#f7f7f7">
                        <th>Data</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['data']); ?></td>
                        <td>R$ <?php echo number_format($p['total'],2,',','.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Sem pedidos registrados para sua conta.</p>
            <p>Quando você realizar compras, elas aparecerão aqui.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Dados do cliente não encontrados.</p>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
