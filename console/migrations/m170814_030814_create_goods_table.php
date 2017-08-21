<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170814_030814_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */





    public function up()
    {
//        goods_day_count 商品每日添加数
        $this->createTable('goods_day_count', [
            //字段名	类型	注释
//day	date	日期
//count	int	商品数
            'day' => $this->date()->comment('当天日期'),
           'count'=>$this->integer(255)->comment('商品条数'),

        ]);
        //goods 商品表
        $this->createTable('goods', [
//        字段名	类型	注释
//id	primaryKey
        'id'=>$this->primaryKey(),
//name	varchar(20)	商品名称
        'name'=>$this->string(20)->notNull()->comment('商品名称'),
//sn	varchar(20)	货号
        'sn'=>$this->string(20)->notNull()->comment('商品货号'),
//logo	varchar(255)	LOGO图片
        'logo'=>$this->string(255)->notNull()->comment('商品logo'),
//goods_category_id	int	商品分类id
        'goods_category_id'=>$this->integer(5)->notNull()->comment('商品分类id'),
//brand_id	int	品牌分类
        'brand_id'=>$this->integer(5)->notNull()->comment('品牌分类id'),
//market_price	decimal(10,2)	市场价格
        'market_price'=>$this->decimal(10,2)->notNull()->comment('市场价格'),
//shop_price	decimal(10, 2)	商品价格
        'shop_price'=>$this->decimal(10,2)->notNull()->comment('商品价格'),
//stock	int	库存
        'stock'=>$this->integer(10)->notNull()->comment('库存'),
//is_on_sale	int(1)	是否在售(1在售 0下架)
        'is_on_sale'=>$this->integer(1)->notNull()->comment('是否在售'),
//status	inter(1)	状态(1正常 0回收站)
        'status'=>$this->integer(1)->notNull()->comment('商品状态'),
//sort	int()	排序
        'sort'=>$this->integer(2)->notNull()->comment('排序'),
//create_time	int()	添加时间
        'create_time'=>$this->timestamp(time())->comment('添加时间'),
//view_times	int()	浏览次数
        'view_times'=>$this->integer(20)->comment('浏览次数')
        ]);
//        goods_intro 商品详情表
        $this->createTable('goods_intro', [
//字段名	类型	注释
//goods_id	int	商品id
        'goods_id'=>$this->integer('5')->comment('商品id'),
//content	text	商品描述
        'content'=>$this->text()->notNull()->comment('商品描述'),
        ]);
// 商品图片表       goods_gallery
        $this->createTable('goods_gallery', [
//id	primaryKey
        'id'=>$this->primaryKey(),
//goods_id	int	商品id
        'goods_id'=>$this->integer(5)->comment('商品id'),
//path	varchar(255)	图片地址
        'path'=>$this->string(255)->notNull()->comment('图片地址')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_count');
        $this->dropTable('goods');
        $this->dropTable('goods_intro');
        $this->dropTable('goods_gallery');
    }
}
