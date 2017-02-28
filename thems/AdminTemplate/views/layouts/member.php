<?php
use superlyons\yii2advext\thems\AdminTemplate\AdminTmplAsset;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

AdminTmplAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <title><?= "Login"?></title>
    <?php $this->head() ?>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="login-page">
<?php $this->beginBody() ?>
<div style="position:absolute; top:0px; right:0px">
	<div class="btn-group btn-group-sm" role="group" aria-label="...">
	  <a type="button" href="<?=Url::toRoute(["/site/language",'lang'=>'en', 'url'=>Yii::$app->getRequest()->getUrl()])?>" class="btn btn-default">en</a>
	  <a type="button" href="<?=Url::toRoute(["/site/language",'lang'=>'zh-CN', 'url'=>Yii::$app->getRequest()->getUrl()])?>" class="btn btn-default">zh-CN</a>
	</div>
</div>

<?= $content; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>