<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\base\Exception;

class AdminlteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $css = [
            'css/AdminLTE.min.css',
    ];
    public $js = [
            'js/adminlte.min.js',
           
    ];
    public $depends = [
            'rmrevin\yii\fontawesome\AssetBundle',
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
            'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = '_all-skins';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }
            
            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }
        
        parent::init();
    }

}