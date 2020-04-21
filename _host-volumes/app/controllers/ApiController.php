<?php

namespace app\controllers;

use app\models\Appartment;
use app\models\AppartmentCoordinate;
use app\models\Build;
use app\models\City;
use app\models\Level;
use app\models\LevelFile;
use app\models\Porch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ApiController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetCities()
    {
        $cities = City::find()->all();
        return $this->asJson([
            'ok' => true,
            'data' => $cities,
        ]);
    }

    public function actionGetBuilds()
    {
        try {
            $cityId = Yii::$app->request->get('cityId');
            $city = City::findOne(['id' => $cityId]);
            if ($city) {
                $builds = $city->getBuilds()->all();
                return $this->asJson([
                    'ok' => true,
                    'data' => $builds,
                ]);
            }
            throw new \Exception('Город не найден');
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

    public function actionGetPorches()
    {
        try {
            $buildId = Yii::$app->request->get('buildId');
            $build = Build::findOne(['id' => $buildId]);
            if ($build) {
                $porches = $build->getPorches()->all();
                $porches = ArrayHelper::toArray($porches, [
                    Porch::class => [
                        'id',
                        'name',
                        'build_id',
                        'levels' => function (Porch $model) {
                            return $model->getLevels()->all();
                        },
                    ],
                ]);
                return $this->asJson([
                    'ok' => true,
                    'data' => [
                        'level_max' => $build->getLevelMax(),
                        'porches' => $porches,
                    ],
                ]);
            }
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

    public function actionGetLevels()
    {
        try {
            $porchId = Yii::$app->request->get('porchId');
            $porch = Porch::findOne(['id' => $porchId]);
            if ($porch) {
                $levels = $porch->getLevels()->all();
                return $this->asJson([
                    'ok' => true,
                    'data' => $levels,
                ]);
            }
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

    public function actionGetAppartments()
    {
        try {
            $levelId = Yii::$app->request->get('levelId');
            $apparments = Appartment::findOne(['level_id' => $levelId]) ?? [];
            $apparments = ArrayHelper::toArray($apparments, [
                Appartment::class => [
                    'id',
                    'level_id',
                    'num',
                    'coordinates' => function (Appartment $model) {
                        return $model->getAppartmentCoordinates()->all();
                    },
                ],
            ]);
            return $this->asJson([
                'ok' => true,
                'data' => $apparments,
            ]);
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

    public function actionGetLevelFiles()
    {
        try {
            $levelId = Yii::$app->request->get('levelId');
            $levelFiles = Level::findOne(['id' => $levelId])->getLevelFilesAsArray();
            return $this->asJson([
                'ok' => true,
                'data' => $levelFiles,
            ]);
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

    public function actionUploadLevelFile()
    {
        try {
            $levelId = Yii::$app->request->get('levelId');
            $level = Level::findOne(['id' => $levelId]);
            if (!$level) throw new \Exception('Помещение не найдено');
            $porch = porch::findOne(['id' => $level->porch->id]);
            $file = $level->uploadFile(UploadedFile::getInstanceByName('file'));
            return $this->asJson([
                'ok' => true,
                'data' => [
                    'porch' => $porch,
                    'level' => $level,
                    'file' => $file,
                ],
            ]);
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

    public function actionGetLevelFileDownload()
    {
        try {
            $levelId = Yii::$app->request->get('levelId');
            $fileName = Yii::$app->request->get('fileName');
            $level = Level::findOne(['id' => $levelId]);
            if (!$level) throw new \Exception('Этаж не найден');
            $file = $level->getFileByName($fileName);
            $file->download();
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

    public function actionDeleteLevelFile()
    {
        try {
            $levelFileId = Yii::$app->request->get('levelFileId');
            $levelFile = LevelFile::findOne(['id' => $levelFileId]);
            if (!$levelFile) throw new \Exception('Помещение не найдено');
            $deletedRows = $levelFile->delete();
            return $this->asJson([
                'ok' => true,
                'data' => [
                    'deletedRows' => $deletedRows,
                ],
            ]);
        } catch (\Throwable $th) {
            return $this->asJson([
                'ok' => false,
                'msg' => $th->getMessage(),
            ]);
        }
    }

}
