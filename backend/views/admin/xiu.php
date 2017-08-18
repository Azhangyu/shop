<?php
$form = \yii\bootstrap\ActiveForm::begin();
//旧密码
//var_dump($id);exit;
echo $form->field($xiu,'oldpassword');
//新密码
echo $form->field($xiu,'newpassword');
//重复密码
echo $form->field($xiu,'password_reset');
echo \yii\bootstrap\Html::submitButton('修改');
\yii\bootstrap\ActiveForm::end();