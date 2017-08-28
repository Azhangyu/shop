<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
    //post接收数据  get显示页面
    public function actionList()
    {
        $model = new Address;
        $rows = Address::find()->all();
//        var_dump($rows);exit;
        if ($model->load(\yii::$app->request->post(),'') && $model->validate()) {
//              echo "123";exit;
//            var_dump($model->checkbox);exit;
            $model->save();
            return $this->redirect(['address/list']);
        }else {
//            var_dump($model->getErrors());exit;
        }
        return $this->render('list',['rows'=>$rows,'model'=>$model]);
    }
   //删除
    public function actionDel($id){
        $model = Address::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['address/list']);
    }
    public function actionMo($id){
        $model = Address::findOne(['id'=>$id]);
        $model->checkbox=1;
        $model->save();
        return $this->redirect(['address/list']);
    }
    public function actionEdit($id){
        $model = Address::findOne(['id'=>$id]);
        $rows = Address::find()->all();
//        var_dump($rows);exit;/
        if ($model->load(\yii::$app->request->post(),'') && $model->validate()) {
//              echo "123";exit;
//
            $model->save();
            return $this->redirect(['address/list']);
        }else {
//
        }
        return $this->render('list',['model'=>$model,'rows'=>$rows]);
    }
    }
