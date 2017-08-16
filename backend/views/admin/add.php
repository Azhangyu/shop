<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->textInput();
echo $form->field($model,'email')->textInput();
echo \yii\bootstrap\Html::submitButton('提交');
$form = \yii\bootstrap\ActiveForm::end();