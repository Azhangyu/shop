<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goodscategory".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
class Goodscategory extends \yii\db\ActiveRecord
{
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodscategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],

            [['parent_id'],'varparent'],//自定义验证规则
        ];
    }
public function varparent(){
        //判定条件
    if ($this->parent_id == $this->id) {
        //只处理不符合验证规则的情况
        //提示错误信息   attribute   error提示的错误信息
        $this->addError('parent_id','当前节点不能作为父节点');
    }
}
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '商品分类名称',
            'parent_id' => '上级分类id',
            'intro' => '简介',
        ];
    }
    //获取商品分类ztree数据
    public static function getZNodes()
    {
        return Json::encode(
            //静态方法
            ArrayHelper::merge(
                //定义顶级的节点
                [['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],
                //数组
                self::find()->select(['id','name','parent_id'])->asArray()->all()
            )
        );
    }
    //建立父分类与子分类一对多的关系  自身模型建立的关系  parent_id 被关联的id   id 关联的id
    public function getChilen(){
        return $this->hasMany(Goodscategory::className(),['parent_id'=>'id']);
    }
}
