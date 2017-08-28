<?php
/* @var $this yii\web\View */
?>
    <a class="btn btn-link" href="<?=\yii\helpers\Url::to(['goods/add'])?>">新增商品</a>
    <form class="form-inline" action="/goods/index" method="get" role="form">
        <div class="form-group field-articlesearchform-name">
            <input type="text" id="articlesearchform-name" class="form-control" name="name" placeholder="标题">
        </div>
        <button type="submit" class="btn btn-default glyphicon glyphicon-zoom-in">搜索</button>
        <button type="submit" class="btn btn-default glyphicon glyphicon-repeat" href="<?=\yii\helpers\Url::to(['goods/index'])?>">返回</button>
    </form>
<table class="table">
    <tr>
    <td>商品编号</td>
    <td>商品货号</td>
    <td>商品名称</td>
    <td>价格</td>
    <td>库存</td>
    <td>logo</td>
    <td>操作</td>
    </tr>
<?php foreach ($goods as $v):?>
    <tr>
    <td><?= $v->id?></td>
    <td><?= $v->sn?></td>
    <td><?= $v->name?></td>
    <td><?= $v->shop_price?></td>
    <td><?= $v->stock?></td>
    <td><img src="<?= $v->logo?>" width="50px"></td>
    <td>
        <?= \yii\helpers\Html::a('相册', ['goods/gallery','id'=>$v->id],['class'=>'btn btn-primary glyphicon glyphicon-picture'])?>
        <?= \yii\helpers\Html::a('修改', ['goods/edit','id'=>$v->id],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
        <button id="<?=$v->id?>" onclick="delgoods(<?=$v->id?>)" class=" btn btn-danger glyphicon glyphicon-trash">删除</button>
    </td>
    </tr>
<?php endforeach;?>
</table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    //'maxButtonCount' => 5,
    'hideOnSinglePage' => true
])?>
<script>
    function delgoods(id) {
        if (confirm("确定删除吗?")){
            $.getJSON("http://admin.shop.com/goods/del","id="+id+"",function (data) {
//
                if (data ===1){
                    //删除当前节点的父节点
                    $("#"+id+"").closest('tr').remove();
                }else {
                    console.log(data);
                }
            })
        }
    }
</script>