<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/5
 * Time: 11:41
 */
?>
    <table class="table">
        <tr>
            <td>品牌编号</td>
            <td>品牌名称</td>
            <td>品牌简介</td>
            <td>品牌logo</td>
            <td>排序</td>
            <td>当前状态</td>
            <td>操作</td>

            <!--        <td>操作</td>-->
        </tr>
        <!-- 循环读取数据库中的数据  键名 键值都是对象 -->
        <?php foreach($rows as $v): ?>
            <tr>
                <td><?php echo $v->id; ?></td>
                <td><?php echo $v->name; ?></td>
                <td><?php echo $v->intro; ?></td>
                <td><img src="<?= $v->logo?>" width="50px"></td>
                <td><?php echo $v->sort; ?></td>
                <td><?php echo $v->status; ?></td>
                <td><?= \yii\helpers\Html::a('修改', ['brand/edit','id'=>$v->id],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
                    <?= \yii\helpers\Html::a('删除', ['brand/del','id'=>$v->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>
                </td>
            </tr>
        <?php endforeach;?>
        <a class="btn btn-link glyphicon glyphicon-pencil" href="<?=\yii\helpers\Url::to(['brand/add'])?>">添加品牌</a>

    </table>
    <!--视图中要显示分页，要使用到LinkPager::widget 小部件-->
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,
    'hideOnSinglePage' => false
])?>