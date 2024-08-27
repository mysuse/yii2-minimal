<?php
namespace app\modules\restapi\controllers;

use yii\rest\Controller;

class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return [
            'api'=>'Minimal Yii2  Api Services',
            'version'=>'2.0',
            'date'=>date('d/m/Y H:i:s'),
            'online'=>true,
        ];
    }
}

