<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

?>
<div class="login-box">
	<div class="login-logo">
		<a href="<?=Url::home()?>"><b>Admin</b>TMPL</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		<p class="login-box-msg">
			<b><?=Yii::t("yii2_adv_ext","Reset your password")?></b><br>
		</p>

		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
			<div class="form-group">
				<p class="form-control-static lead text-success"><?=$model->getEmail();?></p>
			</div>
			<?
			echo $form->field($model,'password',[
					'enableLabel' => false,
					'options' => [
						'class'	=> 'has-feedback',
					],
					'inputTemplate' => '{input}'.Html::tag('span','',['class'=>'glyphicon glyphicon-lock form-control-feedback']),
				])->passwordInput(['placeholder' => Yii::t("yii2_adv_ext","Password")]);

			?>

			<?
			echo $form->field($model,'retype_password',[
					'enableLabel' => false,
					'options' => [
						'class'	=> 'has-feedback',
					],
					'inputTemplate' => '{input}'.Html::tag('span','',['class'=>'glyphicon glyphicon-log-in form-control-feedback']),
				])->passwordInput(['placeholder' => Yii::t("yii2_adv_ext","Retype Password")]);

			?>
			<div class="row">
				<div class="col-xs-8">

				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block"><?=Yii::t("yii2_adv_ext","Reset")?></button>
				</div>
				<!-- /.col -->
			</div>
		<?php ActiveForm::end(); ?>
		

	</div>
	<!-- /.login-box-body -->
</div>
<!-- /.login-box -->