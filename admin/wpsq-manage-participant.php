<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/
function wpsq_manage_participant()
{
    global $wpdb;
    $options = array();

    if($_POST['email_notify']){
     if(count($_POST['cid'])>0){
	       foreach($_POST['cid'] as $value){
	       		$row = $wpdb->get_row("SELECT p.*,q.quiz_title FROM $wpdb->wpsq_quiz q, $wpdb->wpsq_quiz_participant p WHERE q.quiz_id = p.quiz_id AND p.id = $value");
	       	 	$wpdb->query("UPDATE $wpdb->wpsq_quiz_participant SET announce = 1 WHERE id = $value");      	        	 	
			     		                     	 	
		       	 	if($_POST['email_notify']){
	       	 		  $webmail = get_option('admin_email');
		            $header = 'From: ' . $webmail . "\r\n" . 'Reply-To: ' . $webmail . "\r\n" . 'Return-Path: ' . $webmail . "\r\n";		      
	       	 		  $emsg = str_replace("[twitter_name]", $row->profile_3, get_option('wpsq_emailmessage_notifier'));
	       	 	    $emsg = str_replace("[quiz_title]", $row->quiz_title, get_option('wpsq_emailmessage_notifier'));      	 		
	       	 		  mail($email,"Quiz [".$row->quiz_title."] Winner Announcement - ".get_option('blogname'),$emsg,$header);
	       	 	  }       	 	
	       	}      	
	   }
    }
      
    $options['search'] = $_GET['search'];
    $where = array();
    if (!empty($options['search'])) {
       if($options['search'] == 'PASS')$PASS = 1; elseif($options['search']=='NOT PASS') $PASS = 0; else $PASS = 'NULL'; 
    	$where[] = "(quiz_id LIKE '" . $wpdb->escape('%' . $options['search'] . '%') . "' OR
                     profile_1 LIKE '" . $wpdb->escape('%' . $options['search'] . '%') . "' OR                     
                     quiz_result = " . $wpdb->escape( $PASS )." )" ;
    }
     $sql = "SELECT
                *
            FROM
                " . $wpdb->wpsq_quiz_participant . "
            " . (0 < count($where) ? 'WHERE ' . implode(' AND ', $where) : '') . "
            ORDER BY
                quiz_id";
    $participant = $wpdb->get_results($sql);
    wpsq_display_participant($participant, $options);
}

function wpsq_display_participant($participant, $options = null)
{
    ?>
    <div class="wrap">

 <div class="tablenav" style="height:80px">
    <form name="searchform" id="searchform" action="admin.php" method="get">
        <input type="hidden" name="page" value="manpart" />
        <fieldset><legend>Search All field &hellip;</legend>
        <input type="text" name="search" id="search" value="<?php echo $options['search']; ?>" size="17" />
        <input type="submit" value="Filter &#187;" class="button-secondary" /><br><small>NB : Search quiz only by id number</small>
        </fieldset>
		<p>This is the list of all participant that joined the quiz<br>You may announce the quiz winner and notify them by email<br><b>PASS</b> result mean, Participant have guessed all the correct answer </p>
        
    </form>
</div>
    <br style="clear:both;" />
 <form name="listform" id="listform" action="admin.php?page=wpsq-manage-participant" method="POST">
    <table class="widefat">  
        <thead>      
         <tr>
         <td colspan="6">
         <input type="checkbox" checked="checked" name="email_notify" value="1">Notify by User <b>Email</b>          
         &nbsp;&nbsp;&nbsp;
         <input class="button" type="submit" value="Announce Participant To Receive Prizes &#187;">
         </td>
         </tr>
        <tr>

        <th scope="col"><div style="text-align: center">Quiz ID</div></th>
        <th scope="col">Email</th>       
        <th scope="col">Result</th>
		<th scope="col">Announce</th>
        </tr>
        </thead>
        <tbody id="the-list">
       
        <?php
        $cnt = 0;
        foreach ($participant as $user) {          
            echo "<tr ". ($cnt%2 == 0 ? ' class="alternate"' : '') . ">\n";
            echo "<th scope=\"row\" style=\"text-align: center\" width=\"100\">[wpsq-quiz=" . $user->quiz_id . "]</th>\n";
            echo "<th scope=\"row\" style=\"text-align: left\" >" . $user->profile_1 . "</th>\n";          
            echo "<td width=\"100\"><b>".($user->quiz_result ? "PASS" : "NOT PASS")."</b></td>\n";
          	if($user->announce == 1){
             echo "<td width=\"50\"><b>Announced</b></td>";
			}else{
			 echo "<td width=\"50\"><input type=\"checkbox\" id=\"cid[]\" name=\"cid[]\" value=\"".$user->id."\" ></td>";
			}
			echo "</tr>\n";

           
        }
        ?>        
        </tbody>
    </table>
    <div class="tablenav">
<br class="clear"/>
</div>
</form>
    </div>
    <?php
}