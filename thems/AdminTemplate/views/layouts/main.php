<?php
use superlyons\yii2advext\thems\AdminTemplate\AdminTmplAsset;
use superlyons\yii2advext\thems\AdminTemplate\components\LayoutHelper;
use superlyons\yii2advext\thems\AdminTemplate\components\MpttNavigationData;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\VarDumper;

// test TopNav 
function getTopNavs($id=[],$count=7){
	$navs=[];
	for($i=1; $i<=$count; $i++){
		$navs["$i"]=[
			'label' => 'NavLabel-'.$i,
			'url' => '#',
			'id' => $i,
		];
	}
	return $navs;
}

$navObj = new MpttNavigationData([
		'rbac'=>false,
		'userId'=>1,
		'roots' =>['4644657367311455767'],
]);
$active = $navObj->getActiveNavIdsByRoute();

$navQuick = new MpttNavigationData([
		'rbac'=>false,
		'userId'=>1,
		'roots' =>['4644667162575770607'],
]);
$activeQuick = $navQuick->getActiveNavIdsByRoute();

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
    <?
    	$title = empty($this->params['title']) ? "" : ' | '.$this->params['title'];
    ?>
    <title><?= Html::encode($navObj->getBrand().$title)?></title>
    <?php $this->head() ?>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<? 
//LayoutHelper::init_config() : $event->sender->params['skin'] = $skin;
?>
<body class="sidebar-mini <?=Yii::$app->params['skin']?>">
<?php $this->beginBody() ?>

<div class="wrapper">

	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header left">
				<a class="navbar-brand mini" href="<?=Url::home();?>">
					<?=$navObj->getBrand(true)?>
				</a>
				<a class="navbar-brand full" href="<?=Url::home();?>">
					<?=$navObj->getBrand()?>
				</a>
				<a class="sidebar-toggle" data-toggle="offcanvas"></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<?
					//$top_navs = getTopNavs([],13);
					$top_navs = $navObj->getTopNavs();
					echo LayoutHelper::buildNavbar($top_navs, $active[0]);
					?>
				</ul>
			</div>

			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">	
					<?=LayoutHelper::buildToggleTopNavs("#bs-example-navbar-collapse-1")?>
					<li class="dropdown messages-menu">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-tint"></i>
							<span class="label label-success">2</span>
						</a>
						<ul class="dropdown-menu pull-right" role="menu">
							<li><a id="skin-default" data-route="<?=Url::toRoute(["/site/skin", "skin"=>""])?>" href="#">Default-Skin</a></li>
							<li><a id="skin-inverse" data-route="<?=Url::toRoute(["/site/skin", "skin"=>"skin-inverse"])?>" href="#">Inverse-Skin</a></li>
						</ul>
					</li>
					<li class="dropdown messages-menu">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-globe"></i>
						</a>
						<ul class="dropdown-menu pull-right" role="menu">
							<li><a href="<?=Url::toRoute(["/site/language",'lang'=>'zh-CN', 'url'=>Yii::$app->getRequest()->getUrl()])?>">zh-CN</a></li>
							<li><a href="<?=Url::toRoute(["/site/language",'lang'=>'en', 'url'=>Yii::$app->getRequest()->getUrl()])?>">en</a></li>
						</ul>
					</li>
					<li class="dropdown messages-menu">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-user"></i>
						</a>
						<ul class="dropdown-menu pull-right" role="menu">
							<li><a href="<?=Url::toRoute("/member/logout")?>" data-method="post"><?=Yii::t("yii2_adv_ext","Logout")?> (<?=Yii::$app->user->identity->username?>)</a></li>
							<li class="divider"></li>
							<li><a href="<?=Url::toRoute("/admin/permission")?>"><?=Yii::t("yii2_adv_ext","Permissions")?></a></li>
							<li><a href="<?=Url::toRoute("/admin/role")?>"><?=Yii::t("yii2_adv_ext","Roles")?></a></li>
							<li><a href="<?=Url::toRoute("/admin/assignment")?>"><?=Yii::t("yii2_adv_ext","Assignments")?></a></li>
							<li class="divider"></li>
							<li><a href="<?=Url::toRoute("/mptt/mptt-data/index")?>"><?=Yii::t("yii2_adv_ext","Mptt Manage")?></a></li>
							<li><a href="<?=Url::toRoute("/mptt/mptt-data/tree")?>"><?=Yii::t("yii2_adv_ext","Mptt Trees")?></a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>

<?
$sidebars = $navObj->getNavs();
//$sidebars = $navObj->getSidebar($active[0]);

$quick_sidebars = $navQuick->getSidebar($activeQuick[0]);
?>
	<aside class="main-sidebar">
		<section class="sidebar">
			<ul class="sidebar-menu">
				<? if (!empty($quick_sidebars)){ ?>
				<li class="header"><?=Yii::t("yii2_adv_ext","QUICK NAVIGATION")?></li>
				<?
					echo LayoutHelper::bulidSidebar($quick_sidebars,$activeQuick[1]);
				}
				?>
				<?if (!empty($sidebars)){?>
				<li class="header"><?=$navObj->getSidebarTitle()?></li>
				<?
					echo LayoutHelper::bulidSidebar($sidebars,$active[1]);
				}
				?>
				<li class="header"><?=Yii::t("yii2_adv_ext","QUICK LABELS")?></li>
				<li><a href="<?=Url::toRoute("/user/index")?>"><i class="glyphicon glyphicon-minus text-primary"></i> <span><?=Yii::t("yii2_adv_ext","User Manage")?></span></a></li>
				<li><a href="<?=Url::toRoute("/admin/default/index")?>"><i class="glyphicon glyphicon-minus text-danger"></i> <span><?=Yii::t("yii2_adv_ext","Authorization Manage")?></span></a></li>
				<li><a href="<?=Url::toRoute("/mptt/default/index")?>"><i class="glyphicon glyphicon-minus text-success"></i> <span><?=Yii::t("yii2_adv_ext","Mptt Manage")?></span></a></li>
			</ul>
		</section>
	</aside>

	<div class="content-wrapper">
		<section class="content-header">
			<?
			echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        	]) ?>
		</section>
		<div class="container-fluid">
			<?
			//$arr = $navObj->getNavs();
			//echo "<pre>".VarDumper::export($arr)."<pre>";
			//echo Yii::$app->controller->getRoute()."<br>";
			//echo Yii::$app->request->getPathInfo();
			//var_dump($active);
			//var_dump($activeQuick);
			/*
			$data = '{"route-filter":"^/mptt/(mptt-data|mptt-data/create|mptt-data/update|mptt-data/view)$"}';
			$data = json_decode($data,true);
			var_dump($data);
			$route = '/'.Yii::$app->getRequest()->getPathinfo();
			echo $route."<br>";
			echo preg_match("#".$data['route-filter']."#i", $route);
			*/
			?>
			<? //var_dump(Yii::$app->user->identity)?>
			<?= Alert::widget() ?>
			<?= $content; ?>
		</div>
	</div>


	<footer class="main-footer">
		<div class="pull-right hidden-xs">
		  <b>Version</b> 1.0.0
		</div>
		<strong>Copyright &copy; 2013-2017 <a href="">Lyons</a>.</strong> All rights reserved.
	</footer>


</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>