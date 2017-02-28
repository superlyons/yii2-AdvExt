<?php

namespace superlyons\yii2advext\thems\AdminTemplate\components;

use yii\helpers\Html;
use Yii;

use yii\di\Instance;
use yii\web\User;

class LayoutHelper extends \yii\base\Object
{
    /*
    $top_navs = [
        'id' => '1234567890',
        'label' => 'label name',
        'url' => 'route/to' | [ ... ],
        'data' => '{
                "html-li-options" : {
                    "id" : '1234567890',
                    'class' : ['classname','...', ...]
                },
                "html-a-options" : { ... }
            }';
    ]
    */
    public static function buildNavbar(&$top_navs, $active, $max=7){
        $result='';
        $top_nav_i=1;
        foreach($top_navs as $nav){
            $data = json_decode($top_navs['data'],true);
            if(!empty($nav['id'])){
                if(empty($data['html-li-options']['id'])) $data['html-li-options']['id']="nav_".$nav['id'];
                if($active == $nav['id']) {
                    $data['html-li-options']['class'][]='active';
                }
            }

            if( $top_nav_i == $max && count($top_navs) > $max ){
                $result.= Html::beginTag('li',['class'=>'dropdown']);
                $options['class'][]="dropdown-toggle";
                $options['data']['toggle']="dropdown";
                foreach(array_slice($top_navs,$max-1) as $find){
                    if( $find['id'] == $active ) {
                        $options['class'][]="active";
                        break;
                    }
                }
                $result.= Html::a( Yii::t("yii2_adv_ext","More...") . Html::Tag("span",'',['class'=>"caret"]) , "javascript::Void(0);" , $options);
                $result.= Html::beginTag("ul",['class'=>"dropdown-menu"]);
            }

            if(empty($data['html-li-options'])) $data['html-li-options']=[];

            $a = Html::a(Yii::t("yii2_adv_ext",$nav['label']), $nav['url'], $data['html-a-options']);
            $result.= Html::tag('li', $a , $data['html-li-options']);

            if( count($top_navs) > $max && $top_nav_i == count($top_navs) ){
                $result.= Html::endTag("ul");
                $result.= Html::endTag("li");
            }

            $top_nav_i++;
        };
        return $result;
    }
    /*
    $sidebars = [
        [
            'id' => '1234567890',
            'label' => 'label-name',
            'url' => 'route/to',
            'data' => '{
                    "icon":"glyphicon glyphicon-th",
                }',
            'items' => [ ...... ],
        ],
        [ ...... ],
    ]
    */
    public static function bulidSidebar(&$sidebars,$active,$level=1){
        $result='';
        foreach($sidebars as $sidebar){
            $tree_item = "";
            $li_options = [];
            $ul_options = [];
            $icon = "";

            $is_treeView = isset($sidebar['items']) ? true : false;
            
            $icon = Html::tag("i",'',['class'=>"glyphicon glyphicon-minus"]);
            if($level==1){
                $data = json_decode($sidebar['data'],true);
                if(!empty($data['icon'])){
                    $icon = Html::tag("i",'',[ 'class'=>$data['icon'] ]);
                }
            }
            
            $label = Html::tag('span',Yii::t("yii2_adv_ext",$sidebar['label']));

            if($sidebar['id'] == $active){
                $li_options['class'][]="active";    
            }

            if($is_treeView){
                if($level==1){
                    $li_options['class'][]="treeview";
                }
                $ul_options['class'][]="treeview-menu";
                
                $tree_item_icon = Html::tag("i",'',['class'=>"glyphicon glyphicon-menu-left pull-right"]);
                $tree_item = Html::tag("span", $tree_item_icon , ['class'=>"pull-right-container"]);
            }
            

            $result.= Html::beginTag("li",$li_options);
            $result.= Html::a($icon.$label.$tree_item, $sidebar['url']);

            if($is_treeView){
                $result.= Html::beginTag("ul", $ul_options);
                $result.= static::bulidSidebar($sidebar['items'], $active, 2);
                $result.= Html::endTag("ul");
            }

            $result.= Html::endTag("li");
        }
        return $result;
    }

    public static function buildToggleTopNavs($selector){
        return '<li><a class="navbarnav-toggle" data-toggle="collapse" data-target="'.$selector.'"></a></li>';
    }

    /*
    @app/web/index.php:
    ---------------------------
    $application = new yii\web\Application($config);
    $application->on($application::EVENT_BEFORE_REQUEST, ['superlyons\yii2advext\thems\AdminTemplate\components\LayoutHelper','init_config']);
    $application->run();
    */
    public static function init_config($event){
        //if(!is_file('./language.txt')) file_put_contents('./language.txt','');
        //if(!is_file('./skin.txt')) file_put_contents('./skin.txt','');
        //$file = file_get_contents('./language.txt');
        //$skin = file_get_contents('./skin.txt');
        $lang = Yii::$app->session->get("lang") ? : "zh-CN";
        $skin = Yii::$app->session->get("skin") ? : "";
        $event->sender->language = $lang;
        $event->sender->params['skin'] = $skin;
    }

    public static function GuestDenyAccess($actionevent){
        $route = '/'.Yii::$app->controller->getRoute();
        if( stripos($route, '/member') === 0 || stripos($route,'/site/language') === 0 ){
            return;
        }
        $user = Instance::ensure('user', User::className());
        if ( $user->getIsGuest() ) {
            $actionevent->isValid = false;
            $user->loginRequired();
        }
    }
}
