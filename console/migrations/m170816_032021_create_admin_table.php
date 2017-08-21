<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170816_032021_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull()->comment('密码'),
            'password_reset_token' => $this->string(255)->unique(),
            'email' => $this->string(100)->notNull()->unique()->comment('邮箱'),

            'status' => $this->smallInteger(10)->notNull()->defaultValue(10)->comment('状态'),
            'created_at' => $this->integer(20)->notNull()->comment('创建时间'),
            'updated_at' => $this->integer(20)->notNull()->comment('修改时间'),
//            last_login_time,last_login_ip
           'last_login_time'=>$this->timestamp(),
            'last_login_ip'=>$this->integer(30)
        ]);
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
