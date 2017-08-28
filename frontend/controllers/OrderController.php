<?php

namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\filters\AccessControl;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAdd(){

        //    查询出用户购物车的数据
        $userid=\Yii::$app->user->id;  // 获取用户的ID
        $ids=Cart::find()->select('goods_id')->where(['member_id'=>$userid])->column(); //取得该用户的购物车
//        var_dump($ids);exit;
        $rows=Goods::find()->where(['in','id',$ids])->all();  //取得该用户的所有商品信息
//     var_dump($rows);exit;
        if(\Yii::$app->request->isPost){
            //取得总金额
//            var_dump($rows);exit;
            $sum=0;
            foreach ($rows as $v){
                $sum+=$v->shop_price*$v->carts->amount;
            }

            //              开启事务
            $transaction =\Yii::$app->db->beginTransaction();
//exit;

            $data=\Yii::$app->request->post();
//            var_dump($data);exit;
            try{

                $model = new Order(); //实例化订单模型

                $model->member_id=$userid;
                //收货地址
                $addre=Address::findOne(['id'=>$data['address_id']]);//查询出一个地址
//           var_dump($addre);exit;
                $model->delivery_id=$data['delivery_id'];     //配送方式ID
                $model->payment_id=$data['payment_id']; //支付方式ID;
//             var_dump($model->delivery_id);exit;
                $model->name=$addre->name;
                $model->province=$addre->cmbProvince;
                $model->city=$addre->cmbCity;
                $model->area=$addre->cmbArea;
                $model->address=$addre->xaddress;
                $model->tel=$addre->tel;
                //快递配送方式
                $delivery=Order::$delivery[$model->delivery_id];
                $model->delivery_name=$delivery[0];
                $model->delivery_price=$delivery[1];
                //支付方式
                $payment=Order::$payment[$model->payment_id];
                $model->payment_name=$payment[0];
                //订单金额
                $model->total=$sum;
                $model->create_time=time();
                $model->status = 1;
//                    保存数据
//                var_dump($model->tel);exit;
                if($model->validate()){
//                var_dump($model->save());exit;
                    $model->save();
                }else{
                    var_dump($model->getErrors());exit;//提示错误信息
                }


                //检查购物车的商品库存
                $carts=Cart::findAll(['member_id'=>$userid]);
                foreach ($carts as $v){
                    $stock=Goods::findOne(['id'=>$v->goods_id]);
//             var_dump($carts);exit;
                    if($stock->stock<$v->amount){
                        throw new Exception('商品库存不足，请修改');
                    }
                    //如果库存足够就保存到订单商品详情表

//循环遍历并保存每个商品的订单信息
                    foreach ($rows as $value){
                        $order_goods=new OrderGoods();
                        $order_goods->order_id=$model->id;
                        $order_goods->goods_id=$value->id;
                        $order_goods->goods_name=$value->name;
                        $order_goods->logo=$value->logo;
                        $order_goods->price=$value->shop_price;
                        $order_goods->amount=$value->carts->amount;
                        $order_goods->total=$value->shop_price*$value->carts->amount;

                        $order_goods->save();

                        Goods::updateAllCounters(['stock'=>-$value->carts->amount],['id'=>$value->id]);
                    }
                    //清空购物车数据
                    Cart::deleteAll(['member_id'=>$userid]);
//                    //提交事务
                    $transaction->commit();
                    return $this->redirect(['success']); //成功就跳转

                }



            } catch (Exception $e){
                $transaction->rollBack();  //有错误就回滚
            }

        }
           $sum_goods=count($ids); //统计商品样数

           $address=Address::findAll(['id'=>$userid]);
        //查询出收货地址
        return $this->render('add',['rows'=>$rows,'sum_goods'=>$sum_goods,'address'=>$address]);
    }
  //订单添加成功页面
    public function actionSuccess(){


        return   $this->render('success');
    }
//我的订单详细展示
    public function actionList(){

        $user_id=\Yii::$app->user->id;
        $rows = Order::findAll(['member_id'=>$user_id]);

//var_dump($rows);exit;
        return $this->render('list',['rows'=>$rows]);

    }

}

