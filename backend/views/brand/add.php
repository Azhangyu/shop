<?php
//添加页面的显示
//id	primaryKey
//name	varchar(50)	名称
//intro	text	简介
//logo	varchar(255)	LOGO图片
//sort	int(11)	排序
//status	int(2)	状态(-1删除 0隐藏 1正常)
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textInput();
echo $form->field($model,'imgFile')->fileInput();
if($model->logo){
    echo \yii\bootstrap\Html::img($model->logo);
}
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList([0=>'隐藏',1=>'正常']);
echo \yii\bootstrap\Html::submitButton('提交');
$form = \yii\bootstrap\ActiveForm::end();