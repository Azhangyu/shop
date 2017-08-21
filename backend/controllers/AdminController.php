<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\LoginFore;
use backend\models\Xiu;
use yii\data\Pagination;

class AdminController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            //验证码
            'cap' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 4,
                'maxLength' => 5
            ]
        ];
    }
    //添加用户
    //定义关联角色
    public $roles;
    public function actionAdd()
    {
        $model = new Admin;

        //绑定数据
       //定义场景
        $model->scenario = Admin::SCENARIO_ADD;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //添加密码是hash加密
//            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            //保存创建时间
//            $model->created_at= time();
            $model->save();
            //保存之后获取当前添加的用户id  给用户关联角色
            //添加
            $authManager = \Yii::$app->authManager;
            if(is_array($model->roles)){
                //循环  追加
                foreach ($model->roles as $roleName){
                    $role = $authManager->getRole($roleName);
                    $authManager->assign($role,$model->id);//角色，用户id
                }
            }
            return $this->redirect(['admin/index']);
        }else{
            var_dump($model->getErrors());
        }


        //展示添加列表
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {
        $model = Admin::findOne(['id' => $id]);
        //根据角色找到用户id
        $role = \yii::$app->authManager->getRolesByUser($id);
//        array_keys 回显
        $model->roles=(array_keys($role));
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            $model->save();
            return $this->redirect(['admin/index']);
        }


        //展示添加列表
        return $this->render('add', ['model' => $model]);
    }

    //删除
    public function actionDel($id)
    {
//        $model = Admin::findOne(['id' => $id]);
//        $model->delete();
//        return $this->redirect(['admin/index']);
        echo Admin::findOne(['id'=>$id])->delete();
    }

    //用户显示列表
    public function actionIndex()
    {
        $row = Admin::find()->where('status=10');
        //分页
        $page= new Pagination([
            //总数据条数
            'totalCount'=>$row->count(),
            //每页显示条数
            'defaultPageSize' =>5,
        ]);
        $admin=$row->offset($page->offset)
            ->limit($page->pageSize)
            ->all();
        return $this->render('index',['admin'=>$admin,'page'=>$page]);


    }

    public function actionLogin()
    {
        //登录表单
        $model = new LoginFore;
        //实例化user对象
//        $userObj = \Yii::$app->user;
        if ($model->load(\Yii::$app->request->post())) {
//            var_dump($model->password_hash);exit;
            if ($model->validate()) {
                //验证 数据库中用户名与提交过来的用户名是否一致
                $adminInfo = Admin::findOne(['username' => $model->username]);
                //如果用户名正确 验证密码
                if ($adminInfo) {
                    if (\Yii::$app->security->validatePassword($model->password, $adminInfo->password_hash)) {
                            //判断是否勾选记住我  是就保存 信息 反之为0 不保存
                             $jid = $model->rememberMe?7 * 24 * 3600:0;
                             //密码正确 可以登陆
//                             var_dump($model->rememberMe);exit;
                             \Yii::$app->user->login($adminInfo, $jid);//登陆状态保持7天

                        //获取ip
//                          var_dump($model->rememberMe);exit;
                        $ip = ($_SERVER['REMOTE_ADDR']);
//                   ip  ip2long string类型 转为 int类型
//                        $adminInfo->last_login_ip = ip2long($ip);
                        //updateAll更新ip
                        Admin::updateAll(['last_login_ip'=> $adminInfo->last_login_ip = ip2long($ip),],['id'=>$adminInfo->id]);
//                     跳转
                        \Yii::$app->session->setFlash('success', '登陆成功');
                        return $this->redirect(['admin/index']);
                    } else {
                        //提示密码错误
                        $model->addError('password', '密码错误');
                    }
                } else {
                    //提示
                    $model->addError('username', '没有此用户');
                }
            }
        }
        return $this->render('login', ['model' => $model]);
    }
        public function actionIspost(){
            //isGuest是判断用户身份是否是游客如果是游客返回true表示没有登陆   如果已经登陆返回false
            if (\Yii::$app->user->isGuest) {
                echo '你还未登陆';
                exit;
            }else{
                echo '用户已经登陆';
                exit;
            }
        }
    public function actionLogout()
    {
        //用户退出登陆操作
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);
    }
    public function actionXiu(){
       //获取当前登录用户的id
       $id=\yii::$app->user->identity->getId();
       //查找对应id的数据
        $xiu = new Xiu;
       $model = Admin::findOne(['id'=>$id]);
       //如果是post提交 接受传过来的值
//        var_dump($model->password_hash);exit;
        if (\Yii::$app->request->post()) {
            //验证传过来的值与数据库中的值是否一致
            $mo = \Yii::$app->request->post();
//            var_dump($mo);exit;
            if (\Yii::$app->security->validatePassword($mo['Xiu']['oldpassword'], $model->password_hash)) {
                  //一致则将新密码保存
                $model->password_hash = \Yii::$app->security->generatePasswordHash($mo['Xiu']['newpassword']);
                //保存
                  //验证通过则保存
                if ($model->validate()) {
                    $model->save();
                }else{
                    //打印错误信息
                    var_dump($model->getErrors());exit;
                }
                //跳转
                $this->redirect(['admin/index']);
            } else {

                $model->addError('password', '密码错误');
              $this->redirect(['admin/xiu']);
            }
        }
//       var_dump($id);exit;
        return $this->render('xiu', ['xiu' => $xiu]);
    }
}
