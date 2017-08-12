<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property string $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'article_category_id', 'sort', 'status'], 'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status'], 'integer'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章名称',
            'intro' => '文章简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }

    public function getIndex()
    {
        //对应关系                  被关联的表对应的模型路径↓   被关联表的字段↓  本模型中的字段↓
        return $this->hasOne('backend\models\Articlecategory', ['id' => 'article_category_id']);
    }
}
