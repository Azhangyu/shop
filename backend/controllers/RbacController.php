<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;

class RbacController extends \yii\web\Controller
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
    public function actionIndex(){
        return $this->render('index');
    }
    //添加权限
    public function actionParmissionadd()
    {
        //实例化对象
        $model = new PermissionForm();
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            //保存  这里的save()是在模型中自定义的方法
            $model->save();
            //提示信息以及跳转
            \yii::$app->session->setFlash('success', "添加成功");

            return $this->redirect(['rbac/parmissionindex']);
        }
        return $this->render('permission', ['model' => $model]);
    }

    //权限显示列表
    public function actionParmissionindex()
    {
        //获取到所有的权限getPermissions
        $parmissions = \Yii::$app->authManager->getPermissions();
        //返回给显示的页面
        return $this->render('parmissionindex', ['parmissions' => $parmissions]);
    }

    //修改权限
    public function actionParmissionedit($name)
    {
        //实例化表单模型
        $model = new PermissionForm();
        //查找对应name的那条数据
        $parmission = \Yii::$app->authManager->getPermission($name);
        //回显 将读取出来的赋值给表单模型
        $model->name = $parmission->name;
        $model->description = $parmission->description;
        //判定如果是表单提交过来数据
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            $model->edit($name);

            //跳转
            \yii::$app->session->setFlash('success', "修改成功");

            return $this->redirect(['rbac/parmissionindex']);
        }
        return $this->render('permission', ['model' => $model]);
    }

    //删除
    public function actionParmissiondel($name)
    {
        //查找对应name的那条数据
        $parmission = \Yii::$app->authManager->getPermission($name);
        //删除当前权限
        \Yii::$app->authManager->remove($parmission);
        \yii::$app->session->setFlash('success', "删除成功");

        return $this->redirect(['rbac/parmissionindex']);
    }

    //新增角色
    public function actionRoleadd(){
      $model = new RoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->save()) {
                //提示信息以及跳转
                \yii::$app->session->setFlash('success', "添加成功");
                return $this->redirect(['rbac/roleindex']);
            }

        }
        return $this->render('role',['model'=>$model]);
    }
    //角色显示界面
    public function actionRoleindex(){
        //获取所有角色
      $roles = \Yii::$app->authManager->getRoles();

      //跳转页面
      return $this->render('roleindex',['roles'=>$roles]);

    }
    //修改角色
    public function actionRoleedit($name){
        //实例化表单模型
        $model = new RoleForm();
        //查找对应name的那条数据
        $role= \Yii::$app->authManager->getRole($name);
//
        //回显
        $model->name = $role->name;
        $model->description = $role->description;
        //根据权限找到角色名getPermissionsByRole
        $permissions = \yii::$app->authManager->getPermissionsByRole($name);
  //array_keys 返回包含数组中所有键名的一个新数组
        $model->permissions=(array_keys($permissions));
//        var_dump($_POST);exit;

        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            $model->edit($name);
//                      var_dump($permission);exit;
            //提示信息 并跳转
            \yii::$app->session->setFlash('seccess','修改成功');

            return $this->redirect(['rbac/roleindex']);
        }
       return $this->render('role',['model'=>$model]);
    }
  //删除角色
    public function actionRoledel($name){
     //查找对应name的值
        $role = \Yii::$app->authManager->getRole($name);
        \Yii::$app->authManager->remove($role);
        //提示 跳转
        \yii::$app->session->setFlash('success','成功删除该角色');

        return $this->redirect(['rbac/roleindex']);
    }

}
