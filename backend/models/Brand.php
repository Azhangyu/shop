<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    //文件上传对象
    public $imgFile;
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    //验证规则
    public function rules()
    {
        return [
            //自动验证不为空
            [['name', 'intro', 'sort'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
//            [['logo'], 'string', 'max' => 255],
        //  file 文件上传验证规则 extensions设置哪些 后缀名通过 skipOnEmpty 字段为空跳过当前验证
        ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>true],
];
}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
