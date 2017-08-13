<?php
/* @var $this yii\web\View */
?>
<table class="table">
    <tr>
<!--        字段名	类型	注释-->
<!--        id	primaryKey-->
<!--        name	varchar(50)	名称-->
<!--        intro	text	简介-->
<!--        article_category_id	int()	文章分类id-->
<!--        sort	int(11)	排序-->
<!--        status	int(2)	状态(-1删除 0隐藏 1正常)-->
<!--        create_time	int(11)	创建时间-->
        <td>编号</td>
        <td>文章名称</td>
        <td>文章简介</td>
        <td>文章分类</td>
        <td>排序</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>

        <!--        <td>操作</td>-->
    </tr>
    <!-- 循环读取数据库中的数据  键名 键值都是对象 -->
    <?php foreach($rows as $v): ?>
        <tr>
            <td><?php echo $v->id; ?></td>
            <td><?php echo $v->name; ?></td>
            <td><?php echo $v->intro; ?></td>
            <td><?php echo $v->article->name;?></td>
            <td><?php echo $v->sort; ?></td>
            <td><?php echo $v->status ?></td>
            <td><?php echo $v->create_time; ?></td>
            <td>
                <?= \yii\helpers\Html::a('查看内容', ['article/more','id'=>$v->id],['class'=>'btn btn-info glyphicon glyphicon-eye-open'])?>
                <?= \yii\helpers\Html::a('修改', ['article/edit','id'=>$v->id],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
                <?= \yii\helpers\Html::a('删除', ['article/del','id'=>$v->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <a class="btn btn-link glyphicon glyphicon-pencil" href="<?=\yii\helpers\Url::to(['article/add'])?>">添加文章</a>

</table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,
    'hideOnSinglePage' => false
])?>