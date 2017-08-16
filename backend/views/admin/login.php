<?php
/**
 * Created by PhpStorm.
 * User: HASEE
 * Date: 2017/8/16
 * Time: 12:56
 */

$form = \yii\bootstrap\ActiveForm::begin();
//用户名
echo $form->field($model,'username')->textInput();
//密码
echo $form->field($model,'password_hash')->passwordInput();
//重复密码
echo $form->field($model,'password_reset')->passwordInput();
echo $form->field($model, 'cap')->widget(yii\captcha\Captcha::className(), [
//    改为自己建的验证码 路径
    'captchaAction' => 'admin/cap',
    //img效果
    'imageOptions'=>['alt'=>'点击换图','title'=>'点击换图', 'style'=>'cursor:pointer'],
    'template' => '<div class="row"><div class="col-lg-5 col-md-5">{input}</div>
    <div class="col-lg-3 col-md-3">{image}</div>'
]);
?>
<div class="form-group field-loginform-rememberme">
<div class="checkbox">
<label for="loginform-rememberme">
<input type="hidden" name="LoginForm[rememberMe]" value="0"><input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" checked="">
    Remember Me
</label>
<p class="help-block help-block-error"></p>

</div>
</div>
<?php
echo \yii\bootstrap\Html::submitButton('登陆');
$form = \yii\bootstrap\ActiveForm::end();