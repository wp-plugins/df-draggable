<?php
/**
 * @package DF Draggable
 * @version 1.13.2
 * @url	http://wordpress.org/extend/plugins/df-draggable/
**/

define('DF_DRAGGABLE_PAGE_TITLE', 'DF Draggable Settings');
define('DF_DRAGGABLE_MENU_TITLE', 'DF Draggable');

function df_draggable_admin_init() {
    wp_register_style('df-draggable-admin.css', DF_DRAGGABLE_PLUGIN_URL . 'df-draggable-admin.css');
    wp_enqueue_style('df-draggable-admin.css');    
}

function df_draggable_admin_enqueue_scripts( $hook_suffix ) {
    
    $df_draggable_admin_pages = array(
                                    'settings_page_df-draggable'                               
                                );
       
    if(in_array($hook_suffix, $df_draggable_admin_pages)) {
        wp_enqueue_style( 'farbtastic' );
        wp_enqueue_script( 'farbtastic' );      
    }
}

add_action('admin_init', 'df_draggable_admin_init');

function admin_load_into_head() { ?>
<script type="text/javascript">
 
  jQuery(document).ready(function() {
    jQuery('#ilctabscolorpicker').hide();
    jQuery('#ilctabscolorpicker').farbtastic("#dragBGcolor");
    jQuery("#dragBGcolor").click(function(){jQuery('#ilctabscolorpicker').slideToggle()});
  });
 
</script>
<?php } 

function df_draggable_menu() {
    
    $plugin_menu = add_submenu_page('options-general.php', DF_DRAGGABLE_PAGE_TITLE, DF_DRAGGABLE_MENU_TITLE, 'manage_options', 'df-draggable', 'df_draggable_settings_page');
    
    add_action( 'admin_head-'. $plugin_menu, 'admin_load_into_head' );
    
    add_action( 'admin_enqueue_scripts', 'df_draggable_admin_enqueue_scripts' ); //call the admin javascript files
    
}

add_action( 'admin_menu', 'df_draggable_menu' );


function df_draggable_createform($form_id, $action, $button_text = "Submit", $errors = array(), $makeDraggableClassSetting = null, $containment = "window", $dragBGcolor = "#efefef", $snap = true, $iframeFix = "true", $handle = null) {
  
    if (!empty($errors)) {    
        echo '<div id="df-admin-errors" class="df-admin-errors"><ul>';
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo '</ul></div>';
    }
    
    
    echo '<form id="'.$form_id.'" method="post" action="'.$action.'">';
      
    echo '<table cellspacing="0" cellpadding="0" class="df-admin-update-table">';
    
    echo '<tr>
            <td class="df-admin-update-table-th">Class of element to make draggable</td>
            <td><input id="makeDraggableClass" name="makeDraggableClass" type="text" maxlength="255" value="'. stripslashes($makeDraggableClassSetting) .'" /><br />
                <small>If you want to disable the draggable content, please enter \'none\' (without quotation marks)</small></td>
        </tr>';  
    
    
    echo '<tr>
            <td class="df-admin-update-table-th">Where are the elements allowed to be dragged (contained)?</td>
            <td>
            <select id="containment" name="containment">
                <option id="parent" value="parent" ';
                if ($containment == "parent") { echo "selected='selected'"; }
                echo '>Parent</option>
                    
                <option id="document" value="document" ';
                if ($containment == "document") { echo "selected='selected'"; }
                echo '>Document</option>
                    
                <option id="window" value="window" ';
                if ($containment == "window") { echo "selected='selected'"; }
                echo '>Window</option>
            </select>
            <p><small>
            <strong>Parent: </strong> - Constrains the draggable element to its parent element<br />
            <strong>Document: </strong> - Constrains the draggable element to the HTML page height/width<br />
            <strong>Window: </strong> - Constrains the draggable element to the viewable browser window
            </small></p>          
            </td>
        </tr>';  

    echo '<tr>
            <td class="df-admin-update-table-th">Background color of the draggable element, whilst being dragged</td>
            <td><input id="dragBGcolor" name="dragBGcolor" type="text" maxlength="255" value="'. stripslashes($dragBGcolor) .'" />
                <p><small>Leave this blank, or type <strong>transparent</strong> if you do not want a colour</small></p><div id="ilctabscolorpicker"></div>
            </td>
            </tr>';  
    
    echo '<tr>
            <td class="df-admin-update-table-th">Do you want the draggable elements to snap (align) with other dragged elements?</td>
            <td>
            <select id="snap" name="snap">
                <option id="true" value="true" ';
                if ($snap == "true") { echo "selected='selected'"; }
                echo '>True</option>
                    
                <option id="false" value="false" ';
                if ($snap == "false") { echo "selected='selected'"; }
                echo '>False</option>
               
            </select>   
            <p><small>Elements will snap together when close to each other</small></p>
            </td>
        </tr>'; 
                
                
   echo '<tr>
            <td class="df-admin-update-table-th">Apply the iFrame Fix? (recommended)</td>
            <td>
            <select id="iframeFix" name="iframeFix">
                <option id="true" value="true" ';
                if ($iframeFix == "true") { echo "selected='selected'"; }
                echo '>Yes</option>
                    
                <option id="false" value="false" ';
                if ($iframeFix == "false") { echo "selected='selected'"; }
                echo '>No</option>

            </select>
            <p><small>You can turn this off if you are experiencing problems</small></p>          
            </td>
        </tr>'; 
                
                
    echo '<tr>
            <td class="df-admin-update-table-th">Class name for child element of draggable element to fix drag function.</td>
            <td><input id="handle" name="handle" type="text" maxlength="255" value="'. $handle .'" />
                <p><small>Leave this blank to apply drag function to entire draggable element</small></p>
            </td>
            </tr>';
                
    
    echo '<tr>
            <td class="df-admin-update-table-th"></td>
            <td><input id="submit" name="submit" type="submit" value="'.$button_text.'" /></td>
        </tr>';
    
    echo '</table>';
    
    echo '</form>';
            
}
function df_draggable_settings_page() { ?>

<div id="df_draggable_admin">
    
    <h2><?php echo DF_DRAGGABLE_PAGE_TITLE; ?></h2>
    
    <p>This plugin will make any element draggable: Images <code>img</code>, 
        Divs <code>div</code>, Tables <code>table</code>, 
        Headings <code>h1, h2, h3, h4 ...</code>, Paragraphs <code>p</code>, 
        Lists <code>ul, ol</code>, Links <code>a</code>, etc...</p>

    <p>Use the form below to update the settings for draggable elements</p>

    <?php 
        if (isset($_POST['submit'])) { 

            $errors = array();

            if (empty($_POST['makeDraggableClass'])) {
                $errors[] = "You must enter a class to make draggable, default is 'post'";
            }  

            if (count($errors) != 0) {

                df_draggable_createform("df-admin-update", 
                                        currentURL(), 
                                        "Save Settings",
                                        $errors,
                                        $_POST['makeDraggableClass'],
                                        $_POST['containment'],
                                        $_POST['dragBGcolor'],
                                        $_POST['snap'],
                                        $_POST['iframeFix'],
                                        $_POST['handle']
                                        );

            } else {
                
                $df_draggable_options = array();
                $df_draggable_options['makeDraggableClass']     = $_POST['makeDraggableClass'];
                $df_draggable_options['containment']            = $_POST['containment'];
                $df_draggable_options['dragBGcolor']            = $_POST['dragBGcolor'];
                $df_draggable_options['snap']                   = $_POST['snap'];
                $df_draggable_options['iframeFix']              = $_POST['iframeFix'];
                $df_draggable_options['handle']                 = $_POST['handle'];
                
                /* UPDATE SETTINGS */
                
                try {
                    update_option('df-draggable-options', serialize($df_draggable_options));
                    echo "<p class='df-admin-success'>The settings have been updated succesffuly.</p>";
                    df_draggable_createform("df-admin-update", 
                                        currentURL(), 
                                        "Save Settings",
                                        $errors,
                                        $_POST['makeDraggableClass'],
                                        $_POST['containment'],
                                        $_POST['dragBGcolor'],
                                        $_POST['snap'],
                                        $_POST['iframeFix'],
                                        $_POST['handle']
                                        );
                    
                } catch (Exception $e) {
                    
                    
                    echo "<p class='df-admin-errors'>There was an error updating the settings. {$e->getMessage()}</p>";
                    df_draggable_createform("df-admin-update", 
                                        currentURL(), 
                                        "Save Settings",
                                        $errors,
                                        $_POST['makeDraggableClass'],
                                        $_POST['containment'],
                                        $_POST['dragBGcolor'],
                                        $_POST['snap'],
                                        $_POST['iframeFix'],
                                        $_POST['handle']
                                        );
                    
                } 




            }



        } else {
            
            $df_draggable_options = unserialize(get_option('df-draggable-options'));
            
            if(!empty($df_draggable_options)) {
                $makeDraggableClass = $df_draggable_options['makeDraggableClass'];
                $containment        = $df_draggable_options['containment']; 
                $dragBGcolor        = $df_draggable_options['dragBGcolor'];
                $snap               = $df_draggable_options['snap'];
                $iframeFix          = $df_draggable_options['iframeFix'];
                $handle             = $df_draggable_options['handle'];
            } else {
                $makeDraggableClass = "none";
                $containment        = null;
                $dragBGcolor        = null;
                $snap               = null;
                $iframeFix          = true;
                $handle             = null;    
            }
            
            df_draggable_createform("df-admin-update", 
                                        currentURL(), 
                                        "Save Settings",
                                        null,
                                        $makeDraggableClass,
                                        $containment,
                                        $dragBGcolor,
                                        $snap,
                                        $iframeFix,
                                        $handle
                                    );
        }     
        ?>

    </div>

<?php } //end df_draggable_settings_page() ?>