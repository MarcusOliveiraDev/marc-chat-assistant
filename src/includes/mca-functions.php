<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path(__FILE__) . '/mca-menu.php';

// create custom plugin settings menu
add_action('admin_menu', 'criar_menu');

function criar_menu() {

	//create new top-level menu
	add_menu_page('Marc Chat', 'Marc Chat', 'administrator', __FILE__, 'pagina_configuracao' , 'dashicons-format-status' );

	//call register settings function
	add_action( 'admin_init', 'registrar_opcoes' );
	
}

/*
 * Adiciona chatbot
 */
function adicionar_codigo_ao_body() {
    if(get_option('mca_option')){
        $paginas_permitidas = array(get_option('mca_option')[3]);
    }else{
        $paginas_permitidas = "";
    }

    if ( is_page( $paginas_permitidas ) || is_single( $paginas_permitidas ) || $paginas_permitidas == "" ) {
        ob_start();
        require_once(plugin_dir_path(__FILE__) . 'mca-index.php');
        $codigo = ob_get_clean();
        echo $codigo;
    }
    if(isset($_GET['chat']) && $_GET['chat'] == 'ativo'){
        ob_start();
        require_once(plugin_dir_path(__FILE__) . 'mca-index.php');
        $codigo = ob_get_clean();
        echo $codigo . '<script>abrirChat.click();</script>';
    }
}
add_action('wp_footer', 'adicionar_codigo_ao_body');

/*
 * Enviar Email
 */
function enviar_email(){
    $corpo = isset($_POST['corpo']) ? htmlspecialchars($_POST['corpo']) : 'oi';

    if(get_option('mca_option')[5]){
        wp_mail(get_option('mca_option')[5], 'Nova mensagem do chatbot', $corpo);
    }
    
    if(get_option('mca_option')[4]){
        wp_redirect(get_option('mca_option')[4]);
        exit;
    }
}

if (isset($_POST['corpo'])) {
    add_action('init', 'enviar_email');
}