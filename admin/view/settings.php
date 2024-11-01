<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">

  <h2>Spiritual Gifts Test</h2>

  <nav class="navbar navbar-expand-lg navbar-light pl-0">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">

      <span class="navbar-toggler-icon"></span>

    </button>

    <div class="bg-dark collapse navbar-collapse" id="navbarNavAltMarkup">

      <div class="navbar-nav">

        <a class="nav-item nav-link active text-white pr-4" href="<?php echo trailingslashit(admin_url()); ?>admin.php?page=spiritual_gifts_edit_menu">Settings</a>

        <a class="nav-item nav-link text-white pr-4" target="_blank" href="https://www.ministryvitals.com/free-spiritual-gifts-test/">Support</a>

        <a class="nav-item nav-link text-white pr-4" target="_blank" href="https://www.ministryvitals.com/">Upgrade</a>

      </div>

    </div>

  </nav>

</div>

<h5>Settings</h5>

<!------------------- -------------------->

<div class=" col-md-12" style="margin-top:20px;">

  <h5 class="alert alert-info"><p>Shortcode Options:</p>

    <p>[spiritual_gifts] - Only Spiritual Gifts questions are displayed</p>

    <p>[spiritual_gifts_shape] - All S.H.A.P.E questions are displayed</p>

  </h5>

</div>

<?php

include_once(MVSPG_PLUGIN_DIR . '/admin/model/admin.php');

$admin_model = new mvspg_admin_model();

$data = $admin_model->mvspg_survey_result_message();

?>

<div class="col-md-12" style="margin-top:20px;">

  <h5>Email Settings</h5>

  <form class="form-horizontal" method="post" enctype="multipart/form-data">

    <div class="form-group">

      <label class="control-label col-md-4">Sender Name *</label>

      <div class=" col-md-6">

        <input class="form-control" name="sendername" required value="<?php echo $data['sendername']; ?>" />

      </div>

    </div>

    <div class="form-group">

      <label class="control-label col-md-4">Sender Email Address *</label>

      <div class="col-md-6">

        <input class="form-control" name="senderemail" required value="<?php echo $data['senderemail']; ?>" />

      </div>

    </div>

    <div class="form-group">

      <label class="control-label col-md-4">CC multiple staff emails <br>(Comma separated) </label>

      <div class="col-md-6">

        <input class="form-control" name="ccemails" value="<?php echo $data['ccemails']; ?>" />

      </div>

    </div>

</div>

<div class="col-md-12" style="margin-top:20px;">

  <h5>Test Results Email</h5>

  <div class="form-group">

    <h5 class="alert alert-info">Email Options:<p>To personalize, use these shortcodes: [NAME] [TESTRESULT]</p>

    </h5>

    <label class="control-label col-md-4">Subject *</label>

    <div class="col-md-8">

      <input class="form-control" required name="subjectline_surveyresults" value="<?php echo $data['subjectline_surveyresults']; ?>" />

    </div>

  </div>

  <div class="form-group">

    <label class="control-label col-md-4">Message </label>

    <div class="col-md-8">

      <?php

      $content = str_ireplace('<br>', "\r\n", str_ireplace('<br/>', "\r\n", stripslashes($data['message_surveyresults'])));

      $editor_id = 'message_surveyresults';

      $settings =   array(

        'wpautop' => true, // use wpautop?

        'media_buttons' => true, // show insert/upload button(s)

        'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here

        'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."

        'tabindex' => '',

        'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 

        'editor_class' => '', // add extra class(es) to the editor textarea

        'teeny' => false, // output the minimal editor config used in Press This

        'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)

        'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()

        'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()

      );

      wp_editor($content, $editor_id, $settings = array());

      ?>

    </div>

  </div>

  <div class="form-group hide">

    <label class="control-label col-md-4">Turn On/Off </label>

    <div class="col-md-8">

      <select name="toggle_emails" class="form-control">

        <option <?php if ($data['toggle_emails'] == "1") {

                  echo "selected=selected";
                } ?> value="1">On</option>

        <option <?php if ($data['toggle_emails'] == "0") {

                  echo "selected=selected";
                } ?> value="0">Off</option>

      </select>

    </div>

  </div>

  <div class="form-group">

    <div class="col-md-12 text-right">

      <?php wp_nonce_field('spgsettings_save', 'spgsettings');; ?>

      <input type="hidden" name="action" value="saveformemailsurveynotif" />

      <button type="submit" class="btn btn-primary" style="padding:10px 50px;">Save</button>

    </div>

  </div>

  </form>

</div>