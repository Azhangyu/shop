<?php

use yii\db\Migration;

/**
 * Handles the creation of table `student1`.
 */
class m170811_100728_create_student1_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
//        学生（姓名，年龄，性别，所属班级id）
        $this->createTable('student1', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('学生姓名'),
            'age'=>$this->integer(5)->notNull()->comment('学生年龄'),
            'sex'=>$this->string(10)->notNull()->comment('学生性别'),
            'classnid'=>$this->integer(5)->notNull()->comment('所属班级')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('student1');
    }
}
