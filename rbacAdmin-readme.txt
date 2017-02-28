rbacAdmin不是yii的扩展,也不是Module, 它仅对mdmsoft/admin项目进行了扩展, 
因此使用它们时需要附加到Application或Module的'controllerMap' 中

它主要实现如下功能:
1.已分配授权视图: 查看已分配授权的角色与权限的继承树图
	AssAuthorizationController
	AssignmentAuthorizationHelper
2.对User进行重新定义:
	1.使用superlyons\idGenerator生成ID
	2.可对用户进行管理

使用它需要完成如下配置:

应用程序的配置文件:
------------------------
'modules' => [
            'admin' => [
                'class' => 'mdm\admin\Module',
                'layout' => 'left-menu',
                'mainLayout' => '@app/views/layouts/main.php',
                'controllerMap' => [
                    'ass-authorization'=>[
                        'class' => 'superlyons\rbacadmin\controllers\AssAuthorizationController',
                    ],
                ],
            ],
	],

'aliases' => [
	   '@superlyons/rbacadmin' => dirname(dirname(__DIR__))."/extensions/superlyons/rbacAdmin",
    ],


翻译作用于i18n['ly-rbacadmin'], 因此请在应用的配置文件中设置
    'i18n' => [
            'translations' => [
                'ly-rbacadmin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en',
                    'basePath' => '@superlyons/rbacadmin/messages'
                ]
            ],
        ],
    或在代码中:
    Yii::$app->i18n->translations['ly-rbacadmin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@superlyons/rbacadmin/messages'
    ];

在mdmsoft/admin/messages/zh-CN/rabc-admin.php下配置:
'Ass-authorization' => '已分配权限视图',
        将此此句添加到rabc-admin\messages\zh-CN\rbac-admin.php
 
