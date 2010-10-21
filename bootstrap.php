<?php
/*
Plugin Name: WordPress Slick Quiz Plugin
Description: It's a simple Quiz that providing Only Multiple Choice,Create Unlimited Question (hmm... hope so), Manage Participant Result, Easy Code to modify, well I think this is a beta version, use it at your own risk.
Author: Khalid Adisendjaja
Version: 0.1 Beta

Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/  
    global $wpdb;
    define('WPSQ_FOLDER', dirname(plugin_basename(__FILE__)));    
    
    // ADD TABLE POINTER
    $wpdb->wpsq_quiz = $wpdb->prefix . 'wpsq_quiz';
    $wpdb->wpsq_quiz_participant = $wpdb->prefix . 'wpsq_quiz_participant';
    // INSTALL DATABASE
    require_once (dirname(__FILE__) . "/install.php");
    // INCLUDE REQUIRED MODULE
    require_once (dirname(__FILE__) . "/functions.php");
    // LOAD ADMIN PANEL
    require_once (dirname(__FILE__) . "/admin/admin.php");
    
    add_filter('the_content', 'wpsq_the_content');
    add_filter('the_title', 'wpsq_the_title');
    add_filter('wp_title', 'wpsq_the_title');
