<?php

namespace superlyons\yii2advext\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use superlyons\yii2advext\components\Controller;
use superlyons\yii2advext\models\LoginForm;
use superlyons\yii2advext\models\PasswordResetRequestForm;
use superlyons\yii2advext\models\ResetPasswordForm;
use superlyons\yii2advext\models\SignupForm;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\db\QueryBuilder;

/**
 * UserController implements the CRUD actions for User model.
 */
class MemberController extends Controller
{
    public $layout = "@superlyons/yii2advext/views/layouts/member.php";

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionGetDemoData(){
        $q = (new Query)->select('id')->from('mptt')->all();
        $items =[];
        foreach($q as $i){
            $items[]=$i['id'];
        }
        var_export($items);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 32,
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->render('loginMessage', [
                    'msg_type' => 'success',
                    'msg' => Yii::t("yii2_adv_ext",'Check your email for further instructions.'), //请查看您的电子邮件以了解详情。
                ]);
            } else {
                return $this->render('loginMessage', [
                    'msg_type' => 'error',
                    'msg' => Yii::t("yii2_adv_ext",'Sorry, we are unable to reset password for email provided.'), //很抱歉，我们无法为提供的电子邮件重设密码。
                ]);
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            return $this->render('loginMessage', [
                    'msg_type' => 'error',
                    'msg' => $e->getMessage(),
                ]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $this->render('loginMessage', [
                    'msg_type' => 'success',
                    'msg' => Yii::t("yii2_adv_ext","New password was saved."),
                ]);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
