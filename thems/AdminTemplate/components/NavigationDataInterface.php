<?php

namespace superlyons\yii2advext\thems\AdminTemplate\components;


interface NavigationDataInterface
{   
    public function getNavs();
    public function getTopNavs();
    public function getSidebar($id);

    public function getActiveNavIdsByRoute($route=null, &$items=null, $begin=true);
    
    public function getSidebarTitle();
    public function getBrand($mini=false);
}
