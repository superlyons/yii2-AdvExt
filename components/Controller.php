<?php
namespace superlyons\yii2advext\components;

use Yii;
use yii\web\Controller as WebController;

class Controller extends WebController
{
	public $customControllerViewPath = "@superlyons/yii2advext";

	private $_viewPath;

	public function getViewPath()
    {
    	if($this->customControllerViewPath === false ){
	        parent::getViewPath();
        }else{
        	if ($this->_viewPath === null) {
	            $this->_viewPath = $path = Yii::getAlias($this->customControllerViewPath) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->id; 
	        }
	        return $this->_viewPath;
        }
    }
}