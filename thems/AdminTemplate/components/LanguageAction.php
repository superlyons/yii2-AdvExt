<?php

namespace superlyons\yii2advext\thems\AdminTemplate\components;

use Yii;
use yii\base\Action;
use yii\helpers\Url;

class LanguageAction extends Action
{
	public function run(){
		$lang = Yii::$app->getRequest()->get('lang');
                //file_put_contents(Yii::getAlias('@webroot/language.txt'), $lang);
                Yii::$app->session->set("lang", $lang);
                $url = Yii::$app->getRequest()->get('url');
                if(empty($url)){
                	$url = Url::home();
                }
                $this->controller->redirect($url);
	}
}