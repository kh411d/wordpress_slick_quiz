<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/
function wpsq_the_content($content)
{
     preg_match_all('!\[wpsq-quiz=([0-9]+)\]!isU',$content,$matches);

    foreach (array_keys($matches[0]) as $i) {
            if (isset($_POST['answer']) && is_array($_POST['answer'])) {         
                $content = str_replace($matches[0][$i], wpsq_save_answer($_POST['answer'],$_POST['quiz_id']), $content);
            } else {
                $content = str_replace($matches[0][$i], wpsq_generate_quiz($matches[1][$i]), $content);
            }
        }
    

    return $content;
}

function wpsq_the_title($title)
{ 
	global $wpdb;
    preg_match_all('!\[wpsq-quiz=([0-9]+)\]!isU',$title,$matches);

    foreach (array_keys($matches[0]) as $i) {
        $quiz = $wpdb->get_row("SELECT * FROM ".$wpdb->wpsq_quiz." WHERE quiz_id = ".$matches[1][$i]);  
  
        if (!empty($quiz) && is_object($quiz)) {
            $title = str_replace($matches[0][$i], $quiz->quiz_title, $title);
        }
    }

    return $title;
}

function wpsq_generate_quiz($quiz_id){
 global $wpdb;
 $quiz = $wpdb->get_row("SELECT * FROM $wpdb->wpsq_quiz WHERE quiz_id = $quiz_id");
 $data_quiz = array();
 $data_quiz = unserialize($quiz->quiz_data);
 $qnumber = count($data_quiz['question']);
 $anumber = count($data_quiz['answer'])/$qnumber;
 ob_start();
  ?>
	<form name="post" action="<?php echo get_permalink(); ?>" method="post" id="post" style="text-align: left;">
	<ol >
  <br>

	<?php
	 for($i=0;$i<$qnumber;$i++){
	   echo "<b>".($i+1)."</b>.&nbsp;";
	   echo $data_quiz['question']['q_'.$i]."<br>";
	   echo "<ul>";
	  for($j=0;$j<$anumber;$j++){
	    echo "<li style='list-style-type:none'>";
	    echo "<input type='radio' size='50' maxlength='50' value='r_".$i."_".$j."' name='answer[".$i."]'  >".$data_quiz['answer']['a_'.$i.'_'.$j];
	    echo "</li>";
	   }
	   echo "</ul style='list-style-type:none'><Br>";  
	 }
	?>

	

	</ol>
  
	<b>If you win the quiz, Notify by EMAIL at </b><input type="text" size="20" name="wpsq_email" /><br>
  <br><input type="submit" name="submit" value="Send Quiz" />

	
	<input type="hidden" name="quiz_id" id="quiz_id" value="<?php echo $quiz_id; ?>" />
	
	</form><?php 
$contents = ob_get_contents();
ob_end_clean();
return $contents;

}

function wpsq_save_answer($answer=array(),$quiz_id){
 global $wpdb;
 $email = $_POST[wpsq_email];
 $check = $wpdb->get_row("SELECT count(*) as total FROM $wpdb->wpsq_quiz_participant WHERE quiz_id = $quiz_id AND profile_1 = '$email'");
  $notify = '';
 if($check['total']<=0){  
	 $wpdb->query("INSERT INTO ".$wpdb->wpsq_quiz_participant." (`quiz_id`,`profile_1`,`quiz_result`) VALUES (".$quiz_id.",'".$email."',".wpsq_participant_result($quiz_id,$answer).")");
     $notify .= wpsq_display_notification('success');
 }else{
 	if($check['total']>0)
 	$notify .= wpsq_display_notification('failure_already_answer')."<br>";
 }
 
 echo "<Br><p class='postmetadata alt' align='center'><small>".$notify."</small></p>";
 
}

function wpsq_participant_result($quiz_id,$answer){
 global $wpdb; 
   $quiz = $wpdb->get_row("SELECT * FROM $wpdb->wpsq_quiz WHERE quiz_id = $quiz_id");
	 $data_quiz = array();
	 $data_quiz = unserialize($quiz->quiz_data);	 
	 $pass = 0;	
	 foreach($answer as $value){
	  $pass += array_key_exists($value, $data_quiz[result]) ? 1 : 0;
	 }
	 $result = ($pass == count($data_quiz['result'])) ? 1 : 0;	
	 return $result;
}

function wpsq_display_notification($param){
	switch($param){
		case 'failure_already_answer': 
			return "<b>Sorry You have already answer this quiz !!!</b>";
			break;
		case 'success': 
			return "<b>Your result is being proceed we will contact you ASAP, if you pass the quiz. <br> Thank You</b>";
			break;
	}
}

function wpsq_force_redirect($uri){
 		echo "<script language='javascript1.1'>";
    echo "location.href=\"".$uri."\";";
    echo "</script>";
}

