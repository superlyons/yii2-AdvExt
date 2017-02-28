<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel superlyons\rbacadmin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yii2_adv_ext', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('yii2_adv_ext', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 0 ? Yii::t('yii2_adv_ext', 'Locked') : Yii::t('yii2_adv_ext', 'Activate');
                },
                'filter' => [
                    0 => Yii::t('yii2_adv_ext', 'Locked'),
                    10 => Yii::t('yii2_adv_ext', 'Activate'),
                ]
            ],
            'created_at:RelativeTime',
            [
                'attribute' => "updated_at",
                'format' => ['date', 'php:Y-m-d H:m:s'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
            ]
        ]
    ]); ?>
    </div>
</div>
