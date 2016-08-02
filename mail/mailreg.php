<p>恭喜您，您注册的账号已申请成功</p>
<p>用户名：<?php echo $username;?></p>
<p>密码：<?php echo $userpass;?></p>
<p>您可以使用收件邮箱或者用户名进行登录</p>
<p>登录地址：<?php echo Yii::$app->urlManager->createAbsoluteUrl(['member/auth']);?></p>
<p>该邮件为系统自动发生，请勿回复!</p>