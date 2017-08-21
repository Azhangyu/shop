
<?php
    $form = \yii\bootstrap\ActiveForm::begin();
   echo $form->field($model,'label')->textInput();
   echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::caidan());
   //关联权限路由
   echo $form->field($model,'url')->dropDownList(\backend\models\Menu::permissions());
   echo $form->field($model,'sort')->textInput();
   echo \yii\bootstrap\Html::submitButton('提交');

    \yii\bootstrap\ActiveForm::end();