<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path(__FILE__) . '/wpmca-menu.php';

// create custom plugin settings menu
add_action('admin_menu', 'wpmca_criar_menu');

function wpmca_criar_menu() {

	//create new top-level menu
	add_menu_page('Marc Chat', 'Marc Chat', 'administrator', __FILE__, 'wpmca_pagina_configuracao' , 'dashicons-format-status' );

	//call register settings function
	add_action( 'admin_init', 'wpmca_registrar_opcoes' );
	
}

/*
 * Adiciona chatbot
 */
function wpmca_adicionar_codigo_ao_body() {

    if(get_option('wpmca_option')){
        $paginas_permitidas = array(get_option('wpmca_option')[3]);
    }else{
        $paginas_permitidas = "";
    }

    if ( is_page( $paginas_permitidas ) || is_single( $paginas_permitidas ) || $paginas_permitidas == "" ) {

        if(get_option('wpmca_option')){
            $options_chat = get_option('wpmca_option');
        }else{
            $options_chat = array('Marc Bot','"Bem-vindo 👋! Como posso ajudar você hoje?","Fico feliz em ajudar! Para direcioná-lo ao atendimento adequado preciso de algumas informações. Por favor, informe seu nome completo!","Ótimo agora me diga qual é o seu contato.","Muito obrigado 😀! Agora vamos te direcionar para um especialista!"','','','','','/wp-content/plugins/marc-chat-assistant/includes/wpmca-img.jpg','black');
        }

        echo '
        <style>
        #info-container {
            background-color:' . esc_html($options_chat[7]).'
        }
        #send-button {
            background-color:'.esc_html($options_chat[7]).'
        } 
        #icon-chat{
            background-image: url('.esc_html($options_chat[6]).')
        }
        .tooltip-chat::before {
            background-color:'.esc_html($options_chat[7]).'
        }       
        </style>
        <div id="icon-chat" class="tooltip-chat" data-tooltip="Como podemos te ajudar?"></div>
        <div id="chat-body">
            <div id="fechar-chat">X</div>
            <div id="info-container">
                <div id="info">' . esc_html($options_chat[0]) . '<a style="color:white;font-size:16px;" href="https://wa.me/55'.esc_html($options_chat[2]).'">WhatsApp: '.esc_html($options_chat[2]).'</a>'.'</div>
            </div>
        
            <div id="chat-container">
                <div id="chat-messages"></div>
            </div>
        
            <div id="input-container">
                <input type="text" id="message-input" placeholder="Digite sua mensagem">
                <button id="send-button">Enviar</button>
            </div>
            
            <form style="display:none;" method="post" action="'.esc_url(admin_url('admin-post.php')).'">
                '.wp_nonce_field('enviar-email', 'enviar-email-nonce') . '
                <input type="hidden" name="action" value="wpmca_enviar_email">
                <input id="enviar-text" name="corpo" type="text">
                <input id="enviar" type="submit" value="Enviar Email">
            </form>
        </div>';

        //registrar scripts e estilos
        wp_register_style('wpmca-style', plugins_url('/marc-chat-assistant/includes/wpmca-style.css'));
        wp_enqueue_style('wpmca-style');
        wp_register_script('wpmca-script', plugins_url('/marc-chat-assistant/includes/wpmca-script.js'));
        wp_enqueue_script('wpmca-script');
        wp_localize_script('wpmca-script', 'wpmca_optios_chat', $options_chat);
    }

}
add_action('wp_footer', 'wpmca_adicionar_codigo_ao_body');

/*
 * Enviar Email
 */
function wpmca_enviar_email(){

    if (isset($_POST['enviar-email-nonce']) && wp_verify_nonce( sanitize_text_field(wp_unslash( $_POST['enviar-email-nonce'])), 'enviar-email')) {
        $corpo = isset($_POST['corpo']) ? sanitize_text_field($_POST['corpo']) : 'oi';
        $corpo = isset($_POST['corpo']) && !empty($_POST['corpo']) ? sanitize_text_field($_POST['corpo']) : 'oi';
        $corpo = esc_html($corpo);

        if(get_option('wpmca_option')[5]){
            wp_mail(get_option('wpmca_option')[5], 'Nova mensagem do chatbot', $corpo);
        }
        
        if(get_option('wpmca_option')[4]){
            wp_redirect(get_option('wpmca_option')[4]);
            exit;
        }
    }

}
add_action('init', 'wpmca_enviar_email');