<?php
use frontend\assets\AppAsset;

/* @var $this \yii\web\View */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="app">
<head>

    <base href="/"/>

<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Angular Yii Application</title>
<?php $this->head() ?>

<script>paceOptions = {ajax: {trackMethods: ['GET', 'POST']}};</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/red/pace-theme-minimal.css" rel="stylesheet" />
</head>
<body ng-controller="MainController">
<?php $this->beginBody() ?>
<div class="wrap">
    <nav class="navbar-inverse navbar-fixed-top navbar" role="navigation" bs-navbar>
        <div class="container">
            <div class="navbar-header">
                <button ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed" type="button" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span></button>
                <a class="navbar-brand" href="/"><?= Yii::$app->name ?></a>
            </div>
            <div ng-class="!navCollapsed && 'in'" ng-click="navCollapsed=true" class="collapse navbar-collapse" >
                <ul class="navbar-nav navbar-right nav">
                    <li data-match-route="/$">
                        <a href="/">Главная</a>
                    </li>
                    <li data-match-route="/about">
                        <a href="/about">О нас</a>
                    </li>
                    <li data-match-route="/contact">
                        <a href="/contact">Связь</a>
                    </li>
                    <li data-match-route="/dashboard" ng-show="loggedIn()" class="ng-hide">
                        <a href="/dashboard">Секретная страница</a>
                    </li>
                    <li data-match-route="/posts" ng-show="loggedIn()" class="ng-hide">
                        <a href="/posts">Статьи</a>
                    </li>
                    <li ng-class="{active:isActive('/logout')}" ng-show="loggedIn()" ng-click="logout()"  class="ng-hide">
                        <a href="">Выход</a>
                    </li>
                    <li data-match-route="/login" ng-hide="loggedIn()">
                        <a href="/login">Вход</a>
                    </li>
                    <li data-match-route="/signup" ng-hide="loggedIn()">
                        <a href="/signup">Регистрация</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div ng-view>
        </div>
    </div>
    <toaster-container toaster-options="{'position-class': 'toast-top-right'}"></toaster-container>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <a href="http://blog.neattutorials.com">Neat Tutorials</a> <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?> <?= Yii::getVersion() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>