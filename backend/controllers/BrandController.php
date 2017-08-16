<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;
use Yii;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
class BrandController extends \yii\web\Controller
{

    //品牌添加
    public function actionAdd(){
        //实例化request组件
        $model = new Brand();
        $re= \yii::$app->request;
        //ifpost判定
        if($re->isPost){
//            2：接收表单数据 写入数据库
            //接收表单数据load
            $model->load($re->post());

            //实例化文件上传   UploadedFile::getInstance文件上传类
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//            var_dump($model);exit;
            //数据验证  有数据则保存
            if ($model->validate()){
//
//                //拼接并保存文件上传的路径  uniqid 随机生成字符串   extension 获取后缀名
//                $file= '/uploads/'.uniqid().'.'.$model->imgFile->extension;
//                //获取绝对路径
//                if ($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,false)){
//                    $model->logo=$file;
//                }
                //保存到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','成功添加新的品牌');//success警告框样式，第二个参数 提示信息
                //跳转到品牌首页
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }

        }
        //1;添加页面的显示
        //调用视图
        return $this->render('add',['model'=>$model]);
    }


    //品牌展示页面
    public function actionIndex()
    {
        //接收数据   状态大于等于0 表示删除  但实际在数据表中存在
        $rows=Brand::find()->where('status>=0');
        //分页操作
        $page= new Pagination([
            //总数据条数
            'totalCount'=>$rows->count(),
            //每页显示条数
            'defaultPageSize' =>5,
            'pageSizeLimit' => [1,20]
        ]);
        $brand=$rows->offset($page->offset)
            ->limit($page->pageSize)
            ->all();
        return $this->render('index',['rows'=>$brand,'page'=>$page]);
    }
   //品牌修改
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        $re= \yii::$app->request;
        //ifpost判定
        if($re->isPost){
//            2：接收表单数据 写入数据库
            //接收表单数据load
            $model->load($re->post());

            //实例化文件上传   UploadedFile::getInstance文件上传类
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//            var_dump($model);exit;
            //数据验证  有数据则保存
            if ($model->validate()){
                //判断不为空则添加
               if(!$model->imgFile == null){
                   $file= '/uploads/'.uniqid().'.'.$model->imgFile->extension;
                   //获取绝对路径
                   if ($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,false)){
                       $model->logo=$file;
                   }
               }
               //为空则不执行操作

                //保存到数据库
                $model->save();
                \Yii::$app->session->setFlash('danger','修改成功');//success警告框样式，第二个参数 提示信息
                //跳转到品牌首页
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }

        }
        //1;添加页面的显示
        //调用视图
        return $this->render('add',['model'=>$model]);
    }
    //品牌删除
    public function actionDel($id){

        $model = Brand::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save();
        \Yii::$app->session->setFlash('success','修改品牌成功');
        return $this->redirect(['brand/index']);
    }
    //使用文件上传插件
    public function actions(){
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/uploads',
                'baseUrl' => '@web/uploads',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                //格式化文件名
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
//                    $action->output['fileUrl'] = $action->getWebUrl();//输出图片地址
                    //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    //$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    //$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    $config = [
                        'accessKey'=>'UBwNuAOVXZ8cRvwVE0IkSgaKZbiSQ5pNZTh6A_sk',//AK
                        'secretKey'=>'o-XpVo3ZeH2uXorX9HC8KTA6MPckHLOhQa7PT0Gh',//SK
                        'domain'=>'http://ouk7shetn.bkt.clouddn.com/',//测试域名 需要加上http://  ..../
                        'bucket'=>'shop',//存储空间名称
                        'area'=>Qiniu::AREA_HUADONG//区域
                    ];

                    $qiniu = new Qiniu($config);
                    $key = $action->getWebUrl();//文件名
                    $file = $action->getSavePath();
                    $qiniu->uploadFile($file,$key);//上传文件到七牛云存储
                    $url = $qiniu->getLink($key);//根据文件名获取七牛云的文件路径
                    $action->output['fileUrl']=$url;//输出图片地址
                },
            ],
        ];
    }
}
