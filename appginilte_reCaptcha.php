<?php 
$path_parts = pathinfo($_SERVER['REQUEST_URI']);
$current_file_name = $path_parts['filename'];
$reCaptchaData =get_option('reCaptcha_config');
if(Request::val('signIn') || Request::val('loginFailed')||$current_file_name=="appginilte_register") { ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script>$(() => {
    // add reCAPTCHA checkbox to form
    $(`<div
        class="g-recaptcha bspacer-lg col-12 text-center"
        data-sitekey="<?php echo $reCaptchaData['RECAPTCHA_SITE_KEY']; ?>"
        data-theme="light"
    ></div><br>`).insertAfter('div.reCaptcha')
 
    // prevent form submission if reCAPTCHA not solved
    $('form').on('submit', (e) => {
      if(!grecaptcha.getResponse().length)
        e.preventDefault();
    })
 
    // disable 'sign in' button if reCAPTCHA not solved
    $('#submit').prop('disabled', true);
    setInterval(() => {
      $('#submit').prop('disabled', !grecaptcha.getResponse().length);
    }, 100)
  })</script>
<?php } ?>