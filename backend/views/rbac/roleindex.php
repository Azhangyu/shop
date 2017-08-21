<?php
?>


<a class="btn btn-link" href="<?=\yii\helpers\Url::to(['rbac/roleadd'])?>">新增一个角色</a>
<table class="table">

    <tr>
        <th>角色名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $v):?>
        <tr>
            <td><?= $v->name?></td>
            <td><?= $v->description?></td>
            <td><?= \yii\helpers\Html::a('修改', ['rbac/roleedit','name'=>$v->name],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
                <?= \yii\helpers\Html::a('删除', ['rbac/roledel','name'=>$v->name],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?></td>
        </tr>

    <?php endforeach;?>
</table>

