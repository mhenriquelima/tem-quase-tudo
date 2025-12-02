<?php
// Arquivo para processar formulário de contato

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    
    // Validações
    $erros = [];
    
    if (empty($nome)) {
        $erros[] = "Nome é obrigatório";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email válido é obrigatório";
    }
    
    if (empty($assunto)) {
        $erros[] = "Assunto é obrigatório";
    }
    
    if (empty($mensagem) || strlen($mensagem) < 10) {
        $erros[] = "Mensagem deve ter pelo menos 10 caracteres";
    }
    
    if (empty($erros)) {
        // Email para enviar as mensagens
        $email_admin = "mhenriquedelima1@gmail.com";
        
        // Preparar conteúdo do email
        $assunto_email = "Nova Mensagem de Contato: " . $assunto;
        
        $corpo_email = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; background: #f5f5f5; padding: 20px; }
                .header { background: #FF9900; color: white; padding: 15px; border-radius: 5px; }
                .content { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; }
                .field { margin: 10px 0; }
                .label { font-weight: bold; color: #333; }
                .value { color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Nova Mensagem de Contato</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='label'>Nome:</span>
                        <span class='value'>" . htmlspecialchars($nome) . "</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>" . htmlspecialchars($email) . "</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Telefone:</span>
                        <span class='value'>" . htmlspecialchars($telefone ?: 'Não informado') . "</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Assunto:</span>
                        <span class='value'>" . htmlspecialchars($assunto) . "</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Mensagem:</span>
                        <span class='value'>" . nl2br(htmlspecialchars($mensagem)) . "</span>
                    </div>
                </div>
            </div>
        </body>
        </html>";
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . htmlspecialchars($email) . "\r\n";
        $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
        
        $sucesso = @mail($email_admin, $assunto_email, $corpo_email, $headers);
        
        $arquivo_log = __DIR__ . "/../contatos/contatos_recebidos.txt";
        $data_hora = date('d/m/Y H:i:s');
        $log_entry = "[$data_hora] Nome: $nome | Email: $email | Telefone: $telefone | Assunto: $assunto | Mensagem: " . str_replace("\n", " ", $mensagem) . "\n---\n";
        
        @file_put_contents($arquivo_log, $log_entry, FILE_APPEND);
        
        $_SESSION['mensagem_sucesso'] = "Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.";
        $_SESSION['form_limpo'] = true;
    } else {
        $_SESSION['erros_contato'] = $erros;
    }
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
