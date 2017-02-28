<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')?>

    <?= $form->field($model, 'email')?>

    <?= $form->field($model, 'status')->dropDownList([10=>Yii::t('yii2_adv_ext', 'Activate') , 0 => Yii::t('yii2_adv_ext', 'Locked') ]) ?>

<?
$options=[];
if($model->getScenario() == "admin-update"){
	echo $form->field($model, 'changePass')->checkbox();
	$options=[
		'options'=>['class'=>'form-group hidden'],
		];
	$checkbox = Html::getInputId($model,'changePass');
	$js = <<<JS
		$("#{$checkbox}").on('click',function(){
			$(".field-userform-password").toggleClass("hidden");
			$(".field-userform-retype_password").toggleClass("hidden");
		});
		if( $("#{$checkbox}").is(':checked') ){
			$(".field-userform-password").toggleClass("hidden");
			$(".field-userform-retype_password").toggleClass("hidden");
		}
JS;
	$this->registerJs($js);
}
?>
    <?= $form->field($model, 'password', $options)->passwordInput()?>

    <?= $form->field($model, 'retype_password', $options)->passwordInput()?>

   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yii2_adv_ext', 'Create') : Yii::t('yii', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
