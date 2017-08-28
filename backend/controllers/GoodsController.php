<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\Goodscategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;


class GoodsController extends \yii\web\Controller
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
    //商品添加
    public function actionAdd()
    {

        $model = new  Goods();
        $goodsIntro =new GoodsIntro();
        //goods_day_count 商品每日添加数
        $goodsCount = new GoodsDayCount();
        //判定和验证是否成功  goods；GoodsIntro  首先验证
        if ($model->load(\Yii::$app->request->post())&& $model->validate()&&$goodsIntro->load(\Yii::$app->request->post()) && $goodsIntro->validate()){
            $goodsca = Goodscategory::findOne(['id'=>$model->goods_category_id]);
//            var_dump($goodsca->depth);exit;
            if($goodsca->depth <=1){
                \Yii::$app->session->setFlash('danger', '只能选择3级分类');
                //页面刷新
                return $this->refresh();
            }
//            var_dump($goodsca->depth);exit;
//            var_dump($goodscategory->intro);exit;
            //获取GoodsDayCount单独一个对象
            $GoodsCountOne = GoodsDayCount::find()->where(['day'=>date('Ymd')])->one();
            //判定该对象是否存在
            if ($GoodsCountOne !=null){
                //存在添加货号
                $count1 = ($GoodsCountOne->count+1);
                $sn=str_pad($count1,4,"0",STR_PAD_LEFT);
                $model->sn = date('Ymd').$sn;
                $GoodsCountOne->save();
                //让记录表记录加1
                $GoodsCountOne->count = $GoodsCountOne->count+1;
            }else{
                //否则获取GoodsDayCount对象
                $num =1;
                //添加货号用0补充
                $sn=str_pad($num,4,"0",STR_PAD_LEFT);
                //拼接货号
                $model->sn =  date('Ymd').$sn;
                //给GoodsDayCount对象属性赋值
                $goodsCount->day = date('Ymd');
                //保存
                $goodsCount->save();
                $goodsCount->count =1;
            }
                $model->create_time = time();
//            var_dump($model->create_time);exit;
                $model->save();
                //保存商品详情内容 将id赋值给商品详情内容的goods_id
                $goodsIntro->goods_id = $model->id;
                $goodsIntro->save();
            \Yii::$app->session->setFlash('success','添加成功');

            return $this->redirect(['goods/index']);
        }
        $goodscategory = Goodscategory::find()->all();
        $brand = Brand::find()->all();
        return $this->render('add',['model'=>$model,'goodsIntro'=>$goodsIntro,'goodscategory'=>$goodscategory,'brand'=>$brand]);

    }
    //商品修改
    public function actionEdit($id)
    {
        $model = Goods::findOne(['id' => $id]);
        $goodsIntro =GoodsIntro::findOne(['goods_id'=>$id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($goodsIntro->load(\Yii::$app->request->post()) && $goodsIntro->validate()) {
                $goodsIntro->goods_id = $model->id;
                $goodsIntro->save();
            }
            \Yii::$app->session->setFlash('success', '修改成功');

            return $this->redirect(['goods/index']);
        }
        $goodscategory = Goodscategory::find()->all();
        $brand = Brand::find()->all();
        return $this->render('add',['model'=>$model,'goodsIntro'=>$goodsIntro,'goodscategory'=>$goodscategory,'brand'=>$brand]);
}
    //商品列表显示页面
    public function actionIndex()
    {
        $name=\Yii::$app->request->get('name');//?\Yii::$app->request->get('name'):"";
        if($name){
            $query=Goods::find()
                ->where("goods.status=1")
                //andwhere 条件需要中括号
                ->andWhere(['like','name',$name]);
        }else{
            $query=Goods::find()
                ->where("goods.status=1");
        }



        //分页
            $page= new Pagination([
            //总数据条数
            'totalCount'=>$query->count(),
            //每页显示条数
            'defaultPageSize' =>5,
        ]);
        $goods=$query->offset($page->offset)
            ->limit($page->pageSize)
            ->all();
        return $this->render('index',['goods'=>$goods,'page'=>$page]);
    }

    //商品删除
    public function actionDel($id){
        $model = Goods::findOne(['id'=>$id]);
        $model->status = 0;
        $model->save();
        return $this->redirect(['goods/index']);
    }

    //相册
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }

    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /* 'format' => function (UploadAction $action) {
                     $fileext = $action->uploadfile->getExtension();
                     $filename = sha1_file($action->uploadfile->tempName);
                     return "{$filename}.{$fileext}";
                 },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        //相册上传图片
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $action->getWebUrl();
                        $action->output['id'] = $model->id;//图片的id
                    }else {
//                    $action->output['fileUrl'] = $action->getWebUrl();//输出图片地址
                        //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                        //$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                        //$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                        $config = [
                            'accessKey' => 'UBwNuAOVXZ8cRvwVE0IkSgaKZbiSQ5pNZTh6A_sk',//AK
                            'secretKey' => 'o-XpVo3ZeH2uXorX9HC8KTA6MPckHLOhQa7PT0Gh',//SK
                            'domain' => 'http://ouk7shetn.bkt.clouddn.com/',//测试域名 需要加上http://  ..../
                            'bucket' => 'shop',//存储空间名称
                            'area' => Qiniu::AREA_HUADONG//区域
                        ];

                        $qiniu = new Qiniu($config);
                        $key = $action->getWebUrl();//文件名
                        $file = $action->getSavePath();
                        $qiniu->uploadFile($file, $key);//上传文件到七牛云存储
                        $url = $qiniu->getLink($key);//根据文件名获取七牛云的文件路径
                        $action->output['fileUrl'] = $url;//输出图片地址
                    }
                },
            ],
        ];
    }
    //ajax删除图片
    public function actionAjaxDel()
    {
        $model = GoodsGallery::findOne(['id'=>\Yii::$app->request->post('id')]);
        if($model){
            $model->delete();
            return 'success';
        }
        return 'fail';
    }
}
