<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/
function wpsq_addedit_quiz()
{
    switch($_REQUEST['task']){
    	case 'new' : wpsq_create_form(); 
    				 			 break;
    	case 'edit' : wpsq_create_edit_form($_GET['quiz_id']); 
    				 				break;			 
    	case 'save' : wpsq_save_form();
    				 				$redirect_uri = get_option('siteurl') . '/wp-admin/admin.php?page=slick-quiz';
             				wpsq_force_redirect($redirect_uri);            		
    				 				break;	
    	case 'delete' : wpsq_delete_quiz($_GET['quiz_id']); 
    				  				$redirect_uri = get_option('siteurl') . '/wp-admin/admin.php?page=slick-quiz';
              				wpsq_force_redirect($redirect_uri);               	
    				 					break;					 		 
    	default : wpsq_prepare_form();
    			  		break;			
    }
}

function wpsq_prepare_form(){
?>
    <div class="wrap">
    <h2>Add/Edit Quiz</h2>
    <div class="tablenav">
    <form method="get" action="admin.php">
        <input type="hidden" name="page" value="wpsq-addedit" />
        <input type="hidden" name="task" value="new" />
        <select name="qnumber">
	        <option value="">--Select Question Qty--</option>
	        <?php
	        for($i=1;$i<=10;$i++){
	         echo '<option value="'.$i.'">'.$i.'</option>';
	        }
	         ?>
        </select>
        <select name="anumber">
	        <option value="">--Select Answer Qty/Question--</option>
	        <?php
	        for($i=1;$i<=5;$i++){
	         echo '<option value="'.$i.'">'.$i.'</option>';
	        }
	         ?>
        </select>
        <p class="submit"><input type="submit" value="Next &raquo;" /></p>
    </form>
    </div>
    </div>
<?php
}

function wpsq_create_form($quiz_id = NULL){
?>
<div class="wrap">
	<h2>Add/Edit Quiz</h2>
 <form action="admin.php?page=wpsq-addedit" method="post" ><Br>
 <b>Your Quiz Title</b> <br><input size="100" name='quiz_title' ><br><br>
<?php 	
 for($i=0;$i<$_GET['qnumber'];$i++){
   echo "<b>Question ".($i+1)."</b><Br>";
   echo "<input type='text' size='100' maxlength='100' id='q_".$i."' name='q_".$i."'><br>";
   echo "<ul>";
  for($j=0;$j<$_GET['anumber'];$j++){
    echo "<li>";
    echo "<input type='text' size='50' maxlength='50' id='a_".$i."_".$j."' name='a_".$i."_".$j."' ><input type='checkbox' name='r_".$i."_".$j."' value='1'><small>Check for right answer</small><br>";
    echo "</li>";
   }
   echo "</ul><br><br>";  
 }
 ?>
 <input type="hidden" name="task" value="save">
 <p class="submit"><input type="submit" value="Create Quiz &raquo;"></p>
 </form>
</div>
 <?php 
}

function wpsq_save_form(){
 global $wpdb;
	$question_array = array();$answer_array = array();$result_array = array();
	foreach ($_POST as $key => $value){
	 if(substr($key,0,2) == 'q_')$question_array[$key] = $value; 
	 if(substr($key,0,2) == 'a_')$answer_array[$key] = $value; 	 
	 if(substr($key,0,2) == 'r_')$result_array[$key] = $value; 
	}	
	$data = array();
	$data['question']=$question_array;
	$data['answer']=$answer_array;
	$data['result']=$result_array;
	if($_POST['quiz_id'])
	$wpdb->query("UPDATE ". $wpdb->wpsq_quiz ." SET `quiz_title`='".$wpdb->escape($_POST['quiz_title'])."', `quiz_data` = '" . serialize($data) . "' WHERE quiz_id = ".$_POST['quiz_id']);	
    else
    $wpdb->query("insert into ".$wpdb->wpsq_quiz."(quiz_title,quiz_data) VALUES('".$wpdb->escape($_POST['quiz_title'])."','".serialize($data)."')");	  
    
}


function wpsq_create_edit_form($quiz_id = NULL){
global $wpdb;
 $quiz = $wpdb->get_row("SELECT * FROM $wpdb->wpsq_quiz WHERE quiz_id = $quiz_id");
 $data_quiz = array();
 $data_quiz = unserialize($quiz->quiz_data);
 $qnumber = count($data_quiz['question']);
 $anumber = count($data_quiz['answer'])/$qnumber;
?>
<div class="wrap">
	<h2>Add/Edit Quiz</h2>
 <form name="post" action="admin.php?page=editquiz" method="post" id="post"><br>
 <b>Quiz Title</b> <br><input size="100" name='quiz_title' value="<?=$quiz->quiz_title ?>" ><br><br>
<?php 	
 for($i=0;$i<$qnumber;$i++){
   echo "<b>Question ".($i+1)."</b><Br>";
   echo "<input type='text' size='100' maxlength='100' id='q_".$i."' name='q_".$i."' value='".$data_quiz['question']['q_'.$i]."'><br>";
   echo "<ul>";
  for($j=0;$j<$anumber;$j++){
  	$check = $data_quiz['result']['r_'.$i.'_'.$j] == 1 ? " checked='checked' " : "";
    echo "<li>";
    echo "<input type='text' size='50' maxlength='50' id='a_".$i."_".$j."' name='a_".$i."_".$j."' value='".$data_quiz['answer']['a_'.$i.'_'.$j]."' ><input type='checkbox' name='r_".$i."_".$j."' ".$check." value='1'><small>Check for right answer</small><br>";
    echo "</li>";
   }
   echo "</ul><br><br>";  
 }
 ?>

 <input type="hidden" name="task" value="save">
 <input type="hidden" name="quiz_id" value="<?=$quiz_id ?>">
<p class="submit"><input type="submit" value="Save Changes &raquo;"></p>
 </form>
 <?php 
}

function wpsq_delete_quiz($quiz_id){
 global $wpdb;
 $wpdb->query("DELETE FROM ".$wpdb->wpsq_quiz." WHERE quiz_id = ".$quiz_id);	
}

