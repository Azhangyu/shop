<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $cap;
    public $rememberMe;


    public function rules(){
        return [
            [['username','password'],'required'],
            ['cap','captcha','message' =>'验证码错误',],
            ['rememberMe','safe'],
        ];
    }
}
