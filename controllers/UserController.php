<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\TempUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSignup()
    {
        $this->layout = '@app/views/layouts/blank';

        $model = new User();
        
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()))
        {
            /*
            //Check email already exist
            if(User::findOne(['email'=>$model->email]) != null || TempUser::findOne(['email'=>$model->email]) != null)
                $model->addError('email', 'User with given email already exist');
            //Check user name already taken
            if(User::findOne(['userName'=>$model->userName]) != null || TempUser::findOne(['userName'=>$model->userName]) != null)
                $model->addError('userName', 'User name is taken');

            if(!$model->hasErrors()){
                $tempUser = new TempUser();
                $tempUser->userName = $model->userName;
                $tempUser->email = $model->email;
                $tempUser->phash = password_hash($model->phash, PASSWORD_BCRYPT);
                $tempUser->joinDate = date('Y-m-d H:i:s');
                $tempUser->timeZone = $model->timeZone;
                $tempUser->code = substr(md5(time()), 0, 10); //Random string of length 10
                $tempUser->save();

                Yii::$app->mailer->compose('verify_email', ['email'=> $tempUser->email, 'code'=>$tempUser->code])
                    ->setFrom('signup@countup.org')
                    ->setTo($tempUser->email)
                    ->setSubject('Please verify email')
                    ->send();

                return $this->render('pre-verify', ['model' => $model]);
                
            }*/

            return $this->processFbSignup($model);
        }

        return $this->render('signup', ['model' => $model, 'error'=>null]);
    }

    private function processFbSignup($user) {
        if(!$user->email)
            return $this->render('signup', ['model' => $user, 'error'=>'Facebook does not have enough information. Please sign up with Email.']);

        if(User::findOne(['email'=>$user->email]) || User::findOne(['fbId'=>$user->fbId]))
            return $this->redirect(['site/index']);

        if(TempUser::findOne(['email'=>$user->email]))
            return $this->render('signup', ['model' => $user, 'error'=>'Account already exist.']);

        if(!$user->timeZone)
            $user->timeZone = 'America/Chicago';

        $user->joinDate = date('Y-m-d H:i:s');
        $user->authKey = Yii::$app->getSecurity()->generateRandomString();

        if(!$user->validate()) {
            var_dump($user->errors);
            return;
        }

        $user->save();

        Yii::$app->user->login($user);
        return $this->redirect(['site/index']);
    }

    public function actionVerify($email, $code)
    {
        $tempUser = TempUser::findOne(['email'=>$email, 'code'=>$code]);
        
        if($tempUser == null)
        {
            if(User::findOne(['email'=>$email]) != null)
                throw new HttpException(400,'User already verified');
            else
                throw new HttpException(400,'Incorrect email and verification code.');
        }

        $user = new User;
        $user->userName = $tempUser->userName;
        $user->email = $tempUser->email;
        $user->phash = $tempUser->phash;
        $user->joinDate = $tempUser->joinDate;
        $user->timeZone = $tempUser->timeZone;
        $user->authKey = Yii::$app->getSecurity()->generateRandomString();
        $user->save();

        $tempUser->delete();

        return $this->render('post-verify');
    }

    /**
     * Updates basic user information.
     */
    public function actionUpdate()
    {
        $user = $this->findModel(Yii::$app->user->id);
        if($user->load(Yii::$app->request->post()))
        {

        }

        return $this->render('update', ['user' => $user]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
