<?php

namespace app\models;

use Faker\Provider\Uuid;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, obj, mtl, jpeg', 'checkExtensionByMimeType' => false],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $t = Yii::$app->db->beginTransaction();
            $file = new File();
            $file->name = $this->imageFile->baseName . '.'. $this->imageFile->extension;
            $file->guid = Uuid::uuid();
            $file->save();
            $uploadDir = Yii::getAlias('@webroot/uploads');
            $this->imageFile->saveAs($uploadDir .'/'. $file->guid);
            if ($file->hasErrors() || $this->imageFile->getHasError()) {
                $t->rollBack();
                throw new \Exception('Не удалось загрузить файл');
            }
            $t->commit();
            return $file;
        }
        throw new \Exception('Файл не прошел валидацию');
    }
}