<style>
    /* VARIABLES */
    :root {
        --primary: #4f46e5;       /* Indigo (Couleur principale) */
        --primary-light: #eef2ff; /* Fond des bulles reçues */
        --secondary: #10b981;     /* Vert (Indicateur en ligne) */
        --bg-body: #f1f5f9;       /* Gris clair (Fond général) */
        --bg-card: #ffffff;       /* Fond du conteneur de chat */
        --text-main: #0f172a;     /* Noir foncé */
        --text-muted: #64748b;    /* Gris bleu */
        --border: #e2e8f0;        
        --radius: 12px;
        --radius-sm: 6px;
    }
    
    .main-content {
        width: 1350px;
        margin: 20px 350px;
        height: 80vh;
        min-height: 500px;
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* --- HEADER DE CONVERSATION --- */
    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid var(--border);
        background-color: #ffffff;
        flex-shrink: 0;
    }
    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .chat-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: var(--primary);
        color: white;
        font-weight: 600;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        font-size: 0.8rem;
    }
    .online-indicator {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 12px;
        height: 12px;
        background-color: var(--secondary);
        border-radius: 50%;
        border: 2px solid white;
    }
    .chat-info {
        line-height: 1.3;
    }
    .chat-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--text-main);
    }
    .chat-status {
        font-size: 0.8rem;
        color: var(--secondary);
        font-weight: 500;
    }
    .chat-status span {
        margin-right: 3px;
    }
    .action-btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: var(--text-muted);
        margin-left: 10px;
        padding: 5px;
        transition: color 0.2s;
    }
    .action-btn:hover {
        color: var(--primary);
    }

    /* --- MESSAGES CONTAINER --- */
    .messages-container {
        flex-grow: 1;
        padding: 20px 15px;
        overflow-y: auto;
        background-color: var(--bg-body);
        scroll-behavior: smooth;
    }

    /* --- DIVISEUR DE DATE --- */
    .date-divider {
        text-align: center;
        margin: 20px 0;
    }
    .date-divider span {
        display: inline-block;
        background-color: #e5e7eb;
        color: var(--text-muted);
        padding: 5px 10px;
        border-radius: var(--radius);
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* --- MESSAGE INDIVIDUEL --- */
    .message-wrapper {
        display: flex;
        margin-bottom: 15px;
        align-items: flex-end;
    }
    .message-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
        /* Défaut: RH */
        background-color: var(--primary);
        color: white;
    }
    .message-content-wrapper {
        display: flex;
        flex-direction: column;
        max-width: 80%;
    }
    .message-bubble {
        padding: 10px 15px;
        border-radius: 18px;
        font-size: 0.95rem;
        line-height: 1.4;
        word-wrap: break-word;
        white-space: pre-wrap;
    }
    .message-time {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 5px;
    }

    /* Style Message Reçu (RH) */
    .message-wrapper.received {
        justify-content: flex-start;
    }
    .message-wrapper.received .message-avatar {
        margin-right: 8px;
        background-color: var(--primary); /* Conserver la couleur RH */
    }
    .message-wrapper.received .message-bubble {
        background-color: var(--primary-light);
        color: var(--text-main);
        border-bottom-left-radius: 4px;
    }
    .message-wrapper.received .message-time {
        align-self: flex-start;
        margin-left: 8px;
    }

    /* Style Message Envoyé (MOI) */
    .message-wrapper.sent {
        justify-content: flex-end;
    }
    .message-wrapper.sent .message-avatar {
        margin-left: 8px;
        order: 2; /* Place l'avatar à droite */
        background-color: var(--secondary); /* Couleur pour l'utilisateur (MOI) */
    }
    .message-wrapper.sent .message-content-wrapper {
        align-items: flex-end;
        order: 1; /* Place le contenu à gauche */
    }
    .message-wrapper.sent .message-bubble {
        background-color: var(--primary);
        color: white;
        border-bottom-right-radius: 4px;
    }
    .message-wrapper.sent .message-time {
        align-self: flex-end;
        margin-right: 8px;
    }

    /* --- INDICATEUR DE FRAPPE (Typing) --- */
    .typing-indicator {
        margin-left: 8px;
        background-color: var(--primary-light);
        padding: 10px 15px;
        border-radius: 18px;
        width: 60px;
        opacity: 0; /* Masqué par défaut */
        transition: opacity 0.3s;
    }
    .typing-indicator.active {
        opacity: 1;
    }
    .typing-dots {
        display: flex;
        gap: 4px;
    }
    .typing-dots span {
        width: 6px;
        height: 6px;
        background-color: var(--primary);
        border-radius: 50%;
        animation: typing 1s infinite;
        opacity: 0.6;
    }
    .typing-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }
    .typing-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }
    @keyframes typing {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }

    /* --- ZONE DE SAISIE --- */
    .message-input-container {
        padding: 10px 15px;
        border-top: 1px solid var(--border);
        background-color: #ffffff;
        flex-shrink: 0;
    }
    .input-wrapper {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }
    .input-actions {
        display: flex;
        gap: 5px;
        flex-shrink: 0;
    }
    .attach-btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: var(--text-muted);
        padding: 5px;
        transition: color 0.2s;
    }
    .attach-btn:hover {
        color: var(--primary);
    }
    .message-input {
        flex-grow: 1;
        min-height: 40px;
        max-height: 120px;
        resize: none;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 0.95rem;
        line-height: 1.4;
        overflow-y: auto;
        transition: border-color 0.2s;
    }
    .message-input:focus {
        outline: none;
        border-color: var(--primary);
    }
    .send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary);
        color: white;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        flex-shrink: 0;
        transition: background-color 0.2s;
    }
    .send-btn:hover:not(:disabled) {
        background-color: var(--primary-dark);
    }
    .send-btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    /* --- ÉTAT VIDE --- */
    .empty-state {
        text-align: center;
        color: var(--text-muted);
        padding-top: 50px;
    }
    .empty-icon {
        font-size: 3rem;
        margin-bottom: 10px;
    }
    .empty-text {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .empty-subtext {
        font-size: 0.9rem;
    }
    
    /* Media queries */
    @media (max-width: 768px) {
        .main-content {
            height: 100vh;
            border-radius: 0;
            box-shadow: none;
        }
    }
</style>

<body>
    <div class="main-content">
        <div class="chat-header">
            <div class="header-left">
                <div class="chat-avatar">
                    RH
                    <span class="online-indicator"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-title">Service Ressources Humaines</div>
                    <div class="chat-status">
                        <span style="color: var(--secondary);">●</span>
                        En ligne
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <button class="action-btn" title="Rechercher"><i class="fa-solid fa-magnifying-glass"></i></button>
                <button class="action-btn" title="Plus d'options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            </div>
        </div>

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
                    $messageDate = date('Y-m-d', strtotime($msg['date_envoi'] ?? 'now'));
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
                    
                    // Déterminer l'expéditeur
                    $isSent = ($msg['sender'] ?? 0) == 0;
                ?>
                
                <div class="message-wrapper <?= $isSent ? 'sent' : 'received' ?>">
                    <div class="message-avatar" style="background-color: <?= $isSent ? 'var(--secondary)' : 'var(--primary)' ?>;">
                        <?= $isSent ? 'MOI' : 'RH' ?>
                    </div>
                    <div class="message-content-wrapper">
                        <div class="message-bubble">
                            <?= nl2br(htmlspecialchars($msg['contenu'] ?? '')) ?>
                        </div>
                        <div class="message-time">
                            <?= date('H:i', strtotime($msg['date_envoi'] ?? 'now')) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="message-wrapper received" id="typingWrapper" style="display: none;">
                <div class="message-avatar">RH</div>
                <div class="message-content-wrapper">
                    <div class="typing-indicator">
                        <div class="typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="message-input-container">
            <form method="POST" action="<?= constant('BASE_URL') ?>envoyer_message" id="messageForm" class="input-wrapper">
                <input type="hidden" name="id_employe" value="<?= $id_employe ?? '' ?>">
                
                <div class="input-actions">
                    <button type="button" class="attach-btn" title="Joindre un fichier"><i class="fa-solid fa-paperclip"></i></button>
                    <button type="button" class="attach-btn" title="Emoji"><i class="fa-solid fa-face-smile"></i></button>
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
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageInput = document.getElementById('messageInput');
        const messageForm = document.getElementById('messageForm');
        const sendBtn = document.getElementById('sendBtn');
        const typingWrapper = document.getElementById('typingWrapper'); // Le nouveau div wrapper pour l'indicateur

        // 1. Scroll automatique vers le bas
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Scroll au chargement
        window.addEventListener('load', () => {
            setTimeout(scrollToBottom, 200);
        });

        // 2. Auto-resize du textarea et gestion du bouton d'envoi
        messageInput.addEventListener('input', function() {
            // Auto-resize
            this.style.height = 'auto';
            // Limiter la hauteur à 120px
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            
            // Activer/désactiver le bouton d'envoi
            sendBtn.disabled = this.value.trim().length === 0;
        });

        // 3. Envoyer avec Entrée (Shift+Entrée pour nouvelle ligne)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim().length > 0) {
                    messageForm.submit();
                }
            }
        });

        // 4. Animation lors de l'envoi et simulation de l'indicateur
        messageForm.addEventListener('submit', function(e) {
            // Mise à jour du bouton
            sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            sendBtn.disabled = true;
            
            // Simuler l'indicateur de frappe du destinataire
            typingWrapper.style.display = 'flex';
            scrollToBottom();
            
            // Simuler la fin de la réponse RH après un délai
            setTimeout(() => {
                typingWrapper.style.display = 'none';
                scrollToBottom();
                sendBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
            }, 3000); // 3 secondes de simulation
        });

        // 5. Auto-focus sur l'input
        messageInput.focus();
    </script>
</body>