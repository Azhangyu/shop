<?php
/**
 * @var $this \yii\web\View
 */
//id	primaryKey
//tree	int()	树id
//lft	int()	左值
//rgt	int()	右值
//depth	int()	层级
//name	varchar(50)	名称
//parent_id	int()	上级分类id
//intro	text()	简介

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
//父节点id 设为隐藏域
echo $form->field($model,'parent_id')->hiddenInput();
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
$form = \yii\bootstrap\ActiveForm::end();
//调用Goodscategory的静态方法
$zNodes = \backend\models\Goodscategory::getZNodes();
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
                    $("#goodscategory-parent_id").val(treeNode.id);
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
       var zNodes = {$zNodes};
      
        
       zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
       
       //展开所有节点
       zTreeObj.expandAll(true);
       //修改   根据当前分类的parent_id选中节点
        var node = zTreeObj.getNodeByParam("id", "{$model->parent_id}", null);//根据id获取节点
        zTreeObj.selectNode(node);
       
JS
));
