<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $name
 * @property string $guid
 *
 * @property LevelFile[] $levelFiles
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'guid'], 'required'],
            [['name', 'guid'], 'string', 'max' => 255],
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
            'guid' => 'Guid',
        ];
    }

    /**
     * Gets query for [[LevelFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevelFiles()
    {
        return $this->hasMany(LevelFile::className(), ['file_id' => 'id']);
    }

    public static function getUploadsFolder()
    {
        return Yii::getAlias('@webroot/uploads');
    }

    public function getFilePath()
    {
        return self::getUploadsFolder().'/'.$this->guid;
    }

    public function getContent()
    {
        $filePath = $this->getFilePath();
        if ($this->_isFileExists()) {
            return file_get_contents($filePath);
        }
        throw new \Exception('Файл не найден в файловой системе');
    }

    public function download()
    {
        return Yii::$app->getResponse()->sendFile($this->getFilePath(), $this->name);
    }

    private function _isFileExists()
    {
        $filePath = $this->getFilePath();
        return file_exists($filePath);
    }

    public function afterDelete()
    {
        $filePath = $this->getFilePath();
        if ($this->_isFileExists()) unlink($filePath);
    }
}
