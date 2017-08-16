<?php
use yii\web\JsExpression;
$form = \yii\bootstrap\ActiveForm::begin();

//id	primaryKey
//name	varchar(20)	商品名称
echo $form->field($model,'name')->textInput();
//sn	varchar(20)	货号
//logo	varchar(255)	LOGO图片
  //隐藏域保存上传图片路径
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],//传递数据
        'width' => 90,
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
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($model->logo,['id'=>'img']);
//goods_category_id	int	商品分类id  查询商品分类  关联商品分类表goodscategory
//echo $form->field($model,'goods_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($goodscategory,'id','name'));
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
//调用Goods的静态方法
$zNodes=\backend\models\Goodscategory::getZNodes();
//加载zTree静态资源
  //css文件加载
//$this->registerCssFile('@web/zTree/css/demo.css');
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//加载js资源
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//加载JS代码
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
 var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{
                onClick:function(event, treeId, treeNode){
                    console.log(treeNode.id);
                    //赋值给parent_id
                    $("#goods-goods_category_id").val(treeNode.id);
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
       var zNodes = {$zNodes};


       zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);

       //展开所有节点
       zTreeObj.expandAll(true);
     //修改   根据当前分类的goods_category_id选中节点
       var node = zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null);//根据id获取节点
        zTreeObj.selectNode(node);
JS
));
//brand_id	int	品牌分类  查询品牌分类  关联品牌分类表brand
echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brand,'id','name'));
//market_price	decimal(10,2)	市场价格
echo $form->field($model,'market_price')->textInput();
//shop_price	decimal(10, 2)	商品价格
echo $form->field($model,'shop_price')->textInput();
//stock	int	库存
echo $form->field($model,'stock')->textInput();
//is_on_sale	int(1)	是否在售(1在售 0下架)
echo $form->field($model,'is_on_sale')->radioList([1=>'在售',0=>'下架']);
//status	inter(1)	状态(1正常 0回收站)
echo $form->field($model,'status')->radioList([1=>'正常',0=>'回收站']);
//sort	int()	排序
echo $form->field($model,'sort')->textInput();
//create_time	int()	添加时间
//view_times	int()	浏览次数
//使用插件编辑内容
echo $form->field($goodsIntro,'content')->widget('kucha\ueditor\UEditor',[
    //配置参数clientOptions
    'clientOptions' => [
        //编辑区域大小i   nitialFrameHeight
        'initialFrameHeight' => '300',
        //设置语言 lang  中文为 zh-cn
        'lang' =>'zh-cn',
    ]
]);
echo \yii\bootstrap\Html::submitButton('提交');
$form = \yii\bootstrap\ActiveForm::end();