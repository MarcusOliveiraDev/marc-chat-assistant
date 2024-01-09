<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function wpmca_registrar_opcoes() {
    // Register our settings
    if(current_user_can( 'manage_options' )){
        register_setting( 'registrar-opcoes-grupo', 'wpmca_option' );
    }else{
        echo 'Você não tem permissão para editar as opções do plugin.';
    }
}

function wpmca_pagina_configuracao() {
?>
<div class="wrap">
    <h1>Chatbot</h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'registrar-opcoes-grupo' ); ?>
        <?php do_settings_sections( 'registrar-opcoes-grupo' ); ?>
        <table class="form-table">
            <?php 
            if(get_option('wpmca_option')){
                $options_chat = get_option('wpmca_option');
            }else{
                $options_chat = array('Marc Bot','"Bem-vindo 👋! Como posso ajudar você hoje?","Fico feliz em ajudar! Para direcioná-lo ao atendimento adequado preciso de algumas informações. Por favor, informe seu nome completo!","Ótimo agora me diga qual é o seu contato.","Muito obrigado 😀! Agora vamos te direcionar para um especialista!"','','','','','/wp-content/plugins/marc-chat-assistant/includes/wpmca-img.jpg','black');
            }
             ?>
            <tr>
                <th>Nome do bot</th>
                <td><input name="wpmca_option[0]" type="text" placeholder="Chat Bot" value="<?php echo esc_attr( $options_chat[0] ); ?>"></td>
            </tr>
            <tr>
                <th>Mensagens</th>
                <td><textarea name="wpmca_option[1]" rows="5" placeholder="'Faça sua mensagem','nesse','modelo'"><?php echo esc_attr( $options_chat[1] ); ?></textarea></td>
            </tr>
            <tr>
                <th>Número Zap</th>
                <td><input name="wpmca_option[2]" type="tel" placeholder="63991000000" value="<?php echo esc_attr( $options_chat[2] ); ?>"></td>
            </tr>
            <tr>
                <th>Onde deve aparecer o chat</th>
                <td><input name="wpmca_option[3]" type="text" placeholder="id da página, ou vazio para aparecer em todas" value="<?php echo esc_attr( $options_chat[3] ); ?>"></td>
            </tr>
            <tr>
                <th>Onde deve mandar o usuário no final</th>
                <td><input name="wpmca_option[4]" type="url" placeholder="link redirecionamento" value="<?php echo esc_attr( $options_chat[4] ); ?>"></td>
            </tr>
            <tr>
                <th>Email que deve mandar os dados</th>
                <td><input name="wpmca_option[5]" type="email" placeholder="contato@email.com" value="<?php echo esc_attr( $options_chat[5] ); ?>"></td>
            </tr>
            <tr>
                <th>URL da imagem do chat</th>
                <td><input name="wpmca_option[6]" type="url" value="<?php echo esc_attr( $options_chat[6] ); ?>"></td>
            </tr>
            <tr>
                <th>Cor do chat</th>
                <td><input name="wpmca_option[7]" type="text" value="<?php echo esc_attr( $options_chat[7] ); ?>"></td>
            </tr>
            <tr>
                <th>Na URL /?chat=ativo abrirá o chat automaticamente</th>
            </tr>
            <tr>
                <th><?php /*echo 'Dados: ' . implode(', ', $options_chat);*/ ?></th>
            </tr>
        </table>
        
        <?php submit_button(); ?>

    </form>
</div>
<?php } ?>
