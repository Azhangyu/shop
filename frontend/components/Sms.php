<?php
namespace frontend\components;

use yii\base\Component;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

class Sms extends Component{
    //定义ak  sk属性
    public $ak;
    public $sk;
    //短信签名
    public $sign;
    //模板id
    public $template;
    //手机号码
    public $number;
    //替换的变量$params
    public $params=[];

    private $acsClient;

    //初始化
    public function init()
    {
        //加载区域节点配置
        Config::load();
        // 短信API产品名
        $product = "Dysmsapi";

        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";
        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $this->ak,$this->sk);
        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);
        parent::init();
    }
    public function send(){
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request= new SendSmsRequest();
        //设置短信接收号码
        $request->setPhoneNumbers($this->number);
        //设置签名的名称
        $request->setSignName($this->sign);
        //设置模板id
        $request->setTemplateCode($this->template);
        //设置模板参数
        if($this->params) {
            $request->setTemplateParam(json_encode($this->params));
        }
        //发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        return $acsResponse;
    }
    //获取号码值
    public function setNumber($value){
        $this->number = $value;
        return $this;
    }
//   获取模板
    public function setParams(Array $data){
        $this->params = $data;
        return $this;
    }


}