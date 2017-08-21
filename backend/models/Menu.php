<?php

namespace backend\models;

use function Sodium\crypto_box_keypair_from_secretkey_and_publickey;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property integer $parent_id
 * @property string $url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'parent_id', 'url', 'sort'], 'required'],
            [[ 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名字',
            'parent_id' => '上级菜单',
            'url' => '路由地址',
            'sort' => '排序',
        ];
    }
    //获取菜单
  public static function caidan(){
        //查询所有数据
        $caidan= Menu::find()->all();
//        var_dump($model);exit;
      //ArrayHelper_merge合并数组
      return ArrayHelper::merge([0=>'==请选择菜单=='],ArrayHelper::map($caidan,'id','label'));
    }
    //获取所有路由
   public static function permissions(){
      //调用组件查询
       $permissions= \yii::$app->authManager->getPermissions();
       //合并数组
       return ArrayHelper::merge(['0'=>'==请选择路由=='],ArrayHelper::map($permissions,'name','name'));
   }

}




//        var_dump($menus);exit;
        /*$menu1 = [
            'id'=>1,
            'label'=>'用户管理',
            'parent_id'=>0,
            'url'=>'',
            'sort'=>1
        ];
        $menu2 = [
            'id'=>2,
            'label'=>'添加用户',
            'parent_id'=>1,
            'url'=>'admin/add',
            'sort'=>1
        ];*/
        //2 遍历一级菜单
//        foreach ($menus as $menu){
//            //3.获取该一级菜单的所有子菜单
//            $children = [];//
//            $items = [];
//            //4遍历所有子菜单
//            foreach ($children as $child){
//                //根据用户权限决定是否添加到items里面
//                if(Yii::$app->user->can($child->url)){
//                    $items[] = ['label' =>$child->label, 'url' => [$child->url]];
//                }
//            }
//            $menuItems[] = ['label'=>$menu->label,'items'=>$items];
//            /*$menuItems[] = ['label' => '一级菜单', 'items'=>[
//                ['label' => '第一个二级菜单', 'url' => ['admin/add']],
//                ['label' => '第二个二级菜单', 'url' => ['admin/index']]
//            ]];*/
//        }

//        return $menuItems;
//    }
//}
