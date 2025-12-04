<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// Se o usuário já "pagou", limpa o carrinho e redireciona
if (isset($_POST['finalizar'])) {
    include __DIR__ . '/includes/db.php';
    
    // Debug: mostrar erros
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Verifica se o usuário está logado
    if (!isset($_SESSION['cliente_id'])) {
        die('Erro: Usuário não logado. cliente_id não está na sessão.');
    }
    
    $cliente_id = $_SESSION['cliente_id'];
    $carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
    
    if (empty($carrinho)) {
        die('Erro: Carrinho vazio.');
    }
    
    // Verifica conexão
    if (!$conexao) {
        die('Erro: Falha na conexão com o banco de dados.');
    }
    
    // Cria o pedido
    $stmt = $conexao->prepare("INSERT INTO pedidos (cliente_id, data_pedido, status) VALUES (?, NOW(), 'Pendente')");
    if (!$stmt) {
        die('Erro ao preparar statement de pedido: ' . $conexao->error);
    }
    
    $stmt->bind_param('i', $cliente_id);
    if (!$stmt->execute()) {
        die('Erro ao executar insert de pedido: ' . $stmt->error);
    }
    
    $pedido_id = $stmt->insert_id;
    $stmt->close();
    
    if (!$pedido_id) {
        die('Erro: Pedido não foi criado.');
    }
    
    // Insere os itens do pedido
    $stmt_item = $conexao->prepare("INSERT INTO pedidos_item (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    if (!$stmt_item) {
        die('Erro ao preparar statement de item: ' . $conexao->error);
    }
    
    foreach ($carrinho as $item) {
        $produto_id = $item['id'];
        $quantidade = $item['quantidade'];
        $preco = $item['preco'];
        
        $stmt_item->bind_param('iiid', $pedido_id, $produto_id, $quantidade, $preco);
        if (!$stmt_item->execute()) {
            die('Erro ao inserir item do pedido: ' . $stmt_item->error);
        }
    }
    $stmt_item->close();
    
    unset($_SESSION['carrinho']);
    header('Location: /tem-quase-tudo/index.php?compra=ok');
    exit;
}
include __DIR__ . '/includes/header.php';
?>

<h1 style="margin-bottom: 30px; font-size: 32px;">💳 Pagamento Fictício</h1>
<div style="background-color: #F5F5F5; padding: 40px; border-radius: 8px; text-align: center; max-width: 500px; margin: auto;">
    <p style="font-size: 18px; color: #666;">Simule o pagamento da sua compra. Nenhum dado real será processado.</p>
    <form method="post">
        <div style="margin-bottom: 20px;">
            <input type="text" name="nome" placeholder="Nome no cartão" required style="padding: 10px; width: 80%; border-radius: 4px; border: 1px solid #DDD;">
        </div>
        <div style="margin-bottom: 20px;">
            <input type="text" name="cartao" placeholder="Número do cartão" required maxlength="16" style="padding: 10px; width: 80%; border-radius: 4px; border: 1px solid #DDD;">
        </div>
        <div style="margin-bottom: 20px;">
            <input type="text" name="validade" placeholder="Validade (MM/AA)" required maxlength="5" style="padding: 10px; width: 80%; border-radius: 4px; border: 1px solid #DDD;">
        </div>
        <div style="margin-bottom: 20px;">
            <input type="text" name="cvv" placeholder="CVV" required maxlength="3" style="padding: 10px; width: 80%; border-radius: 4px; border: 1px solid #DDD;">
        </div>
        <button type="submit" name="finalizar" value="1" style="background-color: #27ae60; color: white; border: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; cursor: pointer;">✓ Finalizar Compra</button>
    </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
