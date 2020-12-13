<?php
/**
 * Plugin Name:       Allow Certain Email
 * Plugin URI:        https://www.prowebtips.com/
 * Description:       The ultimate solution to stop spam registration on WordPress.
 * Author:            Pronay Sarkar
 * Author URI:        https://www.bareblogging.com/about/
 * Version:           1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Register activation hook. */
register_activation_hook( __FILE__, 'allow_certain_email_activation_hook' );
 
/**
 * Runs only when the plugin is activated.
 * @since 1.0.2
 */
function allow_certain_email_activation_hook() {
 
/* Create transient data */
    set_transient( 'forget-spam-comment-activation-notice', true, 5 );
}
 
 
/* Add admin notice */
add_action( 'admin_notices', 'allow_certain_email_notice' );
 
 
/**
 * Admin Notice on Activation.
 * @since 1.0.2
 */
function allow_certain_email_notice(){
 
    /* Check transient, if available display notice */
    if( get_transient( 'forget-spam-comment-activation-notice' ) ){
        ?><style>div#message.updated{ display: none; }</style>
        <div class="updated notice is-dismissible">
            <p>Thank you for using Allow Certain Email. 😎 <strong>Please clear Page Cache</strong>.</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'forget-spam-comment-activation-notice' );
    }
}

// Support links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'allow_certain_email_add_action_links');
function allow_certain_email_add_action_links($links) {
    $plugin_shortcuts = array(
        '<a rel="noopener" href="https://www.prowebtips.com/contact/" target="_blank">Ask a Question</a>',
        '<a rel="noopener" href="https://www.buymeacoffee.com/pronay/" target="_blank" style="color:#3db634;">Buy developer a coffee</a>'
    );
    return array_merge($links, $plugin_shortcuts);
}

// Allowed email domains

function is_valid_email_domain($login, $email, $errors ){
 $valid_email_domains = array("gmail.com","yahoo.com","hotmail.com","outlook.com");// whitelist email domain lists
 $valid = false;
 foreach( $valid_email_domains as $d ){
 $d_length = strlen( $d );
 $current_email_domain = strtolower( substr( $email, -($d_length), $d_length));
 if( $current_email_domain == strtolower($d) ){
 $valid = true;
 break;
 }
 }
 // if invalid, return error message
 if( $valid === false ){
 $errors->add('domain_whitelist_error',__( '<strong>ERROR</strong>: you can only register using @gmail.com or @yahoo.com or @hotmail.com or @outlook.com emails' ));
 }
}
add_action('register_post', 'is_valid_email_domain',10,3 );

// Share your feedback at https://github.com/iampronay/