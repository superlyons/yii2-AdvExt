<?php
/**
 'Ass-authorization' => '已分配权限视图',
        将此此句添加到mdmsoft/yii2-admin/messages/zh-CNrbac-admin.php
 
 此处翻译作用于i18n['yii2_adv_ext'], 因此请在应用的配置文件中设置
    'i18n' => [
            'translations' => [
                'yii2_adv_ext' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en',
                    'basePath' => '@superlyons/yii2advext/messages'
                ]
            ],
        ],
    或在代码中:
    Yii::$app->i18n->translations['yii2_adv_ext'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@superlyons/yii2advext/messages'
    ];
 */
return [
    //AssAuthorizationController控制器相关翻译
    'AssAuthorizationController List' => '已分配权限列表',
    'AssAuthorizationController Relation' => '已分配权限关系',
    'Name' => '权限名',
    'Type' => '权限类型',
    'Rule' => '权限规则',

	
    //Nav标题翻译
    'SUB MENUS' => '子菜单',
	'MAIN NAVIGATION' => '主导航',
    'QUICK NAVIGATION' => '快速导航',
    'QUICK LABELS' => '快捷标签',
    "More..." => "更多..",
    

    //数据翻译
    //========================================================================

    //导航
    "Post Manage" => "信息管理",
    "Comment Manage" => "评论管理",
    "Settings" => "设置",
    "Site Information" => "站点信息",
    "Register" => "注册",
    "Backend Access" => "后台访问",
    "About Me" => "关于我",
    "Top Nav 1" => "顶级导航一",
    "Top Nav 2" => "顶级导航二",
    "Top Nav 3" => "顶级导航二",
    "Sub Nav 1" => "子导航一",
    "Sub Nav 2" => "子导航二",
    "Sub Nav 3" => "子导航三",

    //快捷导航
    'Login' => "登录",
    "Logout" => "退出登录",
    "Authorization Manage" => "授权管理",
    "User Manage" => "用户管理",
    "Mptt Manage" => "Mptt管理",
    "Authorization Help" => "授权帮助",
    "Assignments" => "分配列表",
    "Roles" => "角色列表",
    "Permissions" => "权限列表",
    "Routes" => "路由列表",
    "Rules" => "规则列表",
    "Ass-authorization" => "已分配权限",
    "Users" => "用户列表",
    "Mptt Help" => "Mptt帮助",
    "Create Mptt" => "创建节点",
    "Mptt Nodes" => "Mptt 节点",
    "Mptt Trees" => "Mptt 树",


    //登录相关翻译-视图
    //========================================================================
    //登录
    "Sign in to start your session" => "登录以开始会话",
    "E-Mail" => "邮箱",
    "Password" => "密码",
    "Verify Code" => "验证码",
    "Sign In" => "登录",
    "I forgot my password" => "我忘记了我的密码",
    "Register a new membership" => "注册一个新的会员",
    //注册
    "Register a new membership" => "注册一个新的会员",
    "User Name" => "用户名",
    "Retype Password" => "重新输入密码",
    "I agree to the" => "我同意",
    "terms" => "条款",
    "Signup" => "注册",
    "I already have a membership" => "我已是会员",
    //请求密码重置
    "Request password reset" => "请求密码重置",
    "Please fill out your email. A link to reset password will be sent there." => "请填写您的电子邮件。 将发送重置密码的链接。",
    "Send" => "发送",
    //密码重置
    "Reset your password" => "重置您的密码",
    "Reset" => "重置",
    //loginMessage
    "Error !!!" => "错误 !!!",
    "Success !!!" => "成功 !!!",

    //登录相关翻译-模型+控制器
    //========================================================================
    //LoginForm model
    "E-Mail" => "邮箱",
    "Password" => "密码",
    "Remember Me" => "记住我",
    "Verification Code" => "验证码",
    "Incorrect username or password." => "错误的 用户名 或 密码",
    //SignupForm model
    "User Name" => "用户名",
    "Retype Password" => "重新输入密码",
    'This "User Name" has already been taken.' => "用户名已被占用",
    "This E-Mail address has already been taken." => "邮箱已被占用",
    "Must agree Site terms!" => "必须同意站点服务条款",
    //PasswordResetRequestForm
    "There is no user with such email." => "没有用户使用此类电子邮件",
    //ResetPasswordForm model
    "Password reset token cannot be blank." => "密码重置令牌不能为空",
    "Wrong password reset token." => "错误的密码重置令牌。",
    "New password was saved." => "新密码已被保存",
    //MemberController
    "Check your email for further instructions." => "请查看您的电子邮件以了解详情。",
    "Sorry, we are unable to reset password for email provided." => "很抱歉，我们无法为提供的电子邮件重设密码。",

    //UserFrom
    "Status" => "状态",
    "Change Password" => "修改密码",
    //UserController index
    'Create User' => "创建用户",
    "Activate" => "活跃",
    "Locked" => "锁定",
    "Created At" => "创建于",
    "Updated At" => "更新于",
    "Are you sure you want to activate this user?" => "确定要解锁此用户?",
    //create
    "Create" => "创建",
    //update
    "Update User:" => "更新用户: ",
    "Update" => "更新",





];