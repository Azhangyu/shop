<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Articlecategory;
use yii\data\Pagination;

class ArticlecategoryController extends \yii\web\Controller
{
    //配置过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login','logout','cap','upload','s-upload','gallery','gii']
            ]
        ];
    }
    //添加文章
    public function actionAdd(){
        //实例化模型及组件
        $model = new ArticleCategory();
        $re = \Yii::$app->request;
//        ifPost判定
        if($re->isPost){
//        2:接收表单数据  写入数据库
//            load绑定数据
            $model->load($re->post());
            //验证数据
            $model->validate();
            //保存数据
            $model->save();
           //提示信息
            \Yii::$app->session->setFlash('success','成功添加新的分类');
            //数据添加完成跳转到首页
            return $this->redirect(['articlecategory/index']);
        }
        //1：添加页面的显示
        return $this->render('add',['model'=>$model]);
    }
    //修改文章
    public function actionEdit($id){
      $model=Articlecategory::findone(['id'=>$id]);
        $re = \Yii::$app->request;
//        ifPost判定
        if($re->isPost){
//        2:接收表单数据  写入数据库
//            load绑定数据
            $model->load($re->post());
            //验证数据
            $model->validate();
            //保存数据
            $model->save();
            //提示信息
            \Yii::$app->session->setFlash('success','修改了分类');
            //数据添加完成跳转到首页
            return $this->redirect(['articlecategory/index']);
        }
        //1：添加页面的显示
        return $this->render('add',['model'=>$model]);
    }
    //删除文章
    public function actionDel($id){
        $model = articlecategory::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save();
        //提示信息
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['articlecategory/index']);
    }

    //文章显示页面
    public function actionIndex()
    {
     //根据状态接收数据
        $rows=Articlecategory::find()->where('status>=0');
        //分页操作
        $page= new Pagination([
            //总数据条数
            'totalCount'=>$rows->count(),
            //每页显示条数
            'defaultPageSize' =>5,
            'pageSizeLimit' => [1,20]
        ]);
        $ategory=$rows->offset($page->offset)
            ->limit($page->pageSize)
            ->all();
        return $this->render('index',['rows'=>$ategory,'page'=>$page]);
    }

}
