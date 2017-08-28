<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170827_081333_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull()->comment('用户ID'),
            'name'=>$this->string(50)->notNull()->comment('用户姓名'),
            'province'=>$this->string(20)->notNull()->comment('省'),
            'city'=>$this->string(20)->notNull()->comment('市'),
            'area'=>$this->string(50)->notNull()->comment('县'),
            'address'=>$this->string(150)->notNull()->comment('详细收货地址'),
            'tel'=>$this->string(11)->notNull()->comment('电话号码'),
            'delivery_id'=>$this->smallInteger()->notNull()->comment('配送方式ID'),
            'delivery_name'=>$this->string(60)->notNull()->comment('配送方式名称'),
            'delivery_price'=>$this->float()->notNull()->comment('配送方式价格'),
            'payment_id'=>$this->smallInteger()->notNull()->comment('支付方式ID'),
            'payment_name'=>$this->string(60)->notNull()->comment('支付方式名称'),
            'total'=>$this->decimal(10,2)->notNull()->comment('订单金额'),
            'status'=>$this->smallInteger(2)->notNull()->comment('订单状态'),
            'trade_no'=>$this->string()->comment('第三方支付交易号'),
            'create_time'=>$this->integer(11)->comment('创建时间'),
        ]);

        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->notNull()->comment('订单ID'),
            'goods_id'=>$this->integer()->notNull()->comment('商品ID'),
            'goods_name'=>$this->string(200)->notNull()->comment('商品名称'),
            'logo'=>$this->string(255)->notNull()->comment('商品图片'),
            'price'=>$this->decimal(10,2)->notNull()->comment('商品价格'),
            'amount'=>$this->integer()->notNull()->comment('数量'),
            'total'=>$this->decimal(10,2)->notNull()->comment('统计总的金额'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
        $this->dropTable('order_goods');
    }
}