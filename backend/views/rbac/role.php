<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo $form->field($model,'permissions',['inline'=>true])->checkboxList(\backend\models\RoleForm::getPermissionItems());
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();