<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/
function wpsq_manage_quiz()
{
    global $wpdb;
    wp_enqueue_script('listman');
    $options = array();
    $options['search'] = $_GET['search'];
    $where = array();
    if (!empty($options['search'])) {
        $where[] = "quiz_title LIKE '" . $wpdb->escape('%' . $options['search'] . '%') . "'";
    }
     $sql = "SELECT
                *
            FROM
                " . $wpdb->wpsq_quiz . "
            " . (0 < count($where) ? 'WHERE ' . implode(' AND ', $where) : '') . "
            ORDER BY
                quiz_title";

    $quizzes = $wpdb->get_results($sql);
    wpsq_display_quiz($quizzes, $options);
}

function wpsq_display_quiz($quizzes, $options = null)
{
    ?>
    <div class="wrap">    
    <div class="tablenav" style="height:80px">
    <form name="searchform" id="searchform" action="admin.php" method="get">
        <input type="hidden" name="page" value="slick-quiz" />
        <fieldset><legend>Search Quiz Title&hellip;</legend>
        <input type="text" name="search" id="search" value="<?php echo $options['search']; ?>" size="17" />
        <input type="submit" value="Filter &#187;" class="button-secondary" />
        </fieldset>
<p>How to set up the Quiz ?<br>
    	Copy and Paste the quizID (ex: [wpsq-quiz=1]) into your TITLE <b>and</b> BODY field on a Post/Page.<br />
    And then Your quiz will be set up automatically.</p>
        
    </form>
    </div>
    <br style="clear:both;" />

    <table class="widefat">
        <thead>
        <tr>

        <th scope="col"><div style="text-align: center">QuizID</div></th>
        <th scope="col">Title</th>
        <th scope="col"></th>
        <th scope="col"></th>

        </tr>
        </thead>
        <tbody id="the-list">
        
        <?php
        $cnt = 0;
        foreach ($quizzes as $quiz) {
            $link = "admin.php?page=wpsq-addedit&task=delete&quiz_id=" . $quiz->quiz_id ;       
            echo "<tr " . ($cnt%2==0 ? ' class="alternate"' : '') . ">\n";
            echo "<th scope=\"row\" style=\"text-align: center\" width=\"100\">[wpsq-quiz=" . $quiz->quiz_id . "]</th>\n";
            echo "<td width=\"300\">" . wp_specialchars($quiz->quiz_title) . "</td>\n";
            echo "<td width=\"50\"><a href=\"admin.php?page=wpsq-addedit&task=edit&quiz_id=" . $quiz->quiz_id . "\" class=\"edit\">Edit</a></td>\n"; 
            echo "<td width=\"50\"><a href=\"" . $link . "\" class=\"delete\" >Delete</a></td>\n";
            echo "</tr>\n";           
        }
        ?>

        </tbody>
    </table>
<div class="tablenav">
<br class="clear"/>
</div>
    </div>
    <?php
}