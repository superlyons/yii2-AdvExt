<?
use yii\helpers\Html;
?>

<h1><?=Yii::t('yii2_adv_ext', 'AssAuthorizationController Relation')?></h1>

<?

function echoTree(&$items){
    $tmp = "<span class='{class} lead'><strong>{item} {role}</strong></span>";
    foreach($items as $item ){
        $tr=[
                '{class}' => 'text-danger',
                '{role}' => '',
            ];
        echo "<ul class=\"list-group\"><li class=\"list-group-item\">";
        if($item['type'] == 2){
            $tr['{class}'] = 'text-primary';
        }
        if(!empty($item['rule'])){
            $tr['{role}'] = '('.$item['rule'].')';
        }
        $tr['{item}'] = $item['name'];
        echo strtr($tmp, $tr);
        if($item['items']) echoTree($item['items']);
        echo "</li></ul>";
    }
}
echoTree($tree);
?>