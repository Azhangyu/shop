<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $last_login_time
 * @property integer $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    //定义关联角色
    public $roles;
    //定义明文密码
    public $password;
    //定义场景
    const SCENARIO_ADD = 'add';
    //定义重复密码属性
//    public $password_reset;
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
//     , 'created_at', 'updated_at'    'email', 'created_at', 'updated_at'
        return [
            [['username','email','status'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_ip'], 'integer'],
            [['last_login_time','roles'], 'safe'],
            [['username', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 100],
            [['username'], 'unique'],//unique 唯一
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['password'], 'string', 'max' => 255],
            //验证密码 是否为空 添加时不能为空，修改时为空则表示密码不修改
            [['password'],'required','on'=>[self::SCENARIO_ADD]],
            //邮箱验证规则
            [['email'], 'email'],
            //验证码验证规则 captcha   验证的路径captchaAction   提示信息message
            [['cap'],'captcha','captchaAction'=>'admin/cap','message'  =>'验证码错误'],
            //自定义验证规则
//            [['password_reset'],'compare','compareAttribute'=>'password','operator'=>'===','message'=>'两次密码必须一致']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'password_reset'=>'重复密码',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
            'cap'=>'验证码',
            'oldpassword'=>'旧密码',
            'newpassword'=>'新密码',
            'roles'=>'赋予角色'

        ];
    }
    //获取用户菜单
    public function getMenus()
    {
        $menuItems = [];
//        //1 . 获取所有一级菜单
        $menus = Menu::findAll(['parent_id' => 0]);
//
        foreach ($menus as $menu) {
            //获取所有二级菜单
            $children = Menu::findAll(['parent_id' => $menu['id']]);
//            var_dump($children);exit;
            $items = [];
            //遍历子菜单
            foreach ($children as $child) {
                //根据用户权限决定是否添加到items里面
//                if(Yii::$app->user->can($child->url)){
                    $items[] = ['label' =>$child->label, 'url' => [$child->url]];
//                }
        }
            $menuItems[] = ['label' => $menu->label, 'items' => $items];
        }
        return $menuItems;
    }
    //保存之前 做的事情  beforeSave
    public function beforeSave($insert)
    {
        if ($insert) {


            //保存添加时间
            $this->created_at= time();
            //设置auth_key
            $this->auth_key = Yii::$app->security->generateRandomString();
        }else{
            $authManager=\Yii::$app->authManager;
//            var_dump($this->id);exit;
            //修改时 如果都没有勾选 说明是字符串   清除所有角色
            $authManager->revokeAll($this->id);
            //有勾选 就追加
            if(is_array($this->roles)){
                //循环  追加
                foreach ($this->roles as $roleName){
                    $role = $authManager->getRole($roleName);
                    $authManager->assign($role,$this->id);//角色，用户id
                }
            }
            //保存修改时间
            $this->updated_at= time();
        }
        //整理代码
        if($this->password){
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }



    public static function getRoleItems()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(),'name','description');
    }

//根据id主键获取用户的实例对象
    public static function findIdentity($id)
    {
        return static::findOne($id);
        // TODO: Implement findIdentity() method.
    }
//获取token登陆时的用户实例
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }
//获取确定对象的id
    public function getId()
    {
        return $this->id;
        // TODO: Implement getId() method.
    }
//获取自动登陆authkey
    public function getAuthKey()
    {
        return $this->auth_key;
        // TODO: Implement getAuthKey() method.
    }

    //验证自动登陆authkey
    public function validateAuthKey($authKey)
    {
        return $authKey === $this->getAuthKey();
        // TODO: Implement validateAuthKey() method.
    }

}
