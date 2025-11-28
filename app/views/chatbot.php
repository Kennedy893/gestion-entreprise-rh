<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Paie</title>
    <style>
        .chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 500px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        
        .chatbot-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            font-weight: bold;
        }
        
        .chatbot-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
        }
        
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
        }
        
        .user-message {
            background: #007bff;
            color: white;
            margin-left: auto;
        }
        
        .bot-message {
            background: white;
            border: 1px solid #dee2e6;
        }
        
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
        
        .suggestion {
            background: #e9ecef;
            border: none;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .suggestion:hover {
            background: #dee2e6;
        }
        
        .chatbot-input {
            padding: 15px;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 10px;
        }
        
        .chatbot-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }
        
        .chatbot-input button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
        }
        
        .chatbot-input button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Intégration du chatbot -->
    <div class="chatbot-container">
        <div class="chatbot-header">
            🤖 Assistant Paie
        </div>
        
        <div class="chatbot-messages" id="chatMessages">
            <div class="message bot-message">
                Bonjour ! Je suis votre assistant pour les questions de paie.
                Je peux vous aider avec les salaires, retenues, avantages, etc.
            </div>
        </div>
        
        <div class="chatbot-input">
            <input type="text" id="questionInput" placeholder="Posez votre question..." onkeypress="handleKeyPress(event)">
            <button onclick="sendQuestion()">Envoyer</button>
        </div>
    </div>

    <script>
        function sendQuestion() {
            const input = document.getElementById('questionInput');
            const question = input.value.trim();
            const messages = document.getElementById('chatMessages');
            
            if (!question) return;
            
            // Ajouter message utilisateur
            const userMsg = document.createElement('div');
            userMsg.className = 'message user-message';
            userMsg.textContent = question;
            messages.appendChild(userMsg);
            
            // Clear input
            input.value = '';
            
            // Scroll vers le bas
            messages.scrollTop = messages.scrollHeight;
            
            // Envoyer au serveur
           fetch('<?= constant('BASE_URL') ?>/chatbot/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({question: question})
            })
            .then(response => response.json())
            .then(data => {
                const botMsg = document.createElement('div');
                botMsg.className = 'message bot-message';
                botMsg.innerHTML = data.reponse;
                
                if (data.suggestions) {
                    const suggestions = document.createElement('div');
                    suggestions.className = 'suggestions';
                    data.suggestions.forEach(suggestion => {
                        const btn = document.createElement('button');
                        btn.className = 'suggestion';
                        btn.textContent = suggestion;
                        btn.onclick = () => {
                            document.getElementById('questionInput').value = suggestion;
                            sendQuestion();
                        };
                        suggestions.appendChild(btn);
                    });
                    botMsg.appendChild(suggestions);
                }
                
                messages.appendChild(botMsg);
                messages.scrollTop = messages.scrollHeight;
            })
            .catch(error => {
                const errorMsg = document.createElement('div');
                errorMsg.className = 'message bot-message';
                errorMsg.textContent = "Désolé, une erreur s'est produite. Veuillez réessayer.";
                messages.appendChild(errorMsg);
                messages.scrollTop = messages.scrollHeight;
            });
        }
        
        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendQuestion();
            }
        }
        
        // Focus sur l'input au chargement
        document.getElementById('questionInput').focus();
    </script>
</body>
</html>