<?php
ob_start();
require_once __DIR__ . '/../config.inc.php';
ob_end_clean();

require_once __DIR__ . '/../../includes/header.php';

if (!isset($_SESSION['cliente_id'])) {
    header('Location: /tem-quase-tudo/admin/login/login.php');
    exit;
}

$cliente_id = (int) $_SESSION['cliente_id'];
$pedido_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];
$pedido = null;
$itens_pedido = [];

if ($pedido_id <= 0) {
    $errors[] = 'ID do pedido inválido.';
} elseif (!isset($conexao) || !$conexao) {
    $errors[] = 'Conexão com o banco não disponível.';
} else {
    $sql = "SELECT id, cliente_id, data_pedido, total, status, endereco_entrega FROM pedidos WHERE id = ? AND cliente_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conexao, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $pedido_id, $cliente_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $cid, $data, $total, $status, $endereco);
        if (mysqli_stmt_fetch($stmt)) {
            $pedido = [
                'id' => $id,
                'cliente_id' => $cid,
                'data' => $data,
                'total' => $total,
                'status' => $status,
                'endereco' => $endereco
            ];
        } else {
            $errors[] = 'Pedido não encontrado ou você não tem permissão para acessá-lo.';
        }
        mysqli_stmt_close($stmt);
    } else {
        $errors[] = 'Erro ao preparar consulta: ' . mysqli_error($conexao);
    }

    if ($pedido) {
        $sql2 = "SELECT id, pedido_id, produto_id, quantidade, preco_unitario, subtotal FROM pedido_itens WHERE pedido_id = ?";
        $stmt2 = @mysqli_prepare($conexao, $sql2);
        if ($stmt2) {
            mysqli_stmt_bind_param($stmt2, 'i', $pedido_id);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_bind_result($stmt2, $item_id, $item_pedido_id, $produto_id, $quantidade, $preco_unitario, $subtotal);
            while (mysqli_stmt_fetch($stmt2)) {
                $itens_pedido[] = [
                    'id' => $item_id,
                    'produto_id' => $produto_id,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $preco_unitario,
                    'subtotal' => $subtotal
                ];
            }
            mysqli_stmt_close($stmt2);
        }
    }
}

$status_labels = [
    'pendente' => 'Pendente',
    'confirmado' => 'Confirmado',
    'em_preparacao' => 'Em Preparação',
    'enviado' => 'Enviado',
    'entregue' => 'Entregue',
    'cancelado' => 'Cancelado'
];

function get_status_color($status) {
    $colors = [
        'pendente' => '#ff9800',
        'confirmado' => '#2196f3',
        'em_preparacao' => '#673ab7',
        'enviado' => '#00bcd4',
        'entregue' => '#4caf50',
        'cancelado' => '#f44336'
    ];
    return $colors[$status] ?? '#999';
}
?>

<section class="order-detail-box" style="max-width:900px;margin:24px auto;padding:16px;background:#fff;border:1px solid #eee;border-radius:6px">
    <h1>Detalhes do Pedido</h1>

    <?php if (!empty($errors)): ?>
        <div class="error" style="background:#fee;border:1px solid #f88;color:#c33;padding:12px;border-radius:4px;margin-bottom:16px">
            <?php foreach ($errors as $e): ?>
                <div><?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($pedido): ?>
        <div style="background:#f9f9f9;padding:16px;border-radius:4px;margin-bottom:24px">
            <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:16px">
                <div>
                    <p style="margin:0 0 8px 0;font-size:14px;color:#666"><strong>Pedido ID:</strong></p>
                    <p style="margin:0;font-size:18px;font-weight:bold">#<?php echo htmlspecialchars($pedido['id']); ?></p>
                </div>

                <div>
                    <p style="margin:0 0 8px 0;font-size:14px;color:#666"><strong>Data:</strong></p>
                    <p style="margin:0;font-size:16px">
                        <?php 
                            $data_obj = DateTime::createFromFormat('Y-m-d H:i:s', $pedido['data']) ?: DateTime::createFromFormat('Y-m-d', $pedido['data']);
                            echo $data_obj ? $data_obj->format('d/m/Y H:i') : htmlspecialchars($pedido['data']); 
                        ?>
                    </p>
                </div>

                <div>
                    <p style="margin:0 0 8px 0;font-size:14px;color:#666"><strong>Status:</strong></p>
                    <p style="margin:0;padding:6px 12px;background:<?php echo get_status_color($pedido['status']); ?>;color:#fff;border-radius:4px;display:inline-block">
                        <?php echo htmlspecialchars($status_labels[$pedido['status']] ?? ucfirst(str_replace('_', ' ', $pedido['status']))); ?>
                    </p>
                </div>

                <div style="text-align:right">
                    <p style="margin:0 0 8px 0;font-size:14px;color:#666"><strong>Total:</strong></p>
                    <p style="margin:0;font-size:20px;font-weight:bold;color:#2ecc71">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
                </div>
            </div>
        </div>

        <h2 style="border-bottom:2px solid #eee;padding-bottom:12px">Itens do Pedido</h2>
        
        <?php if (!empty($itens_pedido)): ?>
            <table border="0" cellpadding="12" style="width:100%;border-collapse:collapse;margin-bottom:24px">
                <thead>
                    <tr style="background:#f7f7f7;border-bottom:2px solid #ddd">
                        <th style="text-align:left">Produto ID</th>
                        <th style="text-align:center">Quantidade</th>
                        <th style="text-align:right">Preço Unitário</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($itens_pedido as $item): ?>
                    <tr style="border-bottom:1px solid #eee">
                        <td><?php echo htmlspecialchars($item['produto_id']); ?></td>
                        <td style="text-align:center"><?php echo htmlspecialchars($item['quantidade']); ?></td>
                        <td style="text-align:right">R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                        <td style="text-align:right;font-weight:bold">R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="border-top:2px solid #ddd;background:#f9f9f9;font-weight:bold">
                        <td colspan="3" style="text-align:right">Total:</td>
                        <td style="text-align:right;font-size:16px">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p style="color:#999;font-style:italic">Este pedido não possui itens registrados.</p>
        <?php endif; ?>

        <h2 style="border-bottom:2px solid #eee;padding-bottom:12px;margin-top:24px">Endereço de Entrega</h2>
        <div style="background:#f9f9f9;padding:16px;border-radius:4px;margin-bottom:24px">
            <p style="margin:0">
                <?php echo nl2br(htmlspecialchars($pedido['endereco'] ?? 'Não informado')); ?>
            </p>
        </div>

        <div style="text-align:center;margin-top:24px">
            <a href="/tem-quase-tudo/conta.php" style="display:inline-block;padding:10px 20px;background:#2196f3;color:#fff;text-decoration:none;border-radius:4px">Voltar ao Histórico</a>
        </div>

    <?php else: ?>
        <p style="color:#999">Pedido não encontrado.</p>
        <p><a href="/tem-quase-tudo/conta.php">Voltar ao Histórico</a></p>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
