<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{
//    public $enableCsrfValidation = false;
    //用户注册界面
    public function actionRegister()
    {

        $model = new Member();

        if ($model->load(\yii::$app->request->post(), '') && $model->validate()) {
//            var_dump($model);exit;
            $model->save(false);
//            echo "注册成功";
            return $this->redirect(['user/login']);
        }else{
//            var_dump($model->getErrors());
        }

        return $this->render('register', ['model' => $model]);
    }

//    用户登录界面
    public function actionLogin()
    {
        $model = new LoginForm();
//        var_dump($model);exit;
        //注意第二个参数   为空
        if ($model->load(\yii::$app->request->post(),'') && $model->validate()) {
            //验证传过来的用户名与数据库的用户名是否一致
            $memberInfo=Member::findOne(['username' => $model->username]);
            //用户名一致则验证密码
            if ($memberInfo) {
                //明文密码 与数据库的密文密码进行对比
                if (\Yii::$app->security->validatePassword($model->password,$memberInfo->password_hash)){
                    //一致则判定是否勾选登陆信息  勾选则保存7天
                    $jid = $model->rememberMe?7*24*3600:0;
                    //保存到user组件
                    \Yii::$app->user->login($memberInfo, $jid);
                    //获取ip更新
                    $ip = ($_SERVER['REMOTE_ADDR']);
                    //保存ip 最后登陆时间
                    $memberInfo->last_login_ip = ip2long($ip);
                    $memberInfo->last_login_time=time();

                    $memberInfo->save();
//                    Member::updateAll(['last_login_ip' => $memberInfo->last_login_ip = ip2long($ip),'last_login_time'=>time()], ['id' => $memberInfo->id]);
                    return $this->redirect(['show/index']);
                }else{
                  echo "密码错误";
                }
            }else{
                echo "没有此用户";
            }

        }else{
//            var_dump($model->getErrors());exit;
        }
        return $this->render('login');
    }
    public function actionLogout(){
        //用户退出登陆操作
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //后台验证ajax   用户名验证重名
    public function actionValidateUsername($username){
        $model = new Member();
        $model->username = $username;
//        var_dump($model->username);exit;
        $model->validate('username');
        if($model->hasErrors('username')){
            //$model->getErrors();
            return Json::encode($model->getFirstError('username'));
        }
        return Json::encode(true);
    }
    //后台短信接收信息保存到redis
   public function actionSms($tel){
        echo $tel;
    //获取到tel
       $code = rand(1000,9999);
       \Yii::$app->sms->setParams(['smscode'=>$code])->setNumber($tel)->send();
       //判断发送成功则保存到redis
//       if ($result->Message == true){
           //实例化redis
           $redis = new \Redis();
           //链接redis服务器
           $redis->connect('127.0.0.1');
           //set保存到redis    键名  键值的方式
           $redis->set('sms_'.$tel,$code);
//       }
   }
   //后台验证ajax 对比短信
  public function actionValidatetel($tel){
//      $redis = new \Redis();
//
//      var_dump($redis->get('sms_'.$tel));
//      /实例化模型
        $model = new Member();
        //取出输入的tel
        $model->tel = $tel;
        //验证tel
        $model->validate('tel');
        //判定是否验证有错
        if ($model->hasErrors('tel')){
            //返回数据
            return Json::encode($model->getFirstError('tel'));
        }
        return Json::encode(true);
  }
}


