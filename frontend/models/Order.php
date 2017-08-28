<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //    声明属性数组  送货方式  支付方式
    public static $delivery=[
        1=>['顺丰快递','20','每张订单不满499.00元,运费20元'],
        2=>['圆通快递','15','每张订单不满499.00元,运费15元'],
        3=>['EMS','20','每张订单不满499.00元,运费20元']
    ];
    public static $payment=[
        1=>['微信支付','即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        2=>['支付宝支付','即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>['货到付款','送货上门后再收款，支持现金、POS机刷卡、支票支付']
    ];


    public $address_id;   //地址ID

    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'name', 'province', 'city', 'area', 'address', 'tel', 'delivery_id', 'delivery_name', 'delivery_price', 'payment_id', 'payment_name', 'total', 'status'], 'required'],
            [['member_id', 'delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name', 'area'], 'string', 'max' => 50],
            [['province', 'city'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 150],
            [['tel'], 'string', 'max' => 11],
            [['delivery_name', 'payment_name'], 'string', 'max' => 60],
            [['trade_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => 'Name',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'tel' => 'Tel',
            'delivery_id' => 'Delivery ID',
            'delivery_name' => 'Delivery Name',
            'delivery_price' => 'Delivery Price',
            'payment_id' => 'Payment ID',
            'payment_name' => 'Payment Name',
            'total' => 'Total',
            'status' => 'Status',
            'trade_no' => 'Trade No',
            'create_time' => 'Create Time',
        ];
    }
    public function getOrder_goods(){

        return $this->hasMany(OrderGoods::className(),['order_id'=>'id']);
    }
}
