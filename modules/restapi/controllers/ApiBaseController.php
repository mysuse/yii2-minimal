<?php
namespace modules\restapi\controllers;

use yii\filters\Cors;
use yii\rest\Controller;

class ApiBaseController extends Controller
{
    /**
     * @var bool See details {@link \yii\web\Controller::$enableCsrfValidation}.
     */
    public $enableCsrfValidation = false;
    
    public function behaviors(){
        
        $behaviors = parent::behaviors();
        
        // unset / hapus authenticator
        unset($behaviors['authenticator']);
        
        
        //tambahkan HttpBearerAuth untuk autentikasi berbasis token
        
        $behaviors['authenticator'] = [
            'class' => JwtBearerAuth::className(),
            'except'=>['options']
        ];
        
        return $behaviors;
        
    }
    
    public function beforeAction( $action ) {
        if ( parent::beforeAction ( $action ) ) {
            
            //change layout for error action after
            //checking for the error action name
            //so that the layout is set for errors only
            if ( $action->id == 'error' ) {
                $this->layout = 'error-page';
            }
            return true;
        }
    }
    
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction' ,
            ]
        ];
    }
}
