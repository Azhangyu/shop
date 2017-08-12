<?php
use yii\web\JsExpression;
//添加页面的显示
//id	primaryKey
//name	varchar(50)	名称
//intro	text	简介
//logo	varchar(255)	LOGO图片
//sort	int(11)	排序
//status	int(2)	状态(-1删除 0隐藏 1正常)
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
//echo $form->field($model,'imgFile')->fileInput();
//if($model->logo){
//    echo \yii\bootstrap\Html::img($model->logo);
//}
//保存上传文件的路径
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],//传递数据
        'width' => 100,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //图片回显
        $("#img").attr("src",data.fileUrl);
        //将图片地址写入到logo隐藏输入框
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($model->logo,['id'=>'img']);
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList([0=>'下架',1=>'上线']);
echo \yii\bootstrap\Html::submitButton('提交');
$form = \yii\bootstrap\ActiveForm::end();