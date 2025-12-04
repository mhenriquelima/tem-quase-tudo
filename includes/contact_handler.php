<?php
include 'db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function sanitizar_entrada($entrada) {
    $entrada = trim($entrada);
    $entrada = strip_tags($entrada);
    $entrada = str_replace(chr(0), '', $entrada);
    $entrada = str_replace(["\r\n", "\r", "\n"], ' ', $entrada);
    return $entrada;
}

function validar_conteudo_malicioso($texto) {
    $padroes_maliciosos = [
        '/<script[^>]*>.*?<\/script>/i',  // Scripts
        '/<iframe[^>]*>.*?<\/iframe>/i',  // iframes
        '/javascript:/i',                   // javascript:
        '/onerror=/i',                      // onerror
        '/onclick=/i',                      // onclick
        '/onload=/i',                       // onload
        '/eval\(/i',                        // eval
        '/base64_decode/i',                 // base64
        '/sql|union|select|insert|update|delete|drop|create|alter/i'  // SQL injection
    ];
    
    foreach ($padroes_maliciosos as $padrao) {
        if (preg_match($padrao, $texto)) {
            return false;
        }
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitizar_entrada($_POST['nome'] ?? '');
    $email = sanitizar_entrada($_POST['email'] ?? '');
    $telefone = sanitizar_entrada($_POST['telefone'] ?? '');
    $assunto = sanitizar_entrada($_POST['assunto'] ?? '');
    $mensagem = sanitizar_entrada($_POST['mensagem'] ?? '');
    
    $erros = [];
    
    if (empty($nome)) {
        $erros[] = "Nome é obrigatório";
    } elseif (strlen($nome) > 255) {
        $erros[] = "Nome não pode exceder 255 caracteres";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email válido é obrigatório";
    }
    
    if (empty($assunto)) {
        $erros[] = "Assunto é obrigatório";
    } elseif (strlen($assunto) > 255) {
        $erros[] = "Assunto não pode exceder 255 caracteres";
    }
    
    if (empty($mensagem) || strlen($mensagem) < 10) {
        $erros[] = "Mensagem deve ter pelo menos 10 caracteres";
    }
    
    if (!validar_conteudo_malicioso($nome) || !validar_conteudo_malicioso($assunto) || !validar_conteudo_malicioso($mensagem)) {
        $erros[] = "Conteúdo suspeito detectado. Por favor, revise sua mensagem.";
    }
    
    if (empty($erros)) {
        $ip_cliente = $_SERVER['HTTP_CLIENT_IP'] ?? 
                     $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
                     $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido';
        
        $ip_cliente = substr($ip_cliente, 0, 45);
        
        $user_agent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido', 0, 255);
        
        if ($conexao && !$dbError) {
            $stmt = $conexao->prepare("INSERT INTO contatos (nome, email, telefone, assunto, mensagem, ip_cliente, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt) {
                $stmt->bind_param("sssssss", $nome, $email, $telefone, $assunto, $mensagem, $ip_cliente, $user_agent);
                
                if ($stmt->execute()) {
                    $email_admin = "suportetemquasetudo@gmail.com";
                    
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
                    
                    $mail_enviado = mail($email_admin, $assunto_email, $corpo_email, $headers);

                    if ($mail_enviado) {
                        $_SESSION['mensagem_sucesso'] = "Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.";
                    } else {
                        $_SESSION['mensagem_sucesso'] = "Sua mensagem foi salva com sucesso no nosso sistema, mas não foi possível enviar o email de notificação. Entraremos em contato em breve.";
                    }
                    $_SESSION['form_limpo'] = true;
                } else {
                    $_SESSION['erros_contato'] = ["Erro ao salvar sua mensagem. Tente novamente."];
                }
                
                $stmt->close();
            } else {
                $_SESSION['erros_contato'] = ["Erro ao processar sua mensagem. Tente novamente."];
            }
        } else {
            $_SESSION['erros_contato'] = ["Erro de conexão com banco de dados."];
        }
    } else {
        $_SESSION['erros_contato'] = $erros;
    }
    
    header('Location: /tem-quase-tudo/contato.php');
    exit();
}
