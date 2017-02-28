Yii2 Advanced Extensions 
===================================

> 其它扩展导航: [`superlyons/idGenerator`](superlyons/idGenerator) , [`superlyons/mptt`](superlyons/mptt), [`superlyons/AdminTemplate`](superlyons/AdminTemplate)

yii2高级扩展(`superlyons/yii2advext`), 这是对`yiisoft/yii2-app-advanced`模板的增强和扩展, 本扩展的目的在于提供应用的基本功能, 使你在开发时专注于要实现的业务逻辑
这些功能包括:
*	界面: 增强导航, 支持 PC端 和 移动端
*	授权管理
*	树(Mptt)管理
*	登录,注册,密码找回
*	用户管理
*	英文/中文支持

*本扩展未使用Module实现, 因此它依赖应用的相关配置, Application或Module才可以运行, 例如: 使用本扩展提供的控制器时需要将它们附加到Application或Module的 controllerMap中*

*也可应用于你自己定制的模板, 而不是必须依赖于`yiisoft/yii2-app-advanced`*


###功能简要:

*	依赖 `superlyons/AdminTemplate`, 实现布局与登录相关的主题视图
*	提供与界面相关的助手类, 如无需指定高亮的导航项, 会根据PATHINFO或Route自动高亮导航项
*	提供与界面数据相关的助手类, 依赖 `superlyons\mptt`
*	实现登录,注册,密码找回相关的控制器, 模型 和 视图
*	扩展 `mdmsoft/admin` 实现 已分配授权视图 方便 Yii RBAC 隐含权限 的查看(例如: 
	<a href="http://www.yiiframework.com/doc-2.0/images/rbac-access-check-2.png" target="_blank">Update 是否等于 UpdateOwner</a>)
*	对User进行重新定义, 使用 `superlyons\idGenerator` 生成ID
*	提供对用户的后台管理, 增删改查
*	英文/中文支持


###依赖列表及相关连接:

*	<a href="https://github.com/yiisoft/yii2-app-advanced" target="_blank">`yiisoft / yii2-app-advanced`</a>
*	[`mdmsoft / admin`](mdm/admin)
*	[`superlyons / idGenerator`](superlyons/idGenerator)
*	[`superlyons / mptt`](superlyons/mptt)
*	[`superlyons / AdminTemplate`](superlyons/AdminTemplate)


###具体实现说明及相关配置:

1.应用AdminTemplate样式主题,实现主视图样式和用户登录, 依赖`superlyons/AdminTemplate`

*	./thems/AdminTemplate/views/*
	*	layout, member
*	./thems/AdminTemplate/AdminTmplAsset.php

```
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
```
2.提供了与界面相关的助手类:
*	./thems/AdminTemplate/components/:
	*	LayoutHelper.php
	*	LanguageAction.php
	*	SkinAction.php
	

3.提供了与界面数据相关的助手类, 依赖`superlyons/Mptt`
*	./thems/AdminTemplate/components/:
	*	NavigationDataInterface.php
	*	MpttNavigationData.php, 

4.实现与登录相关的控制器和模型
*	./controllers/MemberController.php
*	./models/
	*	LoginForm, SignupForm, PasswordResetRequestForm, ResetPasswordForm
*	./thems/AdminTemplate/views/member/*

```
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
```
5.已分配授权视图: 查看已分配授权的角色与权限的继承树图
*	./controllers/AssAuthorizationController.php
*	./components/AssignmentAuthorizationHelper.php

```
可将其配置到mdmsoft\admin中:
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
```

6.定义User AR模型: 使用`superlyons\idGenerator`生成ID
*	./models/User.php

7.提供对用户的管理
*	./controllers/UserController.php
*	./models/userForm.php, userSearch.php
*	./views/user/*

8./messages/* 全中文支持

