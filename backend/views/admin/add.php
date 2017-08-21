<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->textInput();
//重复密码
//echo $form->field($model,'password_reset')->textInput();
echo $form->field($model,'email')->textInput();
//关联角色
echo $form->field($model,'roles',['inline'=>true])->checkboxList(\backend\models\Admin::getRoleItems());
//inline 第三个参数  true表示在一行显示  默认flase
echo $form->field($model,'status',['inline'=>true])->radioList(['10'=>'启用','0'=>'禁用']);
echo \yii\bootstrap\Html::submitButton('提交');
$form = \yii\bootstrap\ActiveForm::end();