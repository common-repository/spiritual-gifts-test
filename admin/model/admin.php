<?php
if (!defined('ABSPATH')) exit;
/**
 * admin_model
 */
class mvspg_admin_model
{
  /**
   * __construct
   *
   * @return void
   */
  function __construct()
  { }
  /**
   * survey_result_message
   *
   * @return void
   */
  function mvspg_survey_result_message()
  {
    //delete_option('spg_emailsettings');
    $data_json = get_option('spg_emailsettings');
    $data = json_decode($data_json, 1);
    if (!$data)
      $data = $this->mvspg_default_survey_result_message();
    return $data;
  }
  /**
   * default_survey_result_message
   *
   * @return void
   */
  function mvspg_default_survey_result_message()
  {
    $data['sendername'] = get_option('blogname');
    $data['senderemail'] = get_option('admin_email');
    $data['subjectline_surveyresults'] = "Your S.H.A.P.E. Test Results";
    $data['message_surveyresults'] = "Hi [FIRSTNAME],\r\n\r\nCongratulations on completing the S.H.A.P.E. Test. We are confident this tool will help you and us take the best next steps in your faith journey as you seek to use your gifts for God's Kingdom!\r\nHere are the results of your assessment.\r\n\r\n[TESTRESULT]\r\n\r\nBlessings,";
    return $data;
  }
  /**
   * saveformemailsurveynotif
   *
   * @return void
   */
  function mvspg_saveformemailsurveynotif()
  {
    $spgsettings = sanitize_text_field($_POST['spgsettings']);
    if (!isset($_POST['spgsettings']) || !wp_verify_nonce($spgsettings, 'spgsettings_save')) {
      print 'Sorry, your nonce did not verify.';
      exit;
    }
    $data['sendername'] = sanitize_text_field($_POST['sendername']);
    $data['senderemail'] = sanitize_email($_POST['senderemail']);
    $data['ccemails'] = sanitize_text_field($_POST['ccemails']);
    $data['toggle_emails'] = intval($_POST['toggle_emails']);
    $data['subjectline_surveyresults'] = sanitize_text_field($_POST['subjectline_surveyresults']);
    $data['message_surveyresults'] = sanitize_textarea_field($_POST['message_surveyresults']);
    $data_json = json_encode($data);
    update_option('spg_emailsettings', $data_json);
  }
}
