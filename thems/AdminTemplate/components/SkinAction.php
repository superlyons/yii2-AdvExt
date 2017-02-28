<?php

namespace superlyons\yii2advext\thems\AdminTemplate\components;

use Yii;
use yii\base\Action;

class SkinAction extends Action
{
	public function run(){
		$skin = Yii::$app->getRequest()->get('skin');
		Yii::$app->session->set("skin", $skin);
        //file_put_contents(Yii::getAlias('@webroot/skin.txt'), $skin);
	}
}