<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 */
class Articlecategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articlecategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'sort', 'status'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'name' => '分类名称',
            'intro' => '分类简介',
            'sort' => '排序',
            'status' => '当前状态',
        ];
    }
}
