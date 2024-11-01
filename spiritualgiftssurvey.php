<?php
/*
Plugin Name: Spiritual Gifts Test
Plugin URI: https://www.ministryvitals.com/free-spiritual-gifts-test/
Description: Spiritual Gifts and S.H.A.P.E. Test to help church attendees find their place of service in the local church and other service organizations.
Version: 1.1.0
Author: Ministry Vitals
Author URI: https://www.ministryvitals.com
License: GPLv2
Copyright 2020  PLUGIN_AUTHOR_NAME 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) exit;

/**
 * mv_spiritual_gifts_survey
 */
class mvspg_spiritual_gifts_survey
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        add_shortcode('spiritual_gifts', array($this, 'mvspg_spiritual_gifts_shortcode_func'));
        add_shortcode('spiritual_gifts_shape', array($this, 'mvspg_spiritual_gifts_shape_shortcode_func'));
        add_action('admin_menu', array($this, 'mvspg_spg_menu'));
        add_action('wp_enqueue_scripts', array($this, 'mvspg_spg_enqueue_style'));
        add_action('admin_enqueue_scripts', array($this, 'mvspg_admin_style_scripts'));
        add_action('plugins_loaded', array($this, 'mvspg_saveformemailsurveynotif'));
        //add_action('wp_enqueue_scripts', array($this, 'spg_enqueue_script'));
    }

    /**
     * mvspg_saveformemailsurveynotif
     *
     * @return void
     */
    function mvspg_saveformemailsurveynotif()
    {
        if ($_POST['action'] != 'saveformemailsurveynotif')
            return;
        include_once(MVSPG_PLUGIN_DIR . '/admin/model/admin.php');
        $admin_model = new mvspg_admin_model();
        $admin_model->mvspg_saveformemailsurveynotif();
        $url = trailingslashit(admin_url()) . 'admin.php?page=spiritual_gifts_edit_menu';
        wp_redirect($url);
        exit;
    }
    /**
     * mvspg_spg_enqueue_style
     *
     * @return void
     */
    function mvspg_spg_enqueue_style()
    {
        global $post;
        if (!has_shortcode($post->post_content, 'spiritual_gifts') &&  !has_shortcode($post->post_content, 'spiritual_gifts_shape'))
            return;
        wp_enqueue_style('style1', plugins_url('assets/style.css', __FILE__));
    }
    /**
     * mvspg_spg_enqueue_script
     *
     * @return void
     */
    function mvspg_spg_enqueue_script()
    {
        global $post;
        if (!has_shortcode($post->post_content, 'spiritual_gifts') &&  !has_shortcode($post->post_content, 'spiritual_gifts_shape'))
            return;
        wp_enqueue_script('script2', plugins_url('assets/script.js', __FILE__), array('jquery'), false, true);
    }
    /**
     * mvspg_admin_style_scripts
     *
     * @return void
     */
    function mvspg_admin_style_scripts()
    {
        $okarr = array('spiritual_gifts_edit_menu', 'spiritual_gifts_upgrade');
        if (!in_array($_GET['page'], $okarr))
            return;
        wp_enqueue_style('spg_css', '//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css');
    }
    /**
     * mvspg_spg_menu
     *
     * @return void
     */
    function mvspg_spg_menu()
    {
        add_menu_page('Spiritual Gifts Test', 'Spiritual Gifts Test',  'manage_options', 'spiritual_gifts_edit_menu', array($this, 'mvspg_spiritual_gifts_settings_page'));
    }

    /**
     * mvspg_spiritual_gifts_settings_page
     *
     * @return void
     */
    function mvspg_spiritual_gifts_settings_page()
    {
        include_once('admin/view/settings.php');
    }
    /**
     * mvspg_spiritual_gifts_shortcode_func
     *
     * @return void
     */
    function mvspg_spiritual_gifts_shortcode_func()
    {
        return $this->mvspg_spiritual_gifts_write_form("false");
    }
    /**
     * mvspg_spiritual_gifts_shape_shortcode_func
     *
     * @return void
     */
    function mvspg_spiritual_gifts_shape_shortcode_func()
    {
        return $this->mvspg_spiritual_gifts_write_form("true");
    }

    /**
     * mvspg_spiritual_gifts_write_form
     *
     * @param  mixed $shape
     * @return void
     */
    function mvspg_spiritual_gifts_write_form($shape )
    {
        $return_var = "";
        $my_arr = $this->mvspg_spiritual_gifts_create_array();
        $my_types_arr = $this->mvspg_spiritual_gifts_create_types();
        $count_my_arr = $count_my_arrtypes = 0;
        foreach ($my_arr as $marr) {
            $count_my_arr += count($marr);
        }
        $count_my_arrtypes = count($my_types_arr);
        //    echo '<pre>'.print_r($my_types_arr,1).'</pre>';
        if ($_POST['action'] == 'result') {
            include_once('get_results.php');
            $spg_get_results = new mvspg_get_results;
            $return_var .= $spg_get_results->mvspg_get_all_score($my_types_arr, $shape );
            return $return_var;
        }
        $return_var .= "<form name='mySurvey' method='post' >";
        if ($shape == "false") {
            $return_var .= '<h2>Spiritual Gifts</h2>' . $this->mvspg_get_spiritual_gifts($my_arr, $count_my_arr, $count_my_arrtypes, $my_types_arr);
        } else {
            $return_var .= "<div id='shape-survey' class='shape-related'><h2 style='text-align: center'>S.H.A.P.E. Test</h2><h2>[S]piritual Gifts</h2>";
            $return_var .= $this->mvspg_get_spiritual_gifts($my_arr, $count_my_arr, $count_my_arrtypes, $my_types_arr);
            $return_var .= $this->mvspg_get_heart();
            $return_var .= $this->mvspg_get_abilities();
            $return_var .= $this->mvspg_get_personality();
            $return_var .= $this->mvspg_get_experience();
        }
        $return_var .= "<div id='submit-form'>
    <h2 style='margin-top: 20px'>Submit Information</h2>
    Thank you for taking the time to fill out this survey.  A copy will be sent to you as well as a staff member who will be able to help you find your place of service with us and/or in the community.
    <p style='text-align: center'>Your Name:<br />
    <input type='text' name='userName' /></p>
    <p style='text-align: center'>Your Email Address:<br />
    <input type='text' name='userEmailAddress' /></p>
    <p style='text-align: center; position: absolute; margin-left: -9999px'>I am a spam bot if I fill in the following field:<br />
    <input type='text' name='anotherField' /></p>
    <p style='text-align: center'><input type='submit' value='Submit Form!' /></p>
    <script type='text/javascript'> //jQuery(document).ready(function(){previewResults();});</script>
</div><!-- submit-form -->";
        //hidden variables that need to be passed to 'gifts-email.php'
        $return_var .= "<input type='hidden' name='typesCount' value='" . $count_my_arrtypes . "' />";
        $return_var .= "<input type='hidden' name='totquesspg' value='" . $count_my_arr . "' />";
        $return_var .= "<input type='hidden' name='action' value='result' />";
        $return_var .= "<input type='hidden' name='siteTitle' value='" . get_bloginfo('name') . "' />";
        $return_var .= "<input type='hidden' name='shape' value='" . $shape . "' />";
        $return_var .= "</form>";
$return_var .= '<div style="text-align: center; font-size: 12px;"><a target="_blank" href="https://www.ministryvitals.com/">Powered by Ministry Vitals</a></div>';
        return $return_var;
    }
    //create the array spiritual_gifts_array and populate it with the 128 S questions    
    /**
     * mvspg_get_spiritual_gifts
     *
     * @param  mixed $my_arr
     * @param  mixed $count_my_arr
     * @param  mixed $count_my_arrtypes
     * @param  mixed $my_types_arr
     * @return void
     */
    function mvspg_get_spiritual_gifts($my_arr, $count_my_arr, $count_my_arrtypes, $my_types_arr)
    {
        $return_var = '';
        $return_var .= "<div id='spiritual-gifts'>
        <p>Rate how often each statement is reflected in your life:</p>";
        $return_var .= '<input type="hidden" id="count_my_arr" value="' . $count_my_arr . '"/>';
        $return_var .= '<input type="hidden" id="count_my_arrtypes" value="' . $count_my_arrtypes . '"/>';
        $num = 1;
        foreach ($my_arr as $t => $v) {
            //  echo '<pre>' . print_r($v, 1) . '</pre>';
            foreach ($v as $i1 => $v1) {
                if ($i1 % 2 == 1) {
                    $class = "even";
                } else {
                    $class = "odd";
                }
                $return_var .= "<div class='" . $class . "'>" . $num . '. ' . $v1 .
                    "<div class='ratings'>Rarely
            <input type='radio' name='strength[" . $t . "][" . $i1 . "]' value='0' />
            <input type='radio' name='strength[" . $t . "][" . $i1 . "]' value='1' />
            <input type='radio' name='strength[" . $t . "][" . $i1 . "]' value='2' checked />
            <input type='radio' name='strength[" . $t . "][" . $i1 . "]' value='3' />
            <input type='radio' name='strength[" . $t . "][" . $i1 . "]' value='4' />
            Often</div></div>";
                $num++;
            }
        }
        $return_var .= "</div>";
        return $return_var;
    }
    /**
     * mvspg_get_heart
     *
     * @return void
     */
    function mvspg_get_heart()
    {
        return "<h2>[H]eart</h2>
    Three things I love to do:<br />
    <textarea name='heart1_text'></textarea><br />
    <textarea name='heart2_text'></textarea><br />
    <textarea name='heart3_text'></textarea><br />
    <br />
    Who I love to work with most, and the age or type of people:<br />
    <textarea name='heart4_text'></textarea><br />
    <br />
    Church issues, ministries, or possible needs that excite or concern me the most:<br />
    <textarea name='heart5_text'></textarea><br />
    <br />
    If I knew I couldn't fail, this is what I would attempt to do for God with my life:<br />
    <textarea name='heart6_text'></textarea>
    <br />";
    }
    /**
     * mvspg_get_abilities
     *
     * @return void
     */
    function mvspg_get_abilities()
    {
        return  "<h2>[A]bilities</h2>
    My current vocation:
    <textarea name='abilities1_text'></textarea>
    Other jobs I have experience in:
    <textarea name='abilities2_text'></textarea>
    Special talents/skills that I have:
    <textarea name='abilities3_text'></textarea>
    I have taught a class or seminar on:
    <textarea name='abilities4_text'></textarea>
    I feel my most valuable personal asset is:
    <textarea name='abilities5_text'></textarea>
    <br />";
    }
    /**
     * mvspg_get_personality
     *
     * @return void
     */
    function mvspg_get_personality()
    {
        return "<h2>[P]ersonality</h2>
    Select where you lean in these differing personality traits:<br />
    <div class='personality-container'><div class='left-personality'>Extroverted</div>
            <input type='radio' id ='personality_1_1' name='personality_1' value='Extroverted' />
            <input type='radio' id ='personality_1_2' name='personality_1' value='Introverted' />
            <div class='right-personality'>Introverted</div></div>
    <div class='personality-container'><div class='left-personality'>Thinker</div>
            <input type='radio' id ='personality_2_1' name='personality_2' value='Thinker' />
            <input type='radio' id ='personality_2_2' name='personality_2' value='Feeler' />
            <div class='right-personality'>Feeler</div></div>
    <div class='personality-container'><div class='left-personality'>Routine</div>
            <input type='radio' id ='personality_3_1' name='personality_3' value='Routine' />
            <input type='radio' id ='personality_3_2' name='personality_3' value='Variety' />
            <div class='right-personality'>Variety</div></div>
    <div class='personality-container'><div class='left-personality'>Reserved</div>
            <input type='radio' id ='personality_4_1' name='personality_4' value='Reserved' />
            <input type='radio' id ='personality_4_2' name='personality_4' value='Expressive' />
            <div class='right-personality'>Expressive</div></div>
    <div class='personality-container'><div class='left-personality'>Cooperative</div>
            <input type='radio' id ='personality_5_1' name='personality_5' value='Cooperative' />
            <input type='radio' id ='personality_5_2' name='personality_5' value='Competitive' />
            <div class='right-personality'>Competitive</div></div>
    <br />";
    }
    /**
     * mvspg_get_experience
     *
     * @return void
     */
    function mvspg_get_experience()
    {
        return     "<h2>[E]xperience</h2>
    My Testimony of how I became a Christian
    <textarea name='experience1_text'></textarea>
    Other significant spiritual experiences that stand out in my life are:
    <textarea name='experience2_text'></textarea>
    These are the kinds of trials or problems I could relate to and encourage a fellow Christian in:
    <textarea name='experience3_text'></textarea>
    Ministry Experience (Where I have served in the past, if applicable, including Church Name, City/State, Position, Years Involved)
    <textarea name='experience4_text'></textarea>
</div>";
    }
    /**
     * mvspg_spiritual_gifts_create_array
     *
     * @return void
     */
    function mvspg_spiritual_gifts_create_array()
    {
        $my_json = file_get_contents(plugins_url('questions/spiritualgifts.inc', __FILE__));
        $s_statements = json_decode($my_json, 1);
        return $s_statements;
    }
    /**
     * mvspg_spiritual_gifts_create_types
     *
     * @return void
     */
    function mvspg_spiritual_gifts_create_types()
    {
        $my_json = file_get_contents(plugins_url('questions/spiritualgiftstypes.inc', __FILE__));
        $s_statements = json_decode($my_json, 1);
        return $s_statements;
    }
}
$mv_spiritual_gifts_survey = new mvspg_spiritual_gifts_survey;
if (!defined('MVSPG_PLUGIN_URL'))
    define('MVSPG_PLUGIN_URL', plugins_url('', __FILE__));
if (!defined('MVSPG_PLUGIN_DIR'))
    define('MVSPG_PLUGIN_DIR', dirname(__FILE__));
