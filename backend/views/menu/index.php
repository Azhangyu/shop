<?php
/* @var $this yii\web\View */
use yii\bootstrap\Html;
?>
<a class="btn btn-link" href="<?=\yii\helpers\Url::to(['menu/add'])?>">新增菜单</a>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tr>
        <?php foreach ($menus as $menu):?>
        <td><?= $menu->parent_id==0?$menu->label:'——'.$menu->label;?></td>
        <td><?= $menu->url;?></td>
        <td><?= $menu->sort;?></td>
        <td menu-id="<?=$menu->id?>"><?= \yii\helpers\Html::a('修改', ['menu/edit','id'=>$menu->id],['class'=>'btn btn-primary glyphicon glyphicon-edit'])?>
<!-- 根据id绑定一个点击删除事件-->
            <button onclick="delmenu(<?=$menu->id?>)" class="btn btn-danger glyphicon glyphicon-trash" id="<?=$menu->id?>">删除</button>
        </td>
    </tr>
    <?php endforeach;?>
</table>


<script>

    //声明名一个删除的函数
    function delmenu(id) {
        //confirm弹窗提示  返回bool型  是否删除
        var isdel = confirm("确认删除?");
        //返回true表示删除
        if (isdel === true){
            //利用Ajax请求根据id删除数据
            $.getJSON("http://admin.shop.com/menu/del","id="+id+"",function (data){
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