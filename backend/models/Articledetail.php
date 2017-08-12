<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "articledetail".
 *
 * @property integer $article_id
 * @property string $content
 */
class Articledetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articledetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'content' => '文章内容',
        ];
    }
}
