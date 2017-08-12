<?php
//字段名	类型	注释
//id	primaryKey
//name	varchar(50)	名称
//intro	text	简介
//article_category_id	int()	文章分类id
//sort	int(11)	排序
//status	int(2)	状态(-1删除 0隐藏 1正常)
//create_time	int(11)	创建时间

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($modelA,'name')->textInput();
echo $form->field($modelA,'intro')->textarea();
echo $form->field($modelA,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($row,'id','name'));
echo $form->field($modelA,'sort')->textInput();
echo $form->field($modelA,'status')->radioList([0=>'下架',1=>'正常']);
echo $form->field($modelB,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交');
$form = \yii\bootstrap\ActiveForm::end();