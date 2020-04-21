<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "level".
 *
 * @property int $id
 * @property string $name
 * @property int $porch_id
 *
 * @property Porch $porch
 * @property LevelFile[] $levelFiles
 */
class Level extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'porch_id'], 'required'],
            [['porch_id'], 'default', 'value' => null],
            [['porch_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['porch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Porch::className(), 'targetAttribute' => ['porch_id' => 'id']],
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
            'porch_id' => 'Porch ID',
        ];
    }

    /**
     * Gets query for [[Porch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPorch()
    {
        return $this->hasOne(Porch::className(), ['id' => 'porch_id']);
    }

    /**
     * Gets query for [[LevelFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevelFiles()
    {
        return $this->hasMany(LevelFile::className(), ['level_id' => 'id']);
    }

   public function getLevelFilesAsArray() 
   { 
       $levelFiles = $this->getLevelFiles()->all(); 
       return ArrayHelper::toArray($levelFiles, [ 
           LevelFile::class => [ 
               'id', 
               'file' => function ($model) { return $model->file; }, 
           ], 
       ]); 
   } 
 
   public function getFileByName($fileName) 
   { 
       $file = File::find()->alias('f') 
           ->innerJoin(LevelFile::tableName().' lf', 'lf.file_id = f.id') 
           ->innerJoin(Level::tableName().' l', 'l.id = lf.level_id') 
           ->where([ 
               'l.id' => $this->id, 
               'f.name' => $fileName, 
           ])
           ->one(); 
       if (!$file) throw new \Exception('Файл не найден'); 
       return $file; 
   } 
 
   public function uploadFile(UploadedFile $uploadedFile) 
   { 
       $t = Yii::$app->db->beginTransaction(); 
       try { 
           $upload = new UploadForm(); 
           $upload->imageFile = $uploadedFile; 
           $file = $upload->upload(); 
           $levelFile = new LevelFile(); 
           $levelFile->level_id = $this->id; 
           $levelFile->file_id = $file->id; 
           $levelFile->save(); 
           if ($levelFile->hasErrors()) { 
               unlink($levelFile); 
               throw new \Exception('Не удалось сохранить файл'); 
           } 
           $t->commit(); 
           return $file; 
       } catch (\Throwable $th) { 
           $t->rollBack(); 
           throw $th; 
       } 
   } 
}
