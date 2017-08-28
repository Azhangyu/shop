<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $cmbProvince
 * @property string $cmbCity
 * @property string $cmbArea
 * @property string $xaddress
 * @property string $tel
 * @property integer $checkbox
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'xaddress', 'tel'], 'required'],
            [['checkbox'], 'integer'],
            [['name', 'cmbCity', 'cmbArea'], 'string', 'max' => 50],
            [['cmbProvince'], 'string', 'max' => 100],
            [['xaddress'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cmbProvince' => 'Cmb Province',
            'cmbCity' => 'Cmb City',
            'cmbArea' => 'Cmb Area',
            'xaddress' => 'Xaddress',
            'tel' => 'Tel',
            'checkbox' => 'Checkbox',
        ];
    }
}
