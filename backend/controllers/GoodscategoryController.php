<?php

namespace backend\controllers;

use backend\models\Goodscategory;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\HttpException;

class GoodscategoryController extends Controller
{
//    商品分类添加
    public function actionAdd(){
         //实例化模型
        $model = new Goodscategory;
       //判定
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            //判断添加顶级还是子分类
            if($model->parent_id){  //添加子分类
                //添加子分类
                $parent = Goodscategory::findOne(['id'=>$model->parent_id]);
                //判断  深度最多大于等于2  最多添加3级节点
                if ($parent->depth >=2) {
                    //提示信息并跳转
                    \Yii::$app->session->setFlash('danger','最多只能添加到3级节点');
                    return $this->redirect(['goodscategory/add']);
                }
//                var_dump($parent->depth);exit;
                //创建子分类
//                var_dump($parent);exit;
                $model->prependTo($parent);
            }else{
                //添加顶级分类
                $model->makeRoot();
            }
//            提示信息
            \Yii::$app->session->setFlash('success','成功添加新的商品');
            //跳转
            return $this->redirect(['goodscategory/index']);
        }
        //显示添加页面
        return $this->render('add',['model'=>$model]);
    }

    public function actionIndex()
    {
        //商品分类显示页面
        //排序根据tree lft实现缩进
        $rows =Goodscategory::find()->orderBy('tree,lft');
        $page= new Pagination([
            //总数据条数
            'totalCount'=>$rows->count(),
            //每页显示条数
            'defaultPageSize' =>5,
            'pageSizeLimit' => [1,20]
        ]);
        $Goodscategorys=$rows->offset($page->offset)
            ->limit($page->pageSize)
            ->all();
        return $this->render('index',['rows'=>$Goodscategorys,'page'=>$page]);
    }
//    商品分类修改
   public function actionEdit($id){
   $model = Goodscategory::find()->where(['id'=>$id])->one();
       //try 捕获异常
       try {
//   var_dump($model->parent_id);exit;
           if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
//           if($model->parent_id === $id){
//               // 分别捕获  异常捕获throw new HttpException   提示status403 message 提示信息
//               throw new HttpException(403,'当前节点不能作为父节点');
//           }
               //判断添加顶级还是子分类
               if ($model->parent_id) {
                   //添加子分类
                   $parent = Goodscategory::findOne(['id' => $model->parent_id]);
                   //判断  深度最多大于等于2  最多添加3级节点
                   if ($parent->depth >=2) {
                       //提示信息并跳转
                       \Yii::$app->session->setFlash('danger','最多只能添加到3级节点');
                       return $this->redirect(['goodscategory/add']);
                   }
                   //创建子分类
                   $model->prependTo($parent);
               } else {
                   //判断修改根节点
                   //判断修改的根节点与旧根节点
                   if ($model->getOldAttribute('parent_id')) {
                       //新的修改添加顶级分类调用makeRoot()方法保存
                       $model->makeRoot();
                   } else {
                       //是旧的根节点调save()方法保存
                       $model->save();
                   }
               }
//            提示信息
               \Yii::$app->session->setFlash('success', '成功修改');
               //跳转
               return $this->redirect(['goodscategory/index']);
           }
           //输出提示信息
       }catch (Exception $a){
           \Yii::$app->session->setFlash('danger', '当前操作不合法');
           //页面刷新
           return $this->refresh();
       }
       //显示修改页面
       return $this->render('add',['model'=>$model]);
   }

   //商品分类删除
    public function actionDel($id){
        $model = Goodscategory::find()->where(['id'=> $id])->one();
//        var_dump($model->parent_id );exit;
//        判断节点下是否有子节点 有则不能删除
        if(Goodscategory::find()->where(['parent_id'=> $id])->count()){
            //覆盖不能删除根节点的错误信息
            \Yii::$app->session->setFlash('danger','不能删除根节点');
            return $this->redirect(['goodscategory/index']);
        }
//        $model->isLeaf();//是否是叶子，是否有子节点
        //deleteWithChildren 删除根节点及以下子节点
        $model->deleteWithChildren();
        return $this->redirect(['goodscategory/index']);
    }
}
