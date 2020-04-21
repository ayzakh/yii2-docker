<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appartment_coordinate".
 *
 * @property int $id
 * @property int $appartment_id
 * @property float $x
 * @property float $y
 *
 * @property Appartment $appartment
 */
class AppartmentCoordinate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appartment_coordinate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appartment_id', 'x', 'y'], 'required'],
            [['appartment_id'], 'default', 'value' => null],
            [['appartment_id'], 'integer'],
            [['x', 'y'], 'number'],
            [['appartment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Appartment::className(), 'targetAttribute' => ['appartment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appartment_id' => 'Appartment ID',
            'x' => 'X',
            'y' => 'Y',
        ];
    }

    /**
     * Gets query for [[Appartment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppartment()
    {
        return $this->hasOne(Appartment::className(), ['id' => 'appartment_id']);
    }
}
