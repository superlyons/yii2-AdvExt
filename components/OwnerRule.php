<?php

namespace superlyons\yii2advext\components;

use yii\rbac\Rule;

/**
 * Description of OwnerRule
 *
 * @author lyons <superlyons@163.com>
 * @since 1.0
 */
class OwnerRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'owner_rule';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        //userId == $params['created_by']
        return $user == $params['created_by'] ? true : false ;
    }
}