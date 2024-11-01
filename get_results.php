
<?php 
 if (!defined('ABSPATH')) exit;
/**
 * mvspg_get_results
 */
class mvspg_get_results
{  
  /**
   * __construct
   *
   * @return void
   */
  function __construct()
  { }
  /**
   * mvspg_get_all_score
   *
   * @param  mixed $my_types_arr
   * @return void
   */
  public function mvspg_get_all_score($my_types_arr, $isshape)
  {
    $score = array();
    $typeCount = count($my_types_arr); 
    $strength = (array) $_POST['strength'];
    //$isshape = sanitize_text_field($_POST['shape']);
    $maxScore = (intval($_POST['totquesspg']) / $typeCount) * 4;
    foreach ($strength as $type => $str) {
      foreach ($str as $v) {
        $score[$type] = $score[$type] + $v;
      }
    }
    $return_var = '';
    $head = "<div id='gifts-results'>";
    if ($isshape != 'false')
      $head .= "<p>
    <h2 style='background: #555555; color: white; text-align: center; padding: 10px'>[S]piritual Gifts</h2>
  </p>";
    else
      $head .= "<h3 style='text-align: center'>Spiritual Gifts Scores</h3>";
    $return_var .= "<div>
    <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>All Scores</div><br />
    <table>";
    $r = 1;
    foreach ($my_types_arr as $i => $v) {
      //echo $r . '<br>';
      $scoreValue = intval($score[$i] * (100 / $maxScore));
      if ($r == 1) {
        $return_var .= "<tr>";
      }
      $return_var .= "<td>" . $i . "</td>
        <td>" . $scoreValue . "%</td>";
      if ($r == 2) {
        $return_var .= "
      </tr>";
        $r = 1;
      } else
        $r++;
      $scoreval_arr[$i] = $scoreValue;
    }
    $return_var .= "</table>
  </div>";
    $return_var .= "</div>";
    arsort($scoreval_arr);
    $scoreval_arr = array_slice($scoreval_arr, 0, 3);
    $get_top3reasons = $this->mvspg_get_top3reasons($scoreval_arr);
    if ($isshape != "false")
      $hape = $this->mvspg_get_hape();
    $return = $head . $get_top3reasons . $return_var . $hape;
    $this->mvspg_sendemail($return);
    return $return;
  }  
   
  /**
   * get_top3reasons
   *
   * @param  mixed $scoreval_arr
   * @return void
   */
  public function mvspg_get_top3reasons($scoreval_arr = array())
  {
    $s_typetext = file_get_contents(plugins_url('questions/spiritualgiftstypes.inc',__FILE__));
    $s_statements = json_decode($s_typetext, 1);
    $return = "<div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Top 3 Spiritual Gifts from Spiritual Gifts Survey</div><br />";
    foreach ($scoreval_arr as $type => $score) {
      $return .= "<div style='display: inline-block;margin-bottom: 10px;'><strong>" . $type . "</strong> - " . $score . "%</div><br />";
      $return .= "<div style='margin-bottom: 20px;'>" . $s_statements[$type] . "</div>";
    }
    return $return;
  }  
  /**
   * get_hape
   *
   * @return void
   */
  function mvspg_get_hape()
  {
    $hape_results = '';
    $hape_results .= "<p>
  <h2 style='background: #555555; color: white; text-align: center; padding: 10px'>[H]eart</h2>
</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Three things I love to do:</div>
</p>
<p>1. " . sanitize_text_field($_POST["heart1_text"]) . "</p>
<p>2. " . sanitize_text_field($_POST["heart2_text"]) . "</p>
<p>3. " . sanitize_text_field($_POST["heart3_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Who I love to work with most, and the age or type of people:</div>
</p>
<p>" . sanitize_text_field($_POST["heart4_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Church issues, ministries, or possible needs that excite or concern me the most:</div>
</p>
<p>" . sanitize_text_field($_POST["heart5_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>If I knew I couldn't fail, this is what I would attempt to do for God with my life:</div>
</p>
<p>" . sanitize_text_field($_POST["heart6_text"]) . "</p>";
    //[A]bilities
    $hape_results .= "<p>
  <h2 style='background: #555555; color: white; text-align: center; padding: 10px'>[A]bilities</h2>
</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>My current vocation:</div>
</p>
<p>" . sanitize_text_field($_POST["abilities1_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Other jobs I have experience in:</div>
</p>
<p>" . sanitize_text_field($_POST["abilities2_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Special talents/skills that I have:</div>
</p>
<p>" . sanitize_text_field($_POST["abilities3_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>I have taught a class or seminar on:</div>
</p>
<p>" . sanitize_text_field($_POST["abilities4_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>I feel my most valuable personal asset is:</div>
</p>
<p>" . sanitize_text_field($_POST["abilities5_text"]) . "</p>";
    //[P]ersonality
    $hape_results .= "<p>
  <h2 style='background: #555555; color: white; text-align: center; padding: 10px'>[P]ersonality</h2>
</p>
<p>
  <div style='font-weight: bold'>The personality traits that best fit me are:</div>
</p>
<p>" . sanitize_text_field($_POST["personality_1"]) . "</p>
<p>" . sanitize_text_field($_POST["personality_2"]) . "</p>
<p>" . sanitize_text_field($_POST["personality_3"]) . "</p>
<p>" . sanitize_text_field($_POST["personality_4"]) . "</p>
<p>" . sanitize_text_field($_POST["personality_5"]) . "</p>
";
    //[E]xperience
    $hape_results .= "<p>
  <h2 style='background: #555555; color: white; text-align: center; padding: 10px'>[E]xperience</h2>
</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>My Testimony of how I became a Christian:</div>
</p>
<p>" . sanitize_text_field($_POST["experience1_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Other significant spiritual experiences that stand out in my life are:</div>
</p>
<p>" . sanitize_text_field($_POST["experience2_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>These are the kinds of trials or problems I could relate to and encourage a fellow Christian in:</div>
</p>
<p>" . sanitize_text_field($_POST["experience3_text"]) . "</p>
<p>
  <div style='background: #aaaaaa; text-align: center; color: white; padding: 5px; font-weight: normal'>Ministry Experience (Where I have served in the past, if applicable, including Church Name, City/State, Position, Years Involved)</div>
</p>
<p>" . sanitize_text_field($_POST["experience4_text"]) . "</p>
";
    return $hape_results;
  }  
  /**
   * sendemail
   *
   * @param  mixed $testresults
   * @return void
   */
  function mvspg_sendemail($testresults)
  {
    include_once(MVSPG_PLUGIN_DIR . '/admin/model/admin.php');
    $admin_model = new mvspg_admin_model();
    $emailsettings = $admin_model->mvspg_survey_result_message();
     
    if ($emailsettings['toggle_emails'] != '1')
      return; 
    $toname = sanitize_text_field($_POST['userName']);
    $toemail = sanitize_email($_POST['userEmailAddress']);
    $fromname = $emailsettings['sendername'];
    $fromemail = $emailsettings['senderemail'];
    $subject = $emailsettings['subjectline_surveyresults'];
    $ccemailsarr = explode(',', $emailsettings['ccemails']);
    $headers[] = 'From: ' . $fromname . ' <' . $fromemail . '>';
    foreach ($ccemailsarr as $ccemail) {
      $headers[] = 'Cc: ' . $ccemail;
    }
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $message = nl2br($emailsettings['message_surveyresults']);
    $message = str_replace('[NAME]', $toname, $message);
    $message = str_replace('[TESTRESULT]', $testresults, $message);
    // echo $toemail.', '.$subject.', '.$message.', '.print_r($headers);
    wp_mail($toemail, $subject, $message, $headers);
  }
}