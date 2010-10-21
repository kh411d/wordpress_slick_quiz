<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/

function wpsq_install()
{
    global $wpdb;
    $query1 = "CREATE TABLE $wpdb->wpsq_quiz (
				  quiz_id int(11) NOT NULL auto_increment,
				  quiz_title varchar(255) NULL,
				  quiz_data longtext,
				  PRIMARY KEY  (quiz_id)
				);";   
    $query2= "CREATE TABLE IF NOT EXISTS $wpdb->wpsq_quiz_participant (
					  id int(11) NOT NULL auto_increment,
					  quiz_id int(11) NULL,
					  profile_1 varchar(100) NULL,
					  profile_2 varchar(100) NULL,
					  profile_3 varchar(100) NULL,
					  quiz_result tinyint(1) NULL,
					  announce tinyint(1) NOT NULL default 0,
					  PRIMARY KEY  (id)
					);";
    $wpdb->query($query1);
    $wpdb->query($query2);
}

register_activation_hook(WPSQ_FOLDER . '/bootstrap.php', 'wpsq_install');