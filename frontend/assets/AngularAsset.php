<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower';
//    public $css = [
//        'angular/angular-csp.css',
//        'angular-motion/dist/angular-motion.min.css',
//        'angularjs-toaster/toaster.css',
//    ];
    public $js = [
        'angular/angular.js',
        'angular-route/angular-route.js',
        'angular-resource/angular-resource.js',
        'angular-strap/dist/angular-strap.js',
//        'angular-sanitize/angular-sanitize.js',
//        'angular-animate/angular-animate.js',
//        'angularjs-toaster/toaster.js',
//        'angular-strap/dist/angular-strap.tpl.min.js',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
//        'position' => View::POS_END,
    ];
}
