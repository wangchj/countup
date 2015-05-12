<?php

namespace app\controllers;

use \DateTime;
use \DateTimeZone;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\History;

use Facebook\FacebookSession;
use Facebook\FacebookJavaScriptLoginHelper;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            $this->layout = '@app/views/layouts/landing';
            return $this->render('index');
        }
        else {
            $username = Yii::$app->user->identity->userName;
            $userId   = Yii::$app->user->identity->userId;
            $this->redirect(['user/index', 'token'=>$username ? $username : $userId]);
        }
    }

    public function actionLogin()
    {
        $this->layout = '@app/views/layouts/blank';

        if (!Yii::$app->user->isGuest) {
            return $this->goBack();
        }

        $form = new LoginForm();
        $form->load(Yii::$app->request->post());

        //First process Facebook login. If this fails, try email log in.
        if(isset($_POST['fbId']) && $_POST['fbId']) {
            FacebookSession::setDefaultApplication(Yii::$app->params['fb']['appId'], Yii::$app->params['fb']['appSecret']);
            $fb_helper = new FacebookJavaScriptLoginHelper();
            try {
                $fb_session = $fb_helper->getSession();
                if($fb_session) {
                    Yii::info('fb_session good');
                    //Get the user object
                    if($user = User::findOne(['fbId'=>$_POST['fbId']])) {
                        Yii::info('user good');
                        Yii::$app->user->login($user, $form->rememberMe ? 3600*24*30 : 0);
                        return $this->goBack();
                    }
                    else {
                        return $this->render('login', ['model' => $form, 'error'=>'User is not connected with Facebook.']);
                    }
                }
            } catch(\Exception $ex) {Yii::error($ex->getMessage());}
        }

        //Facebook login was unsuccessful. Try to login using email
        $user = User::findOne(['email'=>$form->email]);
        if($user && password_verify($form->password, $user->phash))
        {
            Yii::$app->user->login($user, $form->rememberMe ? 3600*24*30 : 0);
            return $this->goBack();
        }
        else
            return $this->render('login', ['model' => $form,]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
