<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); 

if(get_option('mca_option')){
    $options_chat = get_option('mca_option');
}else{
    $options_chat = array('Marc Bot','"Bem-vindo 👋! Como posso ajudar você hoje?","Fico feliz em ajudar! Para direcioná-lo ao atendimento adequado preciso de algumas informações. Por favor, informe seu nome completo!","Ótimo agora me diga qual é o seu contato.","Muito obrigado 😀! Agora vamos te direcionar para um especialista!"','','','','','/wp-content/plugins/marc-chat-assistant/includes/mca-img.jpg','black');
}
            
?>

<style>
#chat-body{
    position: fixed;
    right: 40px;
    bottom: 60px;
    width: 350px;
    max-height: 500px;
    border-radius: 10px;
    box-shadow: 0px 8px 23px 3px rgba(0,0,0,0.2);
    font-family: Arial, Helvetica, sans-serif;
    background-color: white;
    z-index: 99999;
    display:none;
}

#info-container {
    display: flex;
    padding: 20px;
    background-color: <?php echo $options_chat[7] ?>;
    border-radius: 10px 10px 0px 0px;
}
#info{
    display: flex;
    flex-direction: column;
    font-size: 20px;
    color: white;
}

#chat-container {
    width: 100%;
    height: 300px;
    border-bottom: 1px solid #ccc;
    overflow-y: scroll;
}
#chat-messages {
    padding: 10px;
    display: flex;
    flex-direction: column;
}
.message {
    margin-bottom: 10px;
    padding: 20px;
    background-color:rgba(0,0,255,0.05);
    border-radius: 10px 0px 10px 10px;
    align-self: flex-end;
}
.robot {
    margin-bottom: 10px;
    padding: 20px;
    background-color:rgba(0,0,255,0.05);
    border-radius: 0px 10px 10px 10px;
    align-self: flex-start;
}
.load{
    margin-bottom: 10px;
    padding: 10px;
    background-color:rgba(0,255,0,0.05);
    border-radius: 10px 10px 10px 10px;
    align-self: center;
}

#input-container {
    display: flex;
    padding: 20px;
}
#message-input {
    width: 80%;
    padding: 5px 10px;
    margin-right: 10px;
    border: none;
    border-radius: 200px;
}
#send-button {
    width: 30%;
    padding: 5px 10px;
    background-color: <?php echo $options_chat[7] ?>;
    border: none;
    color: white;
    border-radius: 200px;
    text-transform: none;
    font-size: 16px;
}

@media (max-width: 768px) {
    #chat-body{
        right: 0px;
        bottom: 0px;
        width: 100vw;
        max-height: 500px;
        border-radius: 10px;
        box-shadow: 0px 8px 23px 3px rgba(0,0,0,0.2);
        font-family: Arial, Helvetica, sans-serif;
        background-color: white;
        z-index: 99999;
        display:none;
    }
}

#icon-chat{
    position: fixed;
    right: 20px;
    bottom: 20px;
    height: 65px;
    width: 65px;
    background-image: url('<?php echo $options_chat[6] ?>');
    background-size: cover;
    background-position: center;
    border-radius: 400px;
    z-index: 9999;
    cursor: pointer;
    box-shadow: 0px 0px 20px 2px rgba(0,0,0,0.2);
}
#fechar-chat{
    position: absolute;
    right: 20px;
    top: 20px;
    height: 20px;
    width: 20px;
    background-color: white;
    color: black;
    border-radius: 400px;
    z-index: 1;
    cursor: pointer;
    line-height: 20px;
    text-align:center;
}

.tooltip-chat::before {
    content: attr(data-tooltip);
    position: absolute;
    top: 0px;
    transform: translateX(-100%);
    padding: 5px;
    background-color: <?php echo $options_chat[7] ?>;
    color: #fff;
    font-size: 14px;
    border-radius: 4px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
    width: max-content;
}

.tooltip-chat:hover::before,
.tooltip-chat:active::before {
    opacity: 1;
    visibility: visible;
}
</style>

<div id="icon-chat" class="tooltip-chat" data-tooltip="Como podemos te ajudar?"></div>
<div id="chat-body">
    <div id="fechar-chat">X</div>
    <div id="info-container">
        <div id="info"> <?php echo $options_chat[0]. '<a style="color:white;font-size:16px;" href="https://wa.me/55'.$options_chat[2].'">WhatsApp: '.$options_chat[2].'</a>'; ?> </div>
    </div>

    <div id="chat-container">
        <div id="chat-messages"></div>
    </div>

    <div id="input-container">
        <input type="text" id="message-input" placeholder="Digite sua mensagem">
        <button id="send-button">Enviar</button>
    </div>
    
    <form style="display:none;" method="post">
        <input id="enviar-text" name="corpo" type="text">
        <input id="enviar" type="submit" value="Enviar Email">
    </form>
</div>

<script>
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
let questions = [
    <?php 
        $mensagens_default = '"Bem-vindo 👋! Como posso ajudar você hoje?","Fico feliz em ajudar! Para direcioná-lo ao atendimento adequado preciso de algumas informações. Por favor, informe seu nome completo!","Ótimo agora me diga qual é o seu contato.","Muito obrigado 😀! Agora vamos te direcionar para um especialista!"';

        if(get_option('mca_option')){
            $mensagens = $options_chat[1]; // Obtém as mensagens armazenadas como uma opção
        }else{
            $mensagens = $mensagens_default;
        }
        
        echo $mensagens;
        ?>
];

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
    newMessage.innerText = '<?php echo substr(strstr($options_chat[0], ' ', true), 0); ?>: Carregando Mensagem...';
    chatMessages.appendChild(newMessage);
    chatContainerMessages.scrollTop = chatMessages.scrollHeight;

    setTimeout(function() {
        newMessage.innerText = `<?php echo substr(strstr($options_chat[0], ' ', true), 0); ?>: ${question}`;
        newMessage.classList.remove('load');
        newMessage.className = 'robot';
        
        chatContainerMessages.scrollTop = chatMessages.scrollHeight;
    }, 1500);
}
</script>