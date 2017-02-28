<?php

namespace superlyons\yii2advext\thems\AdminTemplate\components;

use Yii;
use yii\caching\Cache;
use yii\db\Connection;
use yii\db\Query;
use yii\db\Expression;
use yii\base\InvalidCallException;
use yii\base\InvalidParamException;
use yii\di\Instance;
use yii\base\Component;
use superlyons\mptt\components\MpttRbacRoutesHelper;
use superlyons\mptt\models\MpttNode;
use yii\helpers\VarDumper;

class MpttNavigationData extends Component implements NavigationDataInterface
{
	const ROUTE_BY_PATHINFO = 1;
	const ROUTE_BY_CONTROLLER = 2;

	public $rbac = false; //参考RBAC返回相关授权Route
	public $db = 'db'; 
	public $cache;
	public $routeType = self::ROUTE_BY_PATHINFO;

	public $userId = null; //rbac=true时参考$userId返回相关授权Ruote
    public $defaultRoute = null; //getActiveNavIdsByRoute()没有匹配的Route时参考的默认Route
	public $roots = [];

	private $_navs = null;

	public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
        if( $this->rbac && empty($this->userId)){
        	$this->userId = Yii::$app->getUser()->getId();	
        }
    }

    protected function getMpttRbacRoutes(&$result){
    	$trees = MpttRbacRoutesHelper::getAssignedRouteNodes($this->userId, $this->roots);
    	//echo "<pre>".VarDumper::export($trees)."<pre>";
    	foreach($trees as $tree){
            //去掉查询的roots,只保留其子节点
    		if(isset($tree[0]['items'])){
    			$result =  array_merge($result, $tree[0]['items']);
    		}
    	}
    }
    /*
		$menus = mptt\MpttRbacRoutesHelper::getAssignedRouteNodes();
		$menus = [                                  //$trees
			'1234567890' => [                       //$tree
				[                                   //$tree[0]
					'id' => '1234567890'            //$tree[0]['id']
					'label' => 'name',              //$tree[0]['name']
					'url' => 'url',                 //$tree[0]['url']
					'items' =>[                     //$tree[0]['items']
						[                           //$tree[0]['items'][0]
							'id' => '0987654321',
							'label' => 'name',
							'url' => 'url',
							'items' = > [ ...items-1... ],
						],
						[
						...items-2...
						],
					],
				],
			],
		]
		$menus[1234567890][0]: Navs
		$menus[1234567890][0]['items'] : SubNavs | Menus

		$menus = _buildNavs(); 
		rbac=true则开始于SubNavs:
			$menus = [
				[
					'id' => '0987654321',
					'label' => 'name',
					'url' => 'url',
					'items' = > [ ...items-1... ],
				],
				[
				...items-2...
				],
			];
		rbac=false则开始于id的下一级
    */
    protected function _buildNavs(){
		if($this->_navs === null){
	    	$result = [];
	    	if ($this->rbac) {
	    		$this->getMpttRbacRoutes($result);
	    	}else{
	    		$columns = ["id","lft","rgt","parent","name","value","data","root","level"];
		        if($this->roots == null){
					$rootnodes = MpttNode::find()->select($columns)->where("[[lft]] = 1")->all();
				}else{
					//$this->roots = (array)$this->roots;
					$rootnodes = MpttNode::find()->select($columns)->where(['id'=>$this->roots])->all();
				}
		        foreach($rootnodes as $rootnode){
		            $parents = $rootnode['id'];
		            $nodes = MpttNode::find()->select($columns)->andWhere(['>=', 'lft', $rootnode['lft']])
		                ->andWhere(['<=', 'rgt', $rootnode['rgt']])->andWhere(['root'=>$rootnode['root']])->orderBy('lft')->asArray()->all();
		            $result = array_merge($result, MpttRbacRoutesHelper::convertToArray($nodes, null, $parents));

		        }
	    	}
	    	$this->_navs = $result;
	    }
    }

    public function getNavs(){
    	$this->_buildNavs();
	    return $this->_navs;
    }

    private $_topnavs = null;
    public function getTopNavs(){
    	if($this->_topnavs === null){
	    	$this->_buildNavs();
	    	$result=[];
	    	foreach($this->_navs as $nav){
	    		$result[]=[
	    			'id' => $nav['id'],
	    			'label' => $nav['label'],
	    			'data' => $nav['data'],
	    			'url' => $nav['url'],
	    		];
	    	};
	    	$this->_topnavs = $result;
	    }
    	return $this->_topnavs;
    }

    private $_sidebar = [];
    public function getSidebar($id){
    	if(!isset($this->_sidebar[$id])){
    		$this->_buildNavs();
    		$result = [];
    		foreach($this->_navs as $nav){
	    		if($nav['id'] == $id){
	    			$result = $nav['items'];
	    			break;
	    		}
	    	};
	  		$this->_sidebar[$id]=$result;
    	}
    	return $this->_sidebar[$id];
    }

    /*
	[ 0 => TopNav-id , 1 => Current-Active-Nav-id ]
    */
    public function getActiveNavIdsByRoute($route=null, &$items=null, $begin=true){
    	if( $begin ){
    		$this->_buildNavs();
    		$items = $this->_navs;	
    		if(empty($route) && $this->routeType == self::ROUTE_BY_PATHINFO){
    			$route = '/'.Yii::$app->getRequest()->getPathinfo();
    		} elseif(empty($route)){
    			$route = '/'.Yii::$app->controller->getRoute();
    		}
    		if($route=='/') $route = '';
    	} 
    	if(empty($items)) $items=[];
    	foreach($items as $nav){
    		if(is_array($nav['url'])) {
    			$url=$nav['url'][0];	
    		}else{
    			$url=$nav['url'];
    		}
    		$data = json_decode($nav['data'],true);
    		if(!empty($data) && !empty($data['route-filter'])){
    			$match = preg_match("#".$data['route-filter']."#i",$route) == 1 ? true : false;
    		}else{
    			$match = stripos($url, $route) === 0;
    		}

            //match Url
    		if($match){
    			if($begin){ //Top-Nav match
                    //search match Sub-Nav|Final-Nav
    				$br = $this->getActiveNavIdsByRoute($route, $nav['items'], false);
    				if($br !== false){
    					return [ $nav['id'], $br ];
    				}
    				return [ $nav['id'], $nav['id'] ];
    			}else{
    				return $nav['id'];
    			}
    		}

            //no-match Url search Sub-Nav
    		if(isset($nav['items'])){
    			$r = $this->getActiveNavIdsByRoute($route, $nav['items'], false);	
    			if( $r !== false){
    				return $begin ? [ $nav['id'], $r ] : $r;
    			}
    		} 
    	}
        //current PATHINFO or Route not match, try search defaultRoute from Begin=true
    	if($begin && $this->defaultRoute){
    		$old = $this->defaultRoute;
    		$this->defaultRoute = null;
    		$result = $this->getActiveNavIdsByRoute($old);
    		$this->defaultRoute = $old;
    		return $result;
    	} 
    	return false;
    }


    public function getBrand($mini=false){
        return $mini ? "A-TMPL" : "Admin Template";
    }
    public function getSidebarTitle($hasTopNavs=false){
        return $hasTopNavs ? Yii::t("yii2_adv_ext","SUB MENUS") : Yii::t("yii2_adv_ext","MAIN NAVIGATION");
    }

}
