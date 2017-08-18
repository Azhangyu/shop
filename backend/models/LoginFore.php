<?php
namespace backend\models;

use yii\base\Model;

class LoginFore extends Model{
    public $username;
    public $password;
    public $cap;
    public $rememberMe;

    //定义重复密码
    public $password_reset;

    //验证规则
    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['cap','captcha','captchaAction'=>'admin/cap','message' =>'验证码错误',],
            ['rememberMe','safe'],//这个字段是安全的
            [['password_reset'],'compare','compareAttribute'=>'newpassword','operator'=>'===','message'=>'两次密码必须一致']
        ];
//        return parent::rules(); // TODO: Change the autogenerated stub
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password'=>'密码',
            'cap'=>'验证码',
            'rememberMe'=>'记住我',


        ];
    }
}
