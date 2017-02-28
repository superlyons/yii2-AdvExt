<?php
use yii\helpers\Url;

$textColor = "text-danger";
$icon = 'glyphicon glyphicon-alert';
$title = "Error !!!";

if($msg_type=="success"){
  $textColor = "text-success";
  $icon = 'glyphicon glyphicon-ok';
  $title = "Success !!!";
}


?>
<div class="login-box">
	<div class="login-logo">
		<a href="<?=Url::home()?>"><b>Admin</b>TMPL</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		<p class="<?=$textColor?>"><span class="<?=$icon?> lead"></span><b> &nbsp; <?=Yii::t("yii2_adv_ext",$title)?></b></p>
		<p class="<?=$textColor?>"><?=$msg?></p>
    <br>
		<br>
		<div style="text-align:right;">
			<a href="<?=Url::toRoute(['/member/login'])?>"><?=Yii::t("yii2_adv_ext","I already have a membership")?></a><br>
      <a href="<?=Url::toRoute(['/member/signup'])?>"><?=Yii::t("yii2_adv_ext","Register a new membership")?></a>
		</div>
	</div>
	<!-- /.login-box-body -->
</div>
<!-- /.login-box -->