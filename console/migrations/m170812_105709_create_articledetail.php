<?php

use yii\db\Migration;

class m170812_105709_create_articledetail extends Migration
{
    public function safeUp()
    {
        $this->createTable('articledetail',[
            'article_id' => $this->primaryKey(),
            'content'=>$this->text()->notNull()->comment('文章内容'),

        ]);
    }



    public function safeDown()
    {
        echo "m170812_105709_create_articledetail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170812_105709_create_articledetail cannot be reverted.\n";

        return false;
    }
    */
}
