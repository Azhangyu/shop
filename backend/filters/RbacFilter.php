<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{
    //
    public function beforeAction($action)
    {
        $action->uniqueId;//当前访问的路由
        if(!\Yii::$app->user->can($action->uniqueId)){
            //如果用户没有登陆，则引导用户到登陆页面
            if(\Yii::$app->user->isGuest){
                //send()方法
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            //没有该执行权限，抛出403状态码
            throw new HttpException(403,'您没有该执行权限');

        }

        return parent::beforeAction($action);
    }

}