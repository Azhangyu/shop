<?php

use yii\db\Migration;

/**
 * Handles the creation of table `class`.
 */
class m170811_090834_create_class_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('class', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('班级名称'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('class');
    }
}
