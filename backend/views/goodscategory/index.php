<?php
/* @var $this yii\web\View */
?>
<a class="btn btn-link glyphicon glyphicon-pencil" href="<?=\yii\helpers\Url::to(['goodscategory/add'])?>">添加商品分类</a>
<table class="table">
    <tr>
        <td>商品分类编号</td>
        <td>商品名称</td>
        <td>操作</td>
    </tr>
    <tr>
   <?php foreach ($rows as $v):?>
        <td><?=$v->id?></td>
<!--        根据深度depth缩进-->
        <td><?= str_repeat('—',$v->depth).$v->name?></td>

        <td>
            <?= \yii\helpers\Html::a('修改', ['goodscategory/edit','id'=>$v->id],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
            <button id="<?=$v->id?>" onclick="delgoodscategory(<?=$v->id?>)" class=" btn btn-danger glyphicon glyphicon-trash">删除</button>
        </td>
    </tr>
  <?php endforeach;?>
</table>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,
    'hideOnSinglePage' => false
])?>

<script>
    function delgoodscategory(id) {
        if (confirm("确定删除吗?")){
            $.getJSON("http://admin.shop.com/goodscategory/del","id="+id+"",function (data) {
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
