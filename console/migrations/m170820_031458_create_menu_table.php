<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170820_031458_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            //菜单id
            'id' => $this->primaryKey(),
            //菜单名字
            'label'=>$this->string(50)->notNull()->comment('菜单名字'),
            'parent_id'=>$this->integer(10)->notNull()->comment('上级菜单'),
            'url'=>$this->string(50)->notNull()->comment('路由地址'),
            'sort'=>$this->integer(5)->notNull()->comment('排序')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
