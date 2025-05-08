<?php
$linkNotification = get_home_url().'/onlineforms/'.$_SESSION['token'];

$bodylink = '
<div style="height:auto;color:#333333;margin:0 auto;">
  <div style="">
    <div align="">
      <p>Hi Admin,</p>
      <br>
      <p>You have a new message in your inbox.</p>
      <br>
      <p>Click the link below.</p>
      <br>
      <a href="'.$linkNotification.'">'.$linkNotification.'</a>
      <br>
      <p>_______________________________________________________</p>
      <p>'.COMP_NAME.'</p>

      <p>Please do not reply to this email. This is only a notification from your website online forms. To contact the person who filled out your online form, kindly use the email which is inside the form below.</p>
    </div>
  </div>
</div>';
 ?>
