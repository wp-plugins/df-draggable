<?php
/**
 * @package DF Draggable
 * @version 1.13.2
 * @url	http://wordpress.org/extend/plugins/df-draggable/
 */
/*
Plugin Name: DF Draggable 
Version: 1.13.2
Plugin URI: http://wordpress.org/extend/plugins/df-draggable/
Description: DF Draggable is a plugin for WordPress that enables you to make elements draggable utilising jQuery UI draggable and jQuery UI Touch Punch adding support for desktop and mobile browsers. 
Author: Dominic Fallows
Author URI: http://www.dominicfallows.com/apps-plugins/df-draggable/
License: GPLv2 or later
Tags: jquery, jquery ui, drag, drop, draggable, touch, punch

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


function define_globals() {
    global $wpdb;
    define('DF_DRAGGABLE_VERSION', '1.13.2');  
    define('DF_DRAGGABLE_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}

function init_activation() {
    
    //see if options already exist
    $df_draggable_options = unserialize(get_option('df-draggable-options'));
    
    
    if (!is_array($df_draggable_options)) {
        $df_draggable_options = array();
        $df_draggable_options['makeDraggableClass']     = "post";
        $df_draggable_options['containment']            = "window";
        $df_draggable_options['dragBGcolor']            = "#efefef";
        $df_draggable_options['snap']                   = true;
        $df_draggable_options['iframeFix']              = true;
        $df_draggable_options['handle']                 = "";
        //save the init options
        update_option('df-draggable-options', serialize($df_draggable_options));
    }
    

    //To call the option:
    //get_option('my-plugin-options') == "" ? "" : $new = unserialize(get_option('my-plugin-options')); 

}

define_globals();
require_once("includes/functions.php");
register_activation_hook(__FILE__, 'init_activation');

// direct file request handling
if (!function_exists('add_action')) { echo "You shouldn't be here!"; exit; }

//admin handling
if (is_admin()) { require_once dirname( __FILE__ ) . '/admin.php'; }


//init
function df_draggable_init() {
	wp_register_style('df-draggable-view.css', DF_DRAGGABLE_PLUGIN_URL . 'df-draggable-view.css');
	wp_enqueue_style('df-draggable-view.css');
	wp_enqueue_script("jquery");
	wp_enqueue_script('jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', array('jquery'), "1.8.16");
    //wp_register_script('df-draggable-view', DF_DRAGGABLE_PLUGIN_URL .'includes/df-draggable-view.js');
   	//wp_enqueue_script('df-draggable-view');
}
add_action('init', 'df_draggable_init');

//view handling
function load_into_head() {
    
    $df_draggable_options = unserialize(get_option('df-draggable-options'));
    
    if(!empty($df_draggable_options)) {
        $makeDraggableClass     = $df_draggable_options['makeDraggableClass'];
        $containment            = $df_draggable_options['containment']; 
        $dragBGcolor            = $df_draggable_options['dragBGcolor'];
        $snap                   = $df_draggable_options['snap'];
        $iframeFix              = $df_draggable_options['iframeFix'];
        $handle                 = $df_draggable_options['handle'];
    } else {
        $makeDraggableClass = null;
    }
?>
    
    <script type="text/javascript">
    
    if (typeof jQuery == 'undefined') {
        var head= document.getElementsByTagName('head')[0];
        var script= document.createElement('script');
        script.type= 'text/javascript';
        script.src= 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
        head.appendChild(script);
    }
    </script>
    <script type="text/javascript">if (typeof $ != 'undefined') { $.noConflict(); }</script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo DF_DRAGGABLE_PLUGIN_URL; ?>/includes/jquery.ui.touch-punch.min.js"></script>
    <?php
    if($makeDraggableClass !=null && $makeDraggableClass !="none") { ?>
    <script type="text/javascript">
        
        jQuery(document).ready(function() {
            
            jQuery('.<?php echo $makeDraggableClass; ?>').draggable( {
                containment: '<?php echo $containment; ?>',
                cursor: 'move',
                snap: <?php echo $snap; ?>,
		stack: ".<?php echo $makeDraggableClass; ?>",
                iframeFix: <?php echo $iframeFix; ?><?php if (!empty($handle)) { ?>,
                handle: '.<?php echo $handle; ?>'<?php } ?>
            });
            
        });
    </script>
    
    <?php if (!empty($handle)) { $classMove = $handle; } else { $classMove = $makeDraggableClass; } ?>
    <style type="text/css">
        .ui-draggable-dragging { background: <?php echo $dragBGcolor; ?>; }
        .<?php echo $classMove; ?>:hover { cursor: move; }
    </style>
    <?php } ?>
<?php
      
} 
add_action( 'wp_head', 'load_into_head' );


function df_draggable_view($content = '') {
              
    $df_draggable_html = "";
    
    $new_content = str_replace("[DF-DRAGGABLE]", $df_draggable_html, $content);
    
    return $new_content;
    
}

add_filter('the_content', 'df_draggable_view');

 

?>
