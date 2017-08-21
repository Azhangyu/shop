<?php
namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;

public function rules()
{
    return [
      [['name','description',],'required'],
      ['permissions','safe']
    ];
}

    public function attributeLabels()
    {
        return [
            'name' => '角色名字',
            'description'=>'简介',
            'permissions'=>'赋予权限'
        ];
    }
    public function save(){

        $authManager = \Yii::$app->authManager;
        //判断角色是否存在
        if($authManager->getRole($this->name)){
            //存在则提示 返回false
            $this->addError('name','角色已存在');
            return false;
        }else{
            //创建新的角色
            $role = $authManager->createRole($this->name);
            //赋值
            $role->description = $this->description;
            //保存
            $authManager->add($role);
            //角色关联权限
            if(is_array($this->permissions)){
//                var_dump($this->permissions);exit;
                //循环  追加
                foreach ($this->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);//角色，权限
                }
            }

            return true;
        }

    }
    public function edit($name){
        $role = \Yii::$app->authManager->getRole($name);
//        var_dump($role->permissions);exit;
        $authManager =\Yii::$app->authManager;
        //将提交的数据赋值给$role
        $role->name =$this->name;
        $role->description = $this->description;
        //update更新
        $authManager->update($name, $role);
        if(is_array($this->permissions)){
            //首先清除所有权限
            $authManager->removeChildren($role);
            //循环  追加
            foreach ($this->permissions as $permissionName){
                $permission = $authManager->getPermission($permissionName);
                $authManager->addChild($role,$permission);//角色，权限

            }
        }
//        return true;
    }
    public static function getPermissionItems()
    {
      return ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','description');
    }
}
