<?php

namespace app\models;

use Yii;
use app\components\CachedBehavior;

class City extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'CachedBehavior' => [
                'class' => CachedBehavior::className(),
                'cache_name' => 'cities',               
            ],
        ];
    }

    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'name2'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Город',
            'name2' => 'в Городе',
            'latitude' => 'Широта',
            'longitude' => 'Долгота',
        ];
    }

    /**
     * Gets query for [[ServiceStations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceStations()
    {
        return $this->hasMany(ServiceStation::class, ['city_id' => 'id']);
    }
}
