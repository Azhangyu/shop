<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170810_104908_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
//        id	primaryKey
        'id'=>$this->primaryKey(),
//name	varchar(50)	名称
        'name'=>$this->string(50)->notNull()->comment('文章名称'),
//intro	text	简介
        'intro'=>$this->text()->notNull()->comment('文章简介'),
//sort	int(11)	排序
        'sort'=>$this->integer()->notNull()->comment('排序'),
//status	int(2)	状态(-1删除 0隐藏 1正常)
        'status'=>$this->smallInteger(2)->notNull()->comment('状态')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
