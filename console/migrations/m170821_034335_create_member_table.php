<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170821_034335_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
//            id	primaryKey
//username	varchar(50)	用户名
//auth_key	varchar(32)
//password_hash	varchar(100)	密码（密文）
//email	varchar(100)	邮箱
//tel	char(11)	电话
//last_login_time	int	最后登录时间
//last_login_ip	int	最后登录ip
//status	int(1)	状态（1正常，0删除）
//created_at	int	添加时间
//updated_at
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('密文密码'),
            'password_hash' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull()->unique()->comment('邮箱'),
            'tel'=>$this->char(11)->notNull()->comment('电话'),
            'last_login_time'=>$this->integer(50)->comment('最后登陆时间'),
            'last_login_ip'=>$this->integer(20)->comment('最后登录ip'),
            'status' => $this->smallInteger(2)->notNull()->comment('状态'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
