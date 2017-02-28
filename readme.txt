yii2 advanced extensions 
----------------

yii2高级扩展, 对yii2-advanced模板进行扩展, 它不是yii的扩展,也不是Module, 因此它十分依赖Application和Module才可以运行
有关控制器使用它们时需要附加到Application或Module的'controllerMap' 中

功能简要:
----------------
.使用AdminTemplate界面, 实现布局与登录相关的主题视图
.提供与界面相关的助手类
.提供与界面数据相关的助手类
.实现与登录相关的控制器和模型
.扩展mdmsoft/admin实现已分配授权视图方便权限查看
.对User进行重新定义, 使用superlyons\idGenerator生成ID
.提供对用户的管理
.全英文/中文支持


依赖列表:
-----------------
	yiisoft/yii2-app-advanced
	mdm\admin
	superlyons\AdminTemplate
	superlyons\mptt
	superlyons\superlyons\idGenerator


具体实现列表
---------------------------
1.应用AdminTemplate样式主题,实现主视图样式和用户登录, 依赖superlyons/AdminTemplate
	./thems/AdminTemplate/views/*
		layout, member
	./thems/AdminTemplate/AdminTmplAsset.php
	配置参考:
		'components' => [
		        'view' =>[
		            'theme'=>[
		                'pathMap'=>[
		                    '@backend/views/layouts' => "@superlyons/yii2advext/thems/AdminTemplate/views/layouts",
		                    '@superlyons/yii2advext/views/member' => "@superlyons/yii2advext/thems/AdminTemplate/views/member",
		                    '@superlyons/yii2advext/views/layouts' => "@superlyons/yii2advext/thems/AdminTemplate/views/layouts",
		                ],
		            ],
		        ],
		        'i18n' => [
		            'translations' => [
		                'yii2_adv_ext' =>[
		                    'class' => 'yii\i18n\PhpMessageSource',
		                    'sourceLanguage' => 'en',
		                    'basePath' => '@superlyons/yii2advext/messages'  
		                ]
		            ],
		        ],

2.提供了与界面相关的助手类:
	./thems/AdminTemplate/components/:
		LanguageAction.php
		SkinAction.php
		LayoutHelper.php

3.提供了与界面数据相关的助手类, 依赖superlyons/Mptt
	./thems/AdminTemplate/components/:
		NavigationDataInterface.php
		MpttNavigationData.php, 

4.实现与登录相关的控制器和模型
	./controllers/MemberController.php
	./models/
		LoginForm, SignupForm, PasswordResetRequestForm, ResetPasswordForm
	配置参考:
	'controllerMap' => [
            'user'=>[
                'class' => 'superlyons\yii2advext\controllers\UserController',
            ],
            'member'=>[
                'class' => 'superlyons\yii2advext\controllers\MemberController',
            ],
        ],
    'components' => [
    		'user' => [
	            'identityClass' => 'superlyons\yii2advext\models\User',
	            'enableAutoLogin' => true,
	            'loginUrl' => ['member/login']
	        ],
	        'mailer' => [
	            'class' => 'yii\swiftmailer\Mailer',
	            'viewPath' => '@common/mail',
	            //默认为false，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
	            'useFileTransport' => true,
				'transport' => [  
					'class' => 'Swift_SmtpTransport',  
					'host' => 'smtp.163.com',  
					'username' => 'superlyons@163.com',  
					'password' => '******',  
					'port' => '25',
					'encryption' => 'tls',  
				],   
				'messageConfig'=>[  
					'charset'=>'UTF-8',  
					'from'=>['superlyons@163.com'=>'support group']  
				],
	        ],
    	],

5.已分配授权视图: 查看已分配授权的角色与权限的继承树图
	./controllers/AssAuthorizationController.php
	./components/AssignmentAuthorizationHelper.php
	可将其配置到mdm\admin中:
	5.1 应用程序的配置文件:
		'modules' => [
	            'admin' => [
	                'class' => 'mdm\admin\Module',
	                'layout' => 'left-menu',
	                'mainLayout' => '@app/views/layouts/main.php',
	                'controllerMap' => [
	                    'ass-authorization'=>[
	                        'class' => 'superlyons\yii2advext\controllers\AssAuthorizationController',
	                    ],
	                ],
	            ],
			],
	5.2 在mdmsoft/admin/messages/zh-CN/rabc-admin.php下配置:
		'Ass-authorization' => '已分配权限视图',
		        将此此句添加到rabc-admin\messages\zh-CN\rbac-admin.php

6.定义User AR模型: 使用superlyons\idGenerator生成ID
	./models/User.php

7.提供对用户的管理

8./messages/* 全中文支持

