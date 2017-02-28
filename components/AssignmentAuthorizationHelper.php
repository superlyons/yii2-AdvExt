<?php

namespace superlyons\yii2advext\components;

use Yii;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\db\QueryBuilder;


class AssignmentAuthorizationHelper{

	public static $itemTable = '{{%auth_item}}';
    public static $itemChildTable = '{{%auth_item_child}}';
    public static $assignmentTable = '{{%auth_assignment}}';
    public static $ruleTable = '{{%auth_rule}}';

    //返回已分配的授权关系数组
    public static function getAssignmentsRelation($itemnames=[]){
        $itemnames = (array)$itemnames;
        $roots=static::getAssignments($itemnames);
        static::buildChildrenList($childListAll);
        $result = [];
        foreach($roots as $root){
            if( !array_key_exists($root['name'], $childListAll['info']) ){
                $childListAll['info'][$root['name']] = [
                    'name' => $root['name'],
                    'type' => $root['type'],
                    'rule' => $root['rule'],
                ];
            }
            $result[$root['name']]=static::getItems($root['name'], $childListAll);
        }
        return $result;
    }
    
    //获得assignmentTable中 $itemnames指定的 或 所有 已分配的权限, 并去重
    public static function getAssignments($itemnames=[]){
        $itemnames = (array)$itemnames;
        //SELECT item.`name`,item.type,item.rule_name FROM auth_assignment ass
        //LEFT JOIN auth_item item ON ass.item_name = item.`name` WHERE ass.item_name LIKE '%post%' GROUP BY item.name,item.type,item.rule_name;
        $columns = ['name'=>'item.name', 'type'=>'item.type', 'rule'=>'item.rule_name'];
        $query = (new Query)->select($columns)->from(['ass' => static::$assignmentTable])
                            ->leftJoin( ['item' => static::$itemTable], 'ass.item_name = item.name')
                            ->groupBy('item.name, item.type, item.rule_name');
        foreach($itemnames as $name){
            $query->orWhere(['LIKE','ass.item_name', $name]);
        }
        $rows = $query->all();
        return $rows === false ? null : $rows;
    }

    /*
    返回授权继承关系的数组
    ['parents']中存储 授权的继承关系,  ['父授权名']=[ 子授权名, .... ];
    ['info']中存储 ['parents']中所有授权的详细信息,包括name,type,rule
    */
    public static function buildChildrenList(&$result)
    {
        $columns = ['pname'=>'p.name', 'ptype'=>'p.type', 'prule'=>'p.rule_name','cname'=>'c.name', 'ctype'=>'c.type', 'crule'=>'c.rule_name'];
        $query = (new Query)->select($columns)->from( ['items' => static::$itemChildTable] )
                            ->leftJoin( ['p'=>static::$itemTable], 'items.parent = p.name' )
                            ->leftJoin( ['c'=>static::$itemTable], 'items.child = c.name' );        
        $parents = [];
        $info = [];
        foreach ($query->all() as $row) {
            if( !array_key_exists($row['pname'], $info) ){
                $info[$row['pname']] = [
                    'name' => $row['pname'],
                    'type' => $row['ptype'],
                    'rule' => $row['prule'],
                ];
            }
            if( !array_key_exists($row['cname'], $info) ){
                $info[$row['cname']] = [
                    'name' => $row['cname'],
                    'type' => $row['ctype'],
                    'rule' => $row['crule'],
                ];
            }
            $parents[$row['pname']][] = $row['cname']; //['parent']=[ child, .... ];
        }
        $result = [
                    'info' => $info,
                    'parent' => $parents
            ];
    }
    /*
    返回指定授权的所有子授权层级数组
    $parent : 父授权名
    $childListAll: buildChildrenList()返回的数组
    */
    public static function getItems($parent, &$childListAll){
        $items = [];
        if(array_key_exists($parent, $childListAll['parent'])){
            foreach($childListAll['parent'][$parent] as $child){
                $items[] = static::getItems($child, $childListAll);
            }
        }
        $result = [
            'name' => $parent,
            'type' => $childListAll['info'][$parent]['type'],
            'rule' => $childListAll['info'][$parent]['rule'],
            'items' => $items,
        ];
        return $result;
    }

}