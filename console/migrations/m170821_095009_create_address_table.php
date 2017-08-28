<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170821_095009_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('收货人'),
            'cmbProvince'=>$this->string(100)->comment('省'),
            'cmbCity'=>$this->string(50)->comment('区'),
            'cmbArea'=>$this->string(50)->comment('区县'),
            'xaddress'=>$this->string(255)->notNull()->comment('详细地址'),
            'tel'=>$this->string(20)->notNull()->comment('电话'),
            'checkbox'=>$this->integer(2)->comment('默认地址')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
