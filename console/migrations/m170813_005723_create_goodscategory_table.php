<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goodscategory`.
 */
class m170813_005723_create_goodscategory_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

//        字段名	类型	注释
//id	primaryKey
//tree	int()	树id
//lft	int()	左值
//rgt	int()	右值
//depth	int()	层级
//name	varchar(50)	名称
//parent_id	int()	上级分类id
//intro	text()
        $this->createTable('goodscategory', [
            'id' => $this->primaryKey(),
            'tree'=>$this->integer(5)->notNull()->comment('树id'),
            'lft'=>$this->integer(5)->notNull()->comment('左值'),
            'rgt'=>$this->integer(5)->notNull()->comment('右值'),
            'depth'=>$this->integer(5)->notNull()->comment('层级'),
            'name'=>$this->string(50)->notNull()->comment('商品分类名称'),
            'parent_id'=>$this->integer(3)->notNull()->comment('上级分类id'),
            'intro'=>$this->text()->notNull()->comment('简介')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goodscategory');
    }
}
