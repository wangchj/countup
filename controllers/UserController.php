<?php

namespace app\controllers;

use \DateTime;
use Yii;
use app\models\User;
use app\models\TempUser;
use app\models\Follow;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\helpers\Url;
use SendGrid;

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
    public function actionIndex($token)
    {
        //Viewer is the user who is currently logged in and viewing.
        if(!$viewer = Yii::$app->user->identity) {
            Yii::$app->user->logout();
            $this->goHome();
        }


        //Viewee is the user being queried and viewed.
        $viewee = User::findOne(['userName'=>$token]);

        //See if the object user exist
        if(!$viewee)
            $viewee = User::findOne(['userId'=>$token]);
        if(!$viewee)
            throw new HttpException(400,'User does not exist');

        $counters = $viewer->userId == $viewee->userId ?
            $viewee->getCounters()->orderBy('dispOrder')->all() :
            $viewee->getCounters()->where(['public'=>true])->orderBy('dispOrder')->all();

        $data = $this->makeCalendarData($counters, $viewer, $viewee);
        //$this->layout = '@app/views/layouts/blank';
        return $this->render('index', [
            'viewer'=>$viewer,
            'viewee'=>$viewee,
            'counters'=>$counters,
            'follows'=>$viewee->getRandomFollows(6),
            'followers'=>$viewee->getRandomFollowers(6),
            'data'=>$data
        ]);
    }

    /**
     * Get history date from $startDate to $endDate
     */
    private function makeCalendarData($counters, $viewer, $viewee) {
        $res = [];
        foreach($counters as $counter) {
            $now = new DateTime('now', $counter->getTimeZone());
            $start = (new DateTime())->setTimestamp(strtotime('first day of last month', $now->getTimestamp()))->format('Y-m-d');
            $end = (new DateTime())->setTimestamp(strtotime('last day of this month', $now->getTimestamp()))->format('Y-m-d');
            $data = [];

            $history = $counter->getHistory()->where("date >= '$start' and date <= '$end'")->all();

            foreach($history as $h)
                $data[$h->date] = $h->miss ? 1 : 0; //0: a mark, 1: a miss

            $res["{$counter->counterId}"] = $data;
        }
        return $res;
    }

    public function actionSignupForm() {
         $this->layout = '@app/views/layouts/blank';
        
        if(!Yii::$app->user->isGuest)
            return $this->goHome();

        $user = new User();
        
        if(Yii::$app->request->isPost && $user->load(Yii::$app->request->post()))
        {
            //Check email already exist
            if(User::findOne(['email'=>$user->email]) || TempUser::findOne(['email'=>$user->email])) {
                $user->addError('email', 'User with given email already exist');
                return $this->render('signup-form', ['model' => $user]);
            }

            //if(!$user->validate()) {
            //    return $this->render('signup-form', ['model' => $user]);   
            //}

            if(!$user->timeZone) {
                return $this->render('signup-timezone', ['model' => $user]);   
            }

            if(!$user->hasErrors()){
                $tempUser = new TempUser();
                //$tempUser->userName = $user->userName;
                $tempUser->forename = $user->forename;
                $tempUser->surname = $user->surname;
                $tempUser->gender = $user->gender;
                $tempUser->email = $user->email;
                $tempUser->phash = password_hash($user->phash, PASSWORD_BCRYPT);
                $tempUser->joinDate = date('Y-m-d H:i:s');
                $tempUser->timeZone = $user->timeZone;
                $tempUser->code = substr(md5(time()), 0, 10); //Random string of length 10
                $tempUser->validate();
                $tempUser->save();

                //Send verification email
                $link = Yii::$app->urlManager->createAbsoluteUrl(['user/verify', 'email'=>$user->email, 'code'=>$tempUser->code]);
                $sendgrid = new SendGrid(Yii::$app->params['sendgrid']['username'], Yii::$app->params['sendgrid']['password']);
                $mail = new SendGrid\Email();
                $mail->setFrom(Yii::$app->params['mail']['sender']);
                $mail->addTo($user->email);
                $mail->setSubject("CountUp Sign-up Verification");
                $mail->setText("Please follow this link to complete the sign-up process. $link");
                //$sendgrid->send($mail);

                return $this->render('pre-verify', ['model' => $user]);
            }
        }

        return $this->render('signup-form', ['model' => $user]);
    }

    public function actionSignup()
    {
        $this->layout = '@app/views/layouts/blank';
        
        if(!Yii::$app->user->isGuest)
            return $this->goHome();

        $user = new User();
        
        if(Yii::$app->request->isPost && $user->load(Yii::$app->request->post()))
        {
            return $this->processFbSignup($user);
        }

        return $this->render('signup', ['model' => $user, 'error'=>null]);
    }

    private function processFbSignup($user) {
        if(!$user->forename || !$user->surname || !$user->email)
            return $this->render('signup', ['model' => $user, 'error'=>'Facebook does not have enough information. Please sign up with Email.']);

        //If the Facebook user is already connected with the app, just login the user.
        if($u = User::findOne(['fbId'=>$user->fbId])) {
            Yii::$app->user->login($u);
            return $this->goHome();
        }

        if(User::findOne(['email'=>$user->email]) || TempUser::findOne(['email'=>$user->email]))
            return $this->render('signup', ['model' => $user, 'error'=>'Account already exist.']);

        if(!$user->timeZone)
            $user->timeZone = 'America/Chicago';

        $user->joinDate = date('Y-m-d H:i:s');
        $user->authKey = Yii::$app->getSecurity()->generateRandomString();

        if(!$user->validate()) {
            return;
        }

        $user->save();

        Yii::$app->user->login($user);
        return $this->goHome();
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
        $user->forename = $tempUser->forename;
        $user->surname = $tempUser->surname;
        $user->gender = $tempUser->gender;
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
     * Add a link between two users where follower follows followee.
     * This is to be called by ajax.
     */
    public function actionFollow($followerId, $followeeId) {
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->userId != $followerId)
            throw new ForbiddenHttpException();
        if(!$follower = User::findOne($followerId))
            throw new NotFoundHttpException('Follower does not exist');
        if(!$followee = User::findOne($followeeId))
            throw new NotFoundHttpException('Followee does not exist');

        $follow = new Follow();
        $follow->followerId = $followerId;
        $follow->followeeId = $followeeId;
        $follow->save();
    }

    /**
     * Removes a link between two users where follower unfollos followee.
     * This is to be called by ajax.
     */
    public function actionUnfollow($followerId, $followeeId) {
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->userId != $followerId)
            throw new ForbiddenHttpException();
        if(!$follower = User::findOne($followerId))
            throw new NotFoundHttpException('Follower does not exist');
        if(!$followee = User::findOne($followeeId))
            throw new NotFoundHttpException('Followee does not exist');
        if(!$follow = Follow::findOne(['followerId'=>$followerId, 'followeeId'=>$followeeId]))
            throw new NotFoundHttpException('Following relationship is not found.');

        $follow->delete();
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
