<a class="btn btn-link" href="<?=\yii\helpers\Url::to(['rbac/parmissionadd'])?>">新增一个权限</a>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>权限名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($parmissions as $v):?>
    <tr>
        <td><?= $v->name?></td>
        <td><?= $v->description?></td>
        <td><?= \yii\helpers\Html::a('修改', ['rbac/parmissionedit','name'=>$v->name],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
            <?= \yii\helpers\Html::a('删除', ['rbac/parmissiondel','name'=>$v->name],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
//引入css  js文件
$this->registerCssFile('@web/tables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/tables/media/js/jquery.dataTables.js',
    ['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(<<<JS

   $(document).ready( function () {
        $("#table_id_example").DataTable({
            language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
       });
    } );
JS
);
