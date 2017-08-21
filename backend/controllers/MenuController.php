<?php

namespace backend\controllers;

use backend\models\Menu;
use backend\models\PermissionForm;

class MenuController extends \yii\web\Controller
{
    //添加菜单
    public function actionAdd(){
        $model = new Menu();
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
              $model->save();
              \yii::$app->session->setFlash('seccess','新增菜单成功');
              return $this->redirect(['menu/index']);
        }else{
            var_dump($model->getErrors());
        }

        return $this->render('add',['model'=>$model]);

    }
    //菜单显示界面
    public function actionIndex()
    {
//        查询所有菜单数据
        $menus = Menu::find()->all();
        return $this->render('index',['menus'=>$menus]);
    }
  //菜单修改
    public function actionEdit($id){
        $model = Menu::findOne(['id'=>$id]);
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \yii::$app->session->setFlash('seccess','修改菜单成功');
            return $this->redirect(['menu/index']);
        }else{
            var_dump($model->getErrors());
        }

        return $this->render('add',['model'=>$model]);
    }

    //ajax删除图片
    public function actionDel($id)
    {
        //根据id删除结果
      echo Menu::findOne(['id'=>$id])->delete();
    }

}
