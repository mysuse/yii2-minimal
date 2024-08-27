<?php

namespace app\modules\restapi;

/**
 * restapi module definition class
 */
class Restapi extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\restapi\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::$app->user->enableSession = false;
    }
}
