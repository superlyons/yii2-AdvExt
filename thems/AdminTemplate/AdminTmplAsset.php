<?php

namespace superlyons\yii2advext\thems\AdminTemplate;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AdminTmplAsset extends AssetBundle
{
    public $sourcePath = "@vendor/superlyons/admin-template/build/1";
    public $css = [
        'css/admintmpl.css',
        'css/skins/_admintmpl-all-skins.css',
    ];
    public $js = [
        'js/admintmpl.js',
        'function.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
