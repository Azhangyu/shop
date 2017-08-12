<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Articlecategory;
use backend\models\Articledetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{

    public function actionAdd()
    {
        $modelA = new Article;//实例化模型Article
        $modelB = new Articledetail;//实例化模型Articledetail
        if (isset($_POST['Article']) && isset($_POST['Articledetail']))//判断用户数据与身份信息
        {
            $modelA->attributes = $_POST['Article'];
            $modelB->attributes = $_POST['Articledetail'];
            if ($modelA->validate() && $modelB->validate())//这里是先验证数据，如果通过再save()。
            {
                if ($modelB->save(false))//前面已经验证通过了,这里加个false,表示保存之前不需要再验证了！
                {
                    $modelA->id = $modelB->article_id;
                    if ($modelA->save(false)) {
                        $this->redirect(['article/index']);
                    }
                }
            }
        }
        $row= Articlecategory::find()->all();
        return $this->render('add',['modelA'=>$modelA,'modelB'=>$modelB,'row'=>$row]);

    }

    //修改文章
    public function actionEdit($id)
    {
//    回显
        $modelA =Article::findOne(['id' => $id]);//实例化模型Article
        $modelB =Articledetail::findOne(['article_id' => $id]);//实例化模型Articledetail
        if (isset($_POST['Article']) && isset($_POST['Articledetail']))//判断用户数据与身份信息
        {
            $modelA->attributes = $_POST['Article'];
            $modelB->attributes = $_POST['Articledetail'];
            if ($modelA->validate() && $modelB->validate())//验证数据，如果通过再save()。
            {
                if ($modelB->save(false))//前面已经验证通过了,这里加个false,表示保存之前不需要再验证了！
                {
                    $modelA->id = $modelB->article_id;
                    if ($modelA->save(false)) {
                        $this->redirect(['article/index']);
                    }
                }
                \Yii::$app->session->setFlash('success','添加成功');
            }
        }
        $row= Articlecategory::find()->all();
        return $this->render('add',['modelA'=>$modelA,'modelB'=>$modelB,'row'=>$row]);
    }
    //删除文章
    public function actionDel($id){
      $model= Article::findOne(['id'=>$id]);
      //将状态更改为-1
      $model->status=-1;
      //重新保存
      $model->save();
      //跳转
        return $this->redirect(['article/index']);
    }
    //文章显示主页
    public function actionIndex()
    {
        //读取数据 链表查询
        $rows =Article::find()->joinWith('index')->asArray()->where('Article.status>=0');

        //分页操作
        $page= new Pagination([
            //总数据条数
            'totalCount'=>$rows->count(),
            //每页显示条数
            'defaultPageSize' =>5,
//            'pageSizeLimit' => [1,20]
        ]);
        $row=$rows->offset($page->offset)
            ->limit($page->pageSize)
            ->all();
        return $this->render('index',['rows'=>$row,'page'=>$page]);
    }

}
