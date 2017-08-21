<?php
/* @var $this yii\web\View */
?>
<a class="btn btn-link glyphicon glyphicon-pencil" href="<?=\yii\helpers\Url::to(['admin/add'])?>">添加用户</a>
<a class="btn btn-info glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['admin/xiu'])?>">修改密码</a>

<table class="table">
    <tr>
        <td>编号</td>
        <td>用户名</td>
        <td>邮箱</td>
        <td>当前状态</td>
        <td>操作</td>
    </tr>

    <?php foreach ($admin as $v):?>
    <tr>
        <td><?= $v->id;?></td>
        <td><?= $v->username;?></td>
        <td><?= $v->email;?></td>
<!--        三元表达式判断当前状态 是否显示-->
        <td><?= $v->status==10?"启用":"禁用";?></td>
        <td>
            <?= \yii\helpers\Html::a('修改', ['admin/edit','id'=>$v->id],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
            <button onclick="del(<?=$v->id?>)" class="btn btn-danger glyphicon glyphicon-trash" id="<?=$v->id?>">删除</button>
        </td>
    </tr>

    <?php endforeach;?>
</table>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,
    //是否隐藏分页样式
    'hideOnSinglePage' => false
])?>
<script>
//声明名一个删除的函数
function del(id) {
//confirm弹窗提示  返回bool型  是否删除
var isdel = confirm("确认删除?");
//返回true表示删除
if (isdel === true){
//利用Ajax请求根据id删除数据
$.getJSON("http://admin.shop.com/admin/del","id="+id+"",function (data){
//判定数据库是否删除成功成功返回1
if (data === 1){
//根据id获取对应的父节点并删除
console.log(data);
$("#"+id+"").parent().parent().remove();
}
})
}

}
</script>