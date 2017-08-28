<?php

namespace frontend\controllers;



use backend\models\Goods;
use backend\models\Goodscategory;
use yii\data\Pagination;
use yii\web\HttpException;

class ShowController extends \yii\web\Controller
{
//    商品的分类首页展示
    public function actionIndex()
    {
        //获取所有的一级分类数据
        $rows = Goodscategory::findAll(['parent_id'=>0]);
//        var_dump($rows);exit;
        return $this->render('index',['rows'=>$rows]);
    }
//   商品展示      //查找出三级分类
//   public function actionList($id){
//
//        $models = Goods::findAll(['goods_category_id'=>$id]);
////        var_dump($model);exit;
//        return $this->render('list',['models'=>$models]);
//   }
   //查找二级分类
//   public function actionLists($id){
//       $cate = Goodscategory::findOne($id);
////       var_dump($cate->rgt);exit;
////       $ids = Goodscategory::find()->where('depth' ==2 AND 'lft'>$cate->lft AND 'rgt'<$cate->rgt);
//       $ids = Goodscategory::find()->where('depth' ==2 AND 'lft'>$cate->lft AND 'rgt'<$cate->rgt)->all();
////       var_dump($rows);exit;
//       $models = Goods::findAll(['in','goods_category_id',$ids]);
//
//       return $this->render('list',['models'=>$models]);
//   }
    public function actionList($id)
    {
        //获取当前id对应的商品分类信息
        $data = GoodsCategory::findOne($id);
        if ($data ==null){
            throw new HttpException('404','商品分类未找到');
        }
        if ($data->depth==2){
            //获取所有3级分类信息
            $models = Goods::find()->where(['goods_category_id'=>$id]);
            $pages = new Pagination(['totalCount' => $models->count(), 'defaultPageSize' => '8']);

            //获取根据条件查询的数据
            $models = $models->offset($pages->offset)->limit($pages->limit)->all();
        }
        //2级分类 和1级分类
        if ($data->depth ==1 or $data->depth ==0) {
            //获取当前2级分类的所有子id
            $modelsT = GoodsCategory::findOne($id);
            //根据左值右值和树id获取数据
            $ids = GoodsCategory::find()->select('id')->andWhere(['=','depth',2])->andWhere(['=','tree',$modelsT->tree])->andWhere(['>', 'lft', $modelsT->lft])->andWhere(['<', 'rgt', $modelsT->rgt])->column();

            //in方法分配查询数据
            $models = Goods::find()->where(['in','goods_category_id',$ids]);
            //调用分页工具
            $pages = new Pagination(['totalCount' => $models->count(), 'defaultPageSize' => '8']);
            //获取根据条件查询的数据
            $models = $models->offset($pages->offset)->limit($pages->limit)->all();
        }

        //获取所有顶级分类
        $modelsCategorys = Goodscategory::findAll(['parent_id'=>0]);

        //显示页面
        return $this->render('list',['models'=>$models,'pages'=>$pages]);
    }
}
