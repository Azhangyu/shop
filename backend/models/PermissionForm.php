<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model {
    public $name;
    public $description;

    //验证规则
    public function rules()
    {
        return [
          [['name','description'],'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => '权限名字',
            'description'=>'简介'
        ];
    }
    public function save(){
        //实例authManager对象
        $authManager = \Yii::$app->authManager;
        //如果添加的权限已存在 则 提示 并返回false
        if($authManager->getPermission($this->name)){
         $this->addError('name','权限已存在');
           return false;
       }else{
            //创建一个新的权限createPermission  赋值name
           $permission = $authManager->createPermission($this->name);
           //赋值description
           $permission->description = $this->description;
           //add添加保存
           $authManager->add($permission);
           //返回true
          return true;
        }
    }
   public function edit($name)
   {
       $parmission = \Yii::$app->authManager->getPermission($name);
       $authManager =\Yii::$app->authManager;
       //将提交的数据赋值给$parmission
       $parmission->name =$this->name;
       $parmission->description = $this->description;
       //update更新
       $authManager->update($name, $parmission);
//       return true;
   }

}




