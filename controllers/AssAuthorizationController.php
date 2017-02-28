<?php
namespace superlyons\yii2advext\controllers;

use Yii;
use yii\db\Query;
use superlyons\yii2advext\components\AssignmentAuthorizationHelper;
use superlyons\yii2advext\components\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;
/**
 * Site controller
 *
 */
class AssAuthorizationController extends Controller
{
	
    public function actionIndex()
    {
    	$roots = AssignmentAuthorizationHelper::getAssignments();
    	$dataProvider = new ArrayDataProvider([
    			'key' => 'name',
    			'allModels' => $roots,
    		]);
    	return $this->render('index',['dataProvider'=>$dataProvider]);
    }

    public function actionView($id='')
    {
    	$tree = AssignmentAuthorizationHelper::getAssignmentsRelation($id);
    	/*
    	echo "<pre>";
    	echo VarDumper::export($tree);
    	echo "</pre>";
    	*/
    	return $this->render('view',['tree'=>$tree]);

    }
}