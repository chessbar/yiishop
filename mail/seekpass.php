<p>尊敬的<?php echo $user;?>,您好：</p>
<p>您找回密码的连接如下：</p>
<?php
$url = Yii::$app->urlManager->createAbsoluteUrl(['admin/manage/mailchangepass','timestamp'=>$time,'user'=>$user,'token'=>$token]);
?>
<p><a href="<?php echo $url;?>"><?php echo $url;?></a></p>
<p>该连接5分钟内有效</p>
<p>该邮件为系统自动发生，请勿回复!</p>