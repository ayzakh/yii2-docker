<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appartment".
 *
 * @property int $id
 * @property int $level_id
 * @property string $num
 *
 * @property Level $level
 * @property AppartmentCoordinate[] $appartmentCoordinates
 */
class Appartment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appartment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level_id', 'num'], 'required'],
            [['level_id'], 'default', 'value' => null],
            [['level_id'], 'integer'],
            [['num'], 'string', 'max' => 255],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => Level::className(), 'targetAttribute' => ['level_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level_id' => 'Level ID',
            'num' => 'Num',
        ];
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['id' => 'level_id']);
    }

    /**
     * Gets query for [[AppartmentCoordinates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppartmentCoordinates()
    {
        return $this->hasMany(AppartmentCoordinate::className(), ['appartment_id' => 'id']);
    }
}
