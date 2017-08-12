<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m170811_105543_create_book_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

//        3.图书（标题，简介，封面【上传图片】，添加时间，作者【关联学生id】）

        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title'=>$this->string(100)->notNull()->comment('图书标题'),
            'intro'=>$this->string(100)->notNull()->comment('图书简介'),
            'logo'=>$this->string(255)->notNull()->comment('封面'),
            'createtime'=>$this->timestamp(time())->notNull()->comment('添加时间'),
            'author'=>$this->string(20)->notNull()->comment('作者')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('book');
    }
}
