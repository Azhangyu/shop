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
            <td>编号</td>
            <td>姓名</td>
            <td>简介</td>
            <td>头像</td>
            <td>排序</td>
            <td>状态</td>
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
                <td><?= \yii\helpers\Html::a('修改', ['brand/edit','id'=>$v->id],['class'=>'btn btn-primary'])?>
                    <?= \yii\helpers\Html::a('删除', ['brand/del','id'=>$v->id],['class'=>'btn btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
        <a class="btn btn-link" href="<?=\yii\helpers\Url::to(['brand/add'])?>">添加用户</a>

    </table>
    <!--视图中要显示分页，要使用到LinkPager::widget 小部件-->
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,
    'hideOnSinglePage' => false
])?>