<?php
/*
Plugin Name: Custom JavaScript Plugin
Description: A super simple plugin that allows administrators to insert custom JavaScript in the header or footer of specific pages or site wide. Please note that this is a basic example and doesn’t include any input validation or error handling. You’ll want to add that for a production environment. Also, this example assumes that the user enters valid JavaScript in the settings.
Version: 1.0
Author: Gonzalo Prado
Author URL: https://gonzaloprado.ar/
*/

// Create custom plugin settings menu
add_action('admin_menu', 'custom_js_plugin_create_menu');

function custom_js_plugin_create_menu() {
    //create new top-level menu
    add_menu_page('Custom JS Plugin Settings', 'Custom JS Settings', 'administrator', __FILE__, 'custom_js_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );

    //call register settings function
    add_action( 'admin_init', 'register_custom_js_plugin_settings' );
}

function register_custom_js_plugin_settings() {
    //register our settings
    register_setting( 'custom-js-plugin-settings-group', 'custom_js' );
    register_setting( 'custom-js-plugin-settings-group', 'placement' );
    register_setting( 'custom-js-plugin-settings-group', 'page_id' );
}

function custom_js_plugin_settings_page() {
?>
<div class="wrap">
<h1>Custom JS</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'custom-js-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'custom-js-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Custom JS</th>
        <td><input type="text" name="custom_js" value="<?php echo esc_attr( get_option('custom_js') ); ?>" title="Enter your custom JavaScript code here." /></td>
        <td><p>Enter your custom JavaScript code here.</p></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Placement</th>
        <td>
            <select name="placement">
                <option value="header" <?php selected( get_option('placement'), 'header' ); ?>>Header</option>
                <option value="footer" <?php selected( get_option('placement'), 'footer' ); ?>>Footer</option>
            </select>
        </td>
        <td><p>Select 'header' to place the JS in the header, or 'footer' to place it in the footer.</p></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Page ID</th>
        <td><input type="text" name="page_id" value="<?php echo esc_attr( get_option('page_id') ); ?>" title="Enter the ID of the page where the JS should be included, or 'all' for all pages." /></td>
        <td><p>Enter the ID of the page where the JS should be included, or 'all' for all pages.</p></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php 
} 

// Add the custom JS to the selected location
function add_custom_js() {
    $custom_js = get_option('custom_js');
    $placement = get_option('placement');
    $page_id = get_option('page_id');

    if(is_page($page_id) || $page_id == 'all') {
        if($placement == 'header') {
            add_action('wp_head', function() use ($custom_js) {
                echo '<script>' . $custom_js . '</script>';
            });
        } else if($placement == 'footer') {
            add_action('wp_footer', function() use ($custom_js) {
                echo '<script>' . $custom_js . '</script>';
            });
        }
    }
}

add_action('wp', 'add_custom_js');
?>