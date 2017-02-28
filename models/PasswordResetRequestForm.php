<?php
namespace superlyons\yii2advext\models;

use Yii;
use yii\base\Model;
use superlyons\yii2advext\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'superlyons\yii2advext\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t("yii2_adv_ext",'There is no user with such email.'),
            ],
            ['verifyCode', 'captcha', 'captchaAction'=>'member/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            "email" => Yii::t("yii2_adv_ext","E-Mail"),
            "verifyCode" =>  Yii::t("yii2_adv_ext",'Verification Code'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }
        
        if (!$user->save()) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
