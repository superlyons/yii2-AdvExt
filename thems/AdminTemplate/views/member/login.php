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
		<p class="login-box-msg"><b><?=Yii::t("yii2_adv_ext","Sign in to start your session")?></b></p>

		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
			<?
			echo $form->field($model,'email',[
					'enableLabel' => false,
					'options' => [
						'class'	=> 'has-feedback',
					],
					'inputTemplate' => '{input}'.Html::tag('span','',['class'=>'glyphicon glyphicon-envelope form-control-feedback']),
				])->textInput(['placeholder' => Yii::t("yii2_adv_ext","E-Mail")]);

			?>

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
			$tmp = 	'<div class="input-group">
						{input}
						<span class="input-group-addon-img" id="basic-addon3">{image}</span>
					</div>';

			echo $form->field($model, 'verifyCode',[
					'enableLabel' => false,
				])->widget(Captcha::className(), [
							'captchaAction' => 'member/captcha',
							'imageOptions' => ['class'=>"right"],
                    		'template' => $tmp,
                    		'options' => ['class' => 'form-control', 'placeholder'=>Yii::t("yii2_adv_ext","Verify Code")],
               			]);
			?>
			<div class="row">
				<div class="col-xs-8">
					<?=$form->field($model, 'rememberMe')->checkbox()?>
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block"><?=Yii::t("yii2_adv_ext","Sign In")?></button>
				</div>
				<!-- /.col -->
			</div>
		<?php ActiveForm::end(); ?>

		<a href="<?=Url::toRoute(['/member/request-password-reset'])?>"><?=Yii::t("yii2_adv_ext","I forgot my password")?></a><br>
		<a href="<?=Url::toRoute(['/member/signup'])?>"><?=Yii::t("yii2_adv_ext","Register a new membership")?></a>

	</div>
	<!-- /.login-box-body -->
	
</div>
<!-- /.login-box -->