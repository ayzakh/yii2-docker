<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "porch".
 *
 * @property int $id
 * @property string $name
 * @property int $build_id
 *
 * @property Level[] $levels
 * @property Build $build
 */
class Porch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'porch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'build_id'], 'required'],
            [['build_id'], 'default', 'value' => null],
            [['build_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['build_id'], 'exist', 'skipOnError' => true, 'targetClass' => Build::className(), 'targetAttribute' => ['build_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'build_id' => 'Build ID',
        ];
    }

    /**
     * Gets query for [[Levels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevels()
    {
        return $this->hasMany(Level::className(), ['porch_id' => 'id']);
    }

    /**
     * Gets query for [[Build]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBuild()
    {
        return $this->hasOne(Build::className(), ['id' => 'build_id']);
    }
}
