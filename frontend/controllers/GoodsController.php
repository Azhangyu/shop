<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use yii\web\Cookie;
use Yii;
class GoodsController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionGoods($id)
    {
        //获取当前商品的信息
        $models = Goods::findOne($id);

        return $this->render('goods', ['models' => $models]);

    }

    //购物车添加成功提示页
    public function actionNotice($goods_id, $amount)
    {
        if (\Yii::$app->user->isGuest) {
            //未登陆,数据保存到cookie中
            $cookies = \Yii::$app->request->cookies;
            //查看cookie中是否有购物车
            $carts = $cookies->getValue('carts');
            if ($carts == null) {
                $carts = [];
            } else {
                $carts = unserialize($carts);
            }
            //根据goods_id 去购物车表查询，是否存在该商品
            if (array_key_exists($goods_id, $carts)) {
                //如果已存在，则更新购物车对应的商品数量
                $carts[$goods_id] += $amount;
            } else {
                //如果不存在，则插入一条新数据
                $carts[$goods_id] = $amount;
            }

            $cookies = \Yii::$app->response->cookies;
            //写入数据到cookie
            $cookie = new Cookie([
                'name' => 'carts',
                'value' => serialize($carts),
                'expire' => time() + 10 * 24 * 3600 //过期时间戳 设置10天时间  时间戳格式  需要加上time()
            ]);
            $cookies->add($cookie);//设置cookie


        } else {
            //如果已登陆,数据保存到数据表
            $model = new Cart();
            //获取到用户id
            $member_id = \yii::$app->user->identity->id;
//            根据goods_id 和 member_id 去购物车表查询，是否存在该商品
            $carts = Cart::find()->where(['goods_id' => $goods_id])
                ->andWhere(['member_id' => $member_id])
                ->one();
            if ($carts == null) {
                //如果不存在，则插入一条新数据
                //获取到商品id
                $model->goods_id = \yii::$app->request->get('goods_id');
                //获取到商品数量
                $model->amount = \yii::$app->request->get('amount');
                $model->member_id = $member_id;
                $model->save();
            } else {
                //存在则更新商品数量
                $carts->amount += $amount;
                $carts->save();
            }

        }
        return $this->redirect(['goods/cart']);
    }
//购物车显示页面
    public function actionCart()
    {
        //判断是否登陆
        if (\Yii::$app->user->isGuest) {
            //没有登陆从cookie读取
            $cookies = \Yii::$app->request->cookies;
            //查看cookie中是否有购物车
            $carts = $cookies->getValue('carts');
            if ($carts == null) {
                $carts = [];
            } else {
                $carts = unserialize($carts);
            }
            $models = Goods::find()->where(['in', 'id', array_keys($carts)])->all();
            return $this->render('cart',['models'=>$models,'carts'=>$carts]);
        } else {
             //获取用户id
             $id = \yii::$app->user->id;

//            同步该用户cookie中的数据
            $cookies=\Yii::$app->request->cookies; //实例化cookie
            $carts=$cookies->getValue('carts');
            if($carts!==null){   //如果取出来的cookie不为空就加入到数据库
                $carts=unserialize($carts);
                foreach ($carts as $keys=>$v){
                    $model=Cart::findOne(['goods_id'=>$keys,'amount'=>$v,'member_id'=>$id]);  //查询出数据
                    if($model){  // 判断是否有数据 有就修改，没有就增加
                        $model->amount+=$v;

                    }else{
                        $model=new Cart(); //实例化购物车表单数据
                        $model->amount=$v;
                        $model->member_id=$id;
                    }

                    $model->goods_id=$keys;
                    $model->save();
                }
                $cookies=\Yii::$app->response->cookies;
                $cookies->remove('carts');
            }

            //用户登录增加到数据表

            $ids=Cart::find()->select('goods_id')->where(['member_id'=>$id])->column(); //查询出当前用户的商品数据
            $models=Goods::find()->where(['in','id',$ids])->all();

        }
        return $this->render('cart',['models'=>$models]);
    }


    //修改购物车商品数量
  public function actionChange()
{

    $data = \Yii::$app->request->post();

     $goods_id = $data['goods_id'];
     $amount = $data['amount'];
//      echo $goods_id;exit;
    if (\Yii::$app->user->isGuest) {  //未登录用户判断
//      实例化cookie 写入
        $cookies = \Yii::$app->request->cookies;
        $cart = $cookies->getValue('carts'); //取出cookie数据 判断是否有数据
//            var_dump($cookies);exit;
        if ($cart === null) {
            $cart = [];
            return '商品不存在，请刷新页面';
        } else {
            $cart = unserialize($cart);
        }
        //如果存在则更cookie中的数据
        if (isset($cart[$goods_id])) {

            if ($amount == 0) {
                unset($cart[$goods_id]); //如果数量为O就删除
            } else {
                $cart[$goods_id] = $amount;
            }

            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'carts',
                'value' => serialize($cart),
                'expire' => time() + 3600 * 24 *7   //设置过期时间
            ]);
            $cookies->add($cookie);  //保存数据
            return 'success';
        }
    }else{
        //对登录的用户操作

        //用户增加到数据表
        $id  = \Yii::$app->user->id;//获取用户ID
        $model=Cart::findOne(['member_id'=>$id,'goods_id'=>$goods_id]);  //查询出数据
        if($model){  // 判断是否有数据 有就修改
            if ($amount == 0) {
                Cart::deleteAll(['goods_id'=>$goods_id,'member_id'=>$id]); //如果数量为O就删除
            } else {
                $model->amount = $amount;
            }

            $model->member_id=$id;
            $model->goods_id=$goods_id;
            $model->save();
            return 'success';
        }else{
            return '没有该商品';
        }



    }

}

}