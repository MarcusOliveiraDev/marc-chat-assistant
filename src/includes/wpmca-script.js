let chatMessages = document.getElementById('chat-messages');
let chatContainerMessages = document.getElementById('chat-container');
let messageInput = document.getElementById('message-input');
let sendButton = document.getElementById('send-button');

const fecharChat = document.getElementById('fechar-chat');
const abrirChat = document.getElementById('icon-chat');
const chatBody = document.getElementById('chat-body');

// Define os ouvintes de evento
fecharChat.addEventListener('click', function() {
    chatBody.style.display = '';
});

abrirChat.addEventListener('click', function() {
    chatBody.style.display = 'block';
});

// Finaliza a animação quando terminar
chatBody.addEventListener('animationend', function() {
    chatBody.style.animationPlayState = 'paused';
});

sendButton.addEventListener('click', sendMessage);
messageInput.addEventListener('keydown', function(event) {
    if (event.keyCode === 13) {
        sendMessage();
    }
});

function sendMessage() {
    let message = messageInput.value.trim();
    if (message !== '') {
        let newMessage = document.createElement('div');
        newMessage.className = 'message';
        newMessage.innerText = `Você: ${message}`;
        chatMessages.appendChild(newMessage);
        messageInput.value = '';
        chatContainerMessages.scrollTop = chatMessages.scrollHeight;
        
        // Índice da pergunta atual
        currentQuestionIndex = currentQuestionIndex+1;
        
        // Carrega pergunta
        displayQuestion();
    }
}

// Array de perguntas do robô
let questions = JSON.parse('[' + wpmca_optios_chat[1] + ']');

// Índice da pergunta atual
let currentQuestionIndex = 0;

// Carrega a primeira pergunta
displayQuestion();

function displayQuestion() {
    if(currentQuestionIndex == (questions.length-1)){
        
        setTimeout(function() {
            document.getElementById('enviar-text').value = document.getElementById('chat-messages').textContent;
            document.getElementById('enviar').click();
        }, 3000);
        
        
    }
    
    let question = questions[currentQuestionIndex];

    let newMessage = document.createElement('div');
    newMessage.className = 'load';
    newMessage.innerText = wpmca_optios_chat[0] + ': Carregando Mensagem...';
    chatMessages.appendChild(newMessage);
    chatContainerMessages.scrollTop = chatMessages.scrollHeight;

    setTimeout(function() {
        newMessage.innerText = wpmca_optios_chat[0]+`: ${question}`;
        newMessage.classList.remove('load');
        newMessage.className = 'robot';
        
        chatContainerMessages.scrollTop = chatMessages.scrollHeight;
    }, 1500);
}

//verificar se chat deve iniciar ativo
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has("chat") && urlParams.get("chat") === "ativo") {
    abrirChat.click();
}