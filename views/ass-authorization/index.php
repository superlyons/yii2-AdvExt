<?
use yii\helpers\Html;
use yii\grid\GridView;
?>
<h1><?=Yii::t('yii2_adv_ext', 'AssAuthorizationController List')?></h1>

<?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        	'name:text:'.Yii::t('yii2_adv_ext','Name'),
        	'type:text:'.Yii::t('yii2_adv_ext','Type'),
        	'rule:text:'.Yii::t('yii2_adv_ext','Rule'),
        	[
			    'class' => 'yii\grid\ActionColumn',
			    'template' => '{view}'
			],
        ]
    ]);
?>