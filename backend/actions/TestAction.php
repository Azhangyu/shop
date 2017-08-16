<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/6
 * Time: 9:18
 */

namespace backend\actions;


use yii\base\Action;

class TestAction extends Action
{
//    public $name;

    public function run()
    {
        echo 'this is test action in actions folde';
//        echo $this->testActionDo();
//        echo "\n {$this->name}";

    }

}