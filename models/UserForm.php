<?php
namespace superlyons\yii2advext\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use superlyons\yii2advext\models\User;
use yii\helpers\VarDumper;

/**
 * Signup form
 */
class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $retype_password;
    public $status;
    public $changePass=false;


    public $id;
    public $isNewRecord=true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        /*
        4个场景: 
            admin-create:管理员后台创建用户, 
                username, email, password, retype_password, status
            admin-update:管理员后台修改用户,
                username, email, password, retype_password, status
            user-pass-update: 用户修改自身密码
                password, retype_password
            user-email-update: 用户修改自身邮箱
                email
        */
        return [
            ['username', 'filter', 'filter' => 'trim' , 'on' => ['admin-create','admin-update']],
            ['username', 'required', 'on' => 'admin-create'],
            ['username', 'string', 'min' => 7, 'max' => 255, 'on' => ['admin-create','admin-update']],
            [
                'username', 'unique', 'on' => 'admin-create',
                'targetClass' => 'superlyons\yii2advext\models\User', 
                'message' => Yii::t("yii2_adv_ext",'This "User Name" has already been taken.'), 
            ],
            [
                'username', 'unique', 'on' => 'admin-update',
                'targetClass' => 'superlyons\yii2advext\models\User', 
                'message' => Yii::t("yii2_adv_ext",'This "User Name" has already been taken.'), 
                'filter' => $this->filterUnique(),
            ],

            ['email', 'filter', 'filter' => 'trim', 'except' => 'user-pass-update'],
            ['email', 'required', 'except' => 'user-pass-update'],
            ['email', 'email', 'except' => 'user-pass-update'],
            ['email', 'string', 'max' => 255, 'except' => 'user-pass-update'],
            [
                'email', 'unique', 'on' => 'admin-create',
                'targetClass' => 'superlyons\yii2advext\models\User', 
                'message' => Yii::t("yii2_adv_ext",'This E-Mail address has already been taken.'),
            ],
            [
                'email', 'unique', 'on' => ['admin-update','user-email-update'],
                'targetClass' => 'superlyons\yii2advext\models\User', 
                'message' => Yii::t("yii2_adv_ext",'This E-Mail address has already been taken.'),
                'filter' => $this->filterUnique(),
            ],

            [
                ['password','retype_password'], 'required', 'except'=>['user-email-update'],
                'when' => [$this,'when'],
                'whenClient' => $this->whenClient(),
            ],
            [
                ['password','retype_password'], 'string', 'min' => 8, 'except'=>['user-email-update'],
                'when' => [$this,'when'],
                'whenClient' => $this->whenClient(),
            ],

            [
                'retype_password','compare', 'compareAttribute'=>'password', 'except'=>['user-email-update'],
                'when' => [$this,'when'],
                'whenClient' => $this->whenClient(),
            ],

            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => ['admin-create','admin-update']],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_DELETED], 'on' => ['admin-create','admin-update']],

            ['changePass', 'safe', 'on' => ['admin-update']],

        ];
    }

    public function when(){
        $s = $this->getScenario();
        if($s == "admin-update" && $this->changePass != "1"){
            return false;
        }
        return true;
    }

    public function whenClient(){
        if ($this->getScenario() != "admin-update") return null;
        $checkbox = Html::getInputId($this,'changePass'); //#userform-changepass
        return "function(attribute, value){
                    return $(\"#{$checkbox}\").is(':checked');
                }";
    }

    public function filterUnique(){
        $id = $this->id;
        $callback = function($q) use ($id) {
            $q->andWhere(['<>','id', $id]);
        };
        return $callback;
    }

    public function testRules(){
        $original = $this->getScenario();
        $scenarios = [Model::SCENARIO_DEFAULT, 'admin-create', 'admin-update', 'user-pass-update', 'user-email-update'];
        foreach($scenarios as $scenario){
            echo $scenario. " : ";
            $this->setScenario($scenario);
            $attrs = $this->safeAttributes();
            var_dump($attrs);
        }
        $this->setScenario($original);
    }

    public function attributeLabels()
    {
        return [
            "username" => Yii::t("yii2_adv_ext","User Name"),
            "email" => Yii::t("yii2_adv_ext","E-Mail"),
            "password" => Yii::t("yii2_adv_ext","Password"),
            "retype_password" =>  Yii::t("yii2_adv_ext",'Retype Password'),
            'status' => Yii::t("yii2_adv_ext",'Status'),
            'changePass' => Yii::t("yii2_adv_ext",'Change Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = $this->status;
        
        return $user->save() ? $user : null;
    }

    public function updateTo($user){
        if (!$this->validate()) {
            return null;
        }

        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;
        if($this->changePass){
            $user->setPassword($this->password);
            $user->generateAuthKey();
        }
        
        return $user->save() ? $user : null;
    }
}
