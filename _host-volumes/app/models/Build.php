<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "build".
 *
 * @property int $id
 * @property int $city_id
 * @property float $latitude
 * @property float $longitude
 * @property string $address
 *
 * @property City $city
 * @property Porch[] $porches
 */
class Build extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'build';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'latitude', 'longitude', 'address'], 'required'],
            [['city_id'], 'default', 'value' => null],
            [['city_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['address'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'address' => 'Address',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Porches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPorches()
    {
        return $this->hasMany(Porch::className(), ['build_id' => 'id']);
    }

    public function getLevelMax()
    {
        $porches = $this->getPorches()->all();
        $levelMax = 0;
        foreach ($porches as $porch) {
            $count = count($porch->getLevels()->all());
            if ($count > $levelMax) $levelMax = $count;
        }
        return $levelMax;
    }
}
