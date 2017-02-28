<?php
namespace superlyons\yii2advext\models;

use Yii;
use yii\base\Model;
use superlyons\yii2advext\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $retype_password;
    public $verifyCode;
    public $terms=true;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'superlyons\yii2advext\models\User', 'message' => Yii::t("yii2_adv_ext",'This "User Name" has already been taken.')],
            ['username', 'string', 'min' => 7, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'superlyons\yii2advext\models\User', 'message' => Yii::t("yii2_adv_ext",'This E-Mail address has already been taken.')],

            [['password','retype_password'], 'required'],
            [['password','retype_password'], 'string', 'min' => 8],

            ['retype_password','compare', 'compareAttribute'=>'password'],
            //['password','compare', 'compareAttribute'=>'retype_password'],

            ['terms', 'compare', 'compareValue'=>'1', 'message' => Yii::t("yii2_adv_ext",'Must agree Site terms!')],

            ['verifyCode', 'captcha', 'captchaAction'=>'member/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            "username" => Yii::t("yii2_adv_ext","User Name"),
            "email" => Yii::t("yii2_adv_ext","E-Mail"),
            "password" => Yii::t("yii2_adv_ext","Password"),
            "retype_password" =>  Yii::t("yii2_adv_ext",'Retype Password'),
            "verifyCode" =>  Yii::t("yii2_adv_ext",'Verification Code'),
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
        
        return $user->save() ? $user : null;
    }
}
