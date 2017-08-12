<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/5
 * Time: 11:41
 */
?>
    <table class="table">
<!--        //id	primaryKey-->
<!--        //name	varchar(50)	名称-->
<!--        //intro	text	简介-->
<!--        //sort	int(11)	排序-->
<!--        //status	int(2)	状态(-1删除 0隐藏 1正常)-->
        <tr>
            <td>编号</td>
            <td>姓名</td>
            <td>简介</td>
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
                <td><?php echo $v->sort; ?></td>
                <td><?php echo $v->status; ?></td>
                <td><?= \yii\helpers\Html::a('修改', ['articlecategory/edit','id'=>$v->id],['class'=>'btn btn-primary'])?>
                    <?= \yii\helpers\Html::a('删除', ['articlecategory/del','id'=>$v->id],['class'=>'btn btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
        <a class="btn btn-link" href="<?=\yii\helpers\Url::to(['articlecategory/add'])?>">添加用户</a>

    </table>
    <!--视图中要显示分页，要使用到LinkPager::widget 小部件-->
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,
    'hideOnSinglePage' => false
])?>