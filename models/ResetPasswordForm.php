<?php
namespace superlyons\yii2advext\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use superlyons\yii2advext\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $retype_password;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t("yii2_adv_ext",'Password reset token cannot be blank.')); //密码重置令牌不能为空。
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t("yii2_adv_ext",'Wrong password reset token.')); //错误的密码重置令牌。
        }
        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return [
            "password" => Yii::t("yii2_adv_ext","Password"),
            "retype_password" =>  Yii::t("yii2_adv_ext",'Retype Password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','retype_password'], 'required'],
            [['password','retype_password'], 'string', 'min' => 8],

            ['retype_password','compare', 'compareAttribute'=>'password'],
        ];
    }

    public function getEmail(){
        return $this->_user->email;
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
