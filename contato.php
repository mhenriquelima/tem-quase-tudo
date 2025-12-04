<?php
include 'includes/header.php';
include 'includes/contact_handler.php';
?>

<main>
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-header">
                <h1>Fale Conosco</h1>
                <p>Tem dúvidas ou precisa de ajuda? Envie uma mensagem e nossa equipe responderá o mais breve possível!</p>
            </div>

            <div class="contact-content">
                <!-- Coluna esquerda - Informações -->
                <div class="contact-info">
                    <div class="info-card">
                        <div class="info-icon">💬</div>
                        <h3>Chat de Suporte</h3>
                        <p>Disponível de seg-sex, 9h-18h</p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">📧</div>
                        <h3>Email</h3>
                        <p><a href="mailto:suportetemquasetudo@gmail.com">suportetemquasetudo@gmail.com</a></p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">📞</div>
                        <h3>Telefone</h3>
                        <p><a href="tel:1133334444">(11) 3333-4444</a></p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">📍</div>
                        <h3>Localização</h3>
                        <p>São Paulo - SP<br>Brasil</p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">🕐</div>
                        <h3>Horário de Atendimento</h3>
                        <p>Segunda a Sexta: 9h - 18h<br>Sábado: 10h - 14h<br>Domingo: Fechado</p>
                    </div>
                </div>

                <!-- Coluna direita - Formulário -->
                <div class="contact-form-wrapper">
                    <!-- Mensagens de sucesso/erro -->
                    <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                        <!-- Modal de agradecimento -->
                        <div id="thankyou-modal" class="modal" aria-hidden="false" style="display:block;">
                            <div class="modal-overlay" id="modal-overlay"></div>
                            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                                <button id="modal-close" class="modal-close" aria-label="Fechar">×</button>
                                <h2 id="modal-title">Obrigado!</h2>
                                <p class="modal-message"><?php echo $_SESSION['mensagem_sucesso']; ?></p>
                                <p class="modal-note">Você será redirecionado para a página inicial em <strong><span id="modal-countdown">5</span></strong> segundos.</p>
                                <div class="modal-actions">
                                    <button id="go-home" class="btn-submit">Ir para Início Agora</button>
                                </div>
                            </div>
                        </div>
                        <?php unset($_SESSION['mensagem_sucesso']); ?>

                        <style>
                            .modal { position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; z-index: 9999; }
                            .modal-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.5); }
                            .modal-content { position: relative; background: #fff; padding: 24px; max-width: 480px; width: 90%; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); z-index: 10000; }
                            .modal-close { position: absolute; right: 10px; top: 10px; background: transparent; border: none; font-size: 22px; cursor: pointer; }
                            .modal-message { margin: 12px 0; color: #333; }
                            .modal-note { margin: 8px 0 16px; color: #555; }
                            .modal-actions { text-align: right; }
                            .btn-submit { background:#FF9900; color:#fff; border:none; padding:8px 14px; border-radius:4px; cursor:pointer; }
                        </style>

                        <script>
                            (function() {
                                var seconds = 5;
                                var countdownEl = document.getElementById('modal-countdown');
                                var interval = setInterval(function() {
                                    seconds--;
                                    if (countdownEl) countdownEl.textContent = seconds;
                                    if (seconds <= 0) {
                                        clearInterval(interval);
                                        window.location.href = '/tem-quase-tudo/index.php';
                                    }
                                }, 1000);

                                var modal = document.getElementById('thankyou-modal');
                                var overlay = document.getElementById('modal-overlay');
                                var closeBtn = document.getElementById('modal-close');
                                var goHome = document.getElementById('go-home');

                                function closeModal(redirect) {
                                    if (modal) modal.style.display = 'none';
                                    if (redirect) window.location.href = '/tem-quase-tudo/index.php';
                                }

                                if (overlay) overlay.addEventListener('click', function() { closeModal(true); });
                                if (closeBtn) closeBtn.addEventListener('click', function() { closeModal(false); });
                                if (goHome) goHome.addEventListener('click', function() { closeModal(true); });
                            })();
                        </script>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['erros_contato'])): ?>
                        <div class="alert alert-error">
                            <span class="alert-icon">⚠</span>
                            <div>
                                <strong>Erros encontrados:</strong>
                                <ul>
                                    <?php foreach ($_SESSION['erros_contato'] as $erro): ?>
                                        <li><?php echo $erro; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php unset($_SESSION['erros_contato']); ?>
                    <?php endif; ?>

                    <form method="POST" action="includes/contact_handler.php" class="contact-form">
                        <div class="form-group">
                            <label for="nome">Nome Completo *</label>
                            <input 
                                type="text" 
                                id="nome" 
                                name="nome" 
                                placeholder="Seu nome completo"
                                required
                                maxlength="100"
                            >
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="seu.email@exemplo.com"
                                required
                                maxlength="100"
                            >
                        </div>

                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input 
                                type="tel" 
                                id="telefone" 
                                name="telefone" 
                                placeholder="(11) 9999-9999"
                                maxlength="20"
                            >
                        </div>

                        <div class="form-group">
                            <label for="assunto">Assunto *</label>
                            <select id="assunto" name="assunto" required>
                                <option value="">Selecione um assunto</option>
                                <option value="Dúvida sobre produto">Dúvida sobre produto</option>
                                <option value="Problema com pedido">Problema com pedido</option>
                                <option value="Devolução/Troca">Devolução/Troca</option>
                                <option value="Pagamento">Pagamento</option>
                                <option value="Envio/Rastreamento">Envio/Rastreamento</option>
                                <option value="Reclamação">Reclamação</option>
                                <option value="Sugestão">Sugestão</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mensagem">Mensagem *</label>
                            <textarea 
                                id="mensagem" 
                                name="mensagem" 
                                placeholder="Descreva sua dúvida ou solicitação detalhadamente..."
                                rows="8"
                                required
                                minlength="10"
                                maxlength="2000"
                            ></textarea>
                            <small id="char-count">0/2000 caracteres</small>
                        </div>

                        <button type="submit" class="btn-submit">Enviar Mensagem</button>
                    </form>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <h2>Perguntas Frequentes</h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <h3>Como rastrear meu pedido?</h3>
                        <p>Você receberá um código de rastreamento por email assim que seu pedido for despachado. Acesse a página de rastreamento ou entre em contato com nossa equipe.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Qual é o prazo de entrega?</h3>
                        <p>O prazo varia de 5 a 15 dias úteis dependendo da sua localização e do tipo de envio escolhido.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Como faço uma devolução?</h3>
                        <p>Temos uma política de devolução de 30 dias. Entre em contato conosco e orientaremos o processo completo.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Quais formas de pagamento vocês aceitam?</h3>
                        <p>Aceitamos cartão de crédito, débito, transferência bancária e PIX para sua comodidade.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Posso trocar um produto por outro?</h3>
                        <p>Sim! Desde que esteja dentro do prazo de 30 dias e em perfeitas condições.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Como funciona o programa de afiliados?</h3>
                        <p>Temos um programa especial para parceiros. Envie uma mensagem com o assunto "Afiliados" para mais informações.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Script para contador de caracteres -->
<script>
    const textareaMsg = document.getElementById('mensagem');
    const charCount = document.getElementById('char-count');
    
    textareaMsg.addEventListener('input', function() {
        charCount.textContent = this.value.length + '/2000 caracteres';
    });
</script>

<?php include 'includes/footer.php'; ?>
