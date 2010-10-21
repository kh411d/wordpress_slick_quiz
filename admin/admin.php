<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/
// add to menu
add_action('admin_menu', 'wpsq_addmenu');

function wpsq_addmenu()
{
    add_menu_page('Slick Quiz', 'Slick Quiz', 8, WPSQ_FOLDER, 'wpsq_display_menu_content');
    add_submenu_page(WPSQ_FOLDER, 'Add Quiz', 'Add Quiz', 8, 'wpsq-addedit', 'wpsq_display_menu_content');
    add_submenu_page(WPSQ_FOLDER, 'Manage Participant', 'Manage Participant', 8, 'wpsq-manage-participant', 'wpsq_display_menu_content');   
    add_options_page('Slick Quiz Setting', 'Slick Quiz Setting', 8,'wpsq-settings', 'wpsq_display_menu_content');
}

function wpsq_display_menu_content()
{
    switch ($_GET["page"]) {
        case 'wpsq-addedit':
            include_once (dirname(__FILE__) . '/wpsq-addedit.php');            
        	wpsq_addedit_quiz();
            break;
        case 'slick-quiz':
        	include_once (dirname(__FILE__) . '/wpsq-manage-quiz.php');
          wpsq_manage_quiz();
          break;
        case 'wpsq-manage-participant':
        	include_once (dirname(__FILE__) . '/wpsq-manage-participant.php');
          wpsq_manage_participant();
          break;    
        case 'wpsq-settings':
        	wpsq_settings_menu();
        	break;    
        default:
          include_once (dirname(__FILE__) . '/wpsq-manage-quiz.php');
          wpsq_manage_quiz();
          break;
    }
}

function wpsq_settings_menu()
{
?>	
	<div class="wrap">
		<h2>Slick Quiz Settings</h2>
		
		<form action="options.php" method="POST">
		<?php wp_nonce_field('update-options'); ?>
		
		<table class="form-table">
						
		<tr valign="top">
		<th scope="row">Quiz <b>Email Message</b> Notification Text : </th>
		<td><textarea name="wpsq_emailmessage_notifier" rows="10" cols="50"><?=get_option('wpsq_emailmessage_notifier')?></textarea></td>
		</tr>
		
		</table>
		<input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="wpsq-setting" />
		
		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
		</p>
		
		</form>
	</div>
<?php	
}
 

