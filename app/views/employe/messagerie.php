<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie RH - RH Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            overflow: hidden;
        }

        .main-content {
            margin-left: 260px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .chat-header {
            background: white;
            padding: 20px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .chat-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            position: relative;
        }

        .online-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 14px;
            height: 14px;
            background: #10b981;
            border: 3px solid white;
            border-radius: 50%;
        }

        .chat-info {
            display: flex;
            flex-direction: column;
        }

        .chat-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .chat-status {
            font-size: 12px;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .header-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 18px;
        }

        .action-btn:hover {
            background: #e2e8f0;
            transform: scale(1.05);
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 24px 32px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .messages-container::-webkit-scrollbar {
            width: 8px;
        }

        .messages-container::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .messages-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .messages-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .message-wrapper {
            display: flex;
            gap: 12px;
            animation: messageSlideIn 0.3s ease;
        }

        .message-wrapper.sent {
            flex-direction: row-reverse;
        }

        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .message-wrapper.received .message-avatar {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        .message-wrapper.sent .message-avatar {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }

        .message-content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 4px;
            max-width: 60%;
        }

        .message-wrapper.sent .message-content-wrapper {
            align-items: flex-end;
        }

        .message-bubble {
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
            position: relative;
        }

        .message-wrapper.received .message-bubble {
            background: white;
            color: #1e293b;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .message-wrapper.sent .message-bubble {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message-time {
            font-size: 11px;
            color: #94a3b8;
            padding: 0 4px;
        }

        .date-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 16px 0;
        }

        .date-divider span {
            background: #e2e8f0;
            color: #64748b;
            padding: 6px 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .typing-indicator {
            display: none;
            padding: 12px 16px;
            background: white;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            width: fit-content;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .typing-indicator.active {
            display: block;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            background: #94a3b8;
            border-radius: 50%;
            animation: typingBounce 1.4s infinite;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typingBounce {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        .message-input-container {
            background: white;
            padding: 20px 32px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .input-wrapper {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .input-actions {
            display: flex;
            gap: 8px;
        }

        .attach-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 18px;
            color: #64748b;
        }

        .attach-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .message-input {
            flex: 1;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 12px 20px;
            font-size: 14px;
            font-family: inherit;
            resize: none;
            max-height: 120px;
            min-height: 44px;
            transition: all 0.2s ease;
        }

        .message-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
        }

        .message-input::placeholder {
            color: #94a3b8;
        }

        .send-btn {
            width: 44px;
            height: 44px;
            border: none;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 20px;
        }

        .send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .send-btn:active {
            transform: scale(0.95);
        }

        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: scale(1);
        }

        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            text-align: center;
            padding: 40px;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-text {
            font-size: 16px;
            margin-bottom: 8px;
            color: #64748b;
        }

        .empty-subtext {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .chat-header,
            .messages-container,
            .message-input-container {
                padding-left: 16px;
                padding-right: 16px;
            }

            .message-content-wrapper {
                max-width: 80%;
            }

            .attach-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include('app/views/sidebar/sidebar.php') ?>

    <div class="main-content">
        <!-- Header de la conversation -->
        <div class="chat-header">
            <div class="header-left">
                <div class="chat-avatar">
                    RH
                    <span class="online-indicator"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-title">Service Ressources Humaines</div>
                    <div class="chat-status">
                        <span>●</span>
                        En ligne
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <button class="action-btn" title="Rechercher">🔍</button>
                <button class="action-btn" title="Plus d'options">⋮</button>
            </div>
        </div>

        <!-- Zone des messages -->
        <div class="messages-container" id="messagesContainer">
            <?php if (empty($messages)): ?>
                <div class="empty-state">
                    <div class="empty-icon">💬</div>
                    <div class="empty-text">Aucun message pour le moment</div>
                    <div class="empty-subtext">Commencez la conversation en envoyant un message</div>
                </div>
            <?php else: ?>
                <?php 
                $lastDate = null;
                foreach ($messages as $msg): 
                    $messageDate = date('Y-m-d', strtotime($msg['date_envoi']));
                    $today = date('Y-m-d');
                    $yesterday = date('Y-m-d', strtotime('-1 day'));
                    
                    // Afficher le diviseur de date si nécessaire
                    if ($messageDate !== $lastDate):
                        $dateLabel = $messageDate === $today ? "Aujourd'hui" : 
                                    ($messageDate === $yesterday ? "Hier" : 
                                    date('d/m/Y', strtotime($messageDate)));
                ?>
                    <div class="date-divider">
                        <span><?= $dateLabel ?></span>
                    </div>
                <?php 
                        $lastDate = $messageDate;
                    endif;
                ?>
                
                <div class="message-wrapper <?= $msg['sender'] == 0 ? 'sent' : 'received' ?>">
                    <div class="message-avatar">
                        <?= $msg['sender'] == 0 ? 'MOI' : 'RH' ?>
                    </div>
                    <div class="message-content-wrapper">
                        <div class="message-bubble">
                            <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
                        </div>
                        <div class="message-time">
                            <?= date('H:i', strtotime($msg['date_envoi'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Indicateur de frappe -->
            <div class="message-wrapper received">
                <div class="message-avatar">RH</div>
                <div class="typing-indicator" id="typingIndicator">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone de saisie -->
        <div class="message-input-container">
            <form method="POST" action="<?= constant('BASE_URL') ?>envoyer_message" id="messageForm" class="input-wrapper">
                <input type="hidden" name="id_employe" value="<?= $id_employe ?>">
                
                <div class="input-actions">
                    <button type="button" class="attach-btn" title="Joindre un fichier">📎</button>
                    <button type="button" class="attach-btn" title="Emoji">😊</button>
                </div>
                
                <textarea 
                    name="contenu" 
                    id="messageInput"
                    class="message-input" 
                    placeholder="Tapez votre message..."
                    rows="1"
                    required
                ></textarea>
                
                <button type="submit" class="send-btn" id="sendBtn" disabled title="Envoyer">
                    ➤
                </button>
            </form>
        </div>
    </div>

    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageInput = document.getElementById('messageInput');
        const messageForm = document.getElementById('messageForm');
        const sendBtn = document.getElementById('sendBtn');
        const typingIndicator = document.getElementById('typingIndicator');

        // Scroll automatique vers le bas
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Scroll au chargement
        window.addEventListener('load', () => {
            setTimeout(scrollToBottom, 100);
        });

        // Auto-resize du textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            
            // Activer/désactiver le bouton d'envoi
            sendBtn.disabled = this.value.trim().length === 0;
        });

        // Envoyer avec Entrée (Shift+Entrée pour nouvelle ligne)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim().length > 0) {
                    messageForm.submit();
                }
            }
        });

        // Animation lors de l'envoi
        messageForm.addEventListener('submit', function(e) {
            sendBtn.innerHTML = '⏳';
            sendBtn.disabled = true;
            
            // Simuler l'indicateur de frappe du destinataire
            setTimeout(() => {
                typingIndicator.classList.add('active');
            }, 1000);
        });

        // Simulation de réception de message (pour démo)
        // Dans un vrai système, cela serait géré par WebSocket ou polling
        function simulateTyping() {
            typingIndicator.classList.add('active');
            setTimeout(() => {
                typingIndicator.classList.remove('active');
            }, 3000);
        }

        // Auto-focus sur l'input
        messageInput.focus();

        // Empêcher le zoom sur iOS lors du focus
        if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
            messageInput.style.fontSize = '16px';
        }
    </script>
</body>
</html>