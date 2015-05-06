<?php

namespace app\controllers;

use \DateTime;
use \DateTimeZone;
use \Exception;
use Yii;
use yii\web\HttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

//Models
use app\models\Counter;
use app\models\User;
use app\models\History;

use app\controllers\HistoryController;

class CounterController extends \yii\web\Controller
{
    public $markNone = 0;
    public $markDone = 1;
    public $markMiss = 2;

	public function behaviors()
	{
		return [
			'access'=>[
				'class'=>AccessControl::className(),
				'rules'=>[
                    ['allow'=>true, 'actions'=>['index','view'], 'roles'=>['?','@']],
					['allow'=>true, 'actions'=>['add','update','reset','deactivate', 'mark', 'get-days', 'ajax-remove', 'data'], 'roles'=>['@']],
				]
			],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                ],
            ],

		];
	}

    /**
     * Ajax action for adding a new user counter
     */
    public function actionAdd()
    {
    	$counter = new Counter();
        $counter->load(Yii::$app->request->post());

        //Process label
        if(!$counter->label = trim($counter->label))
           throw new BadRequestHttpException('Please fill in a label.');

        try{
            $timezone = new DateTimeZone($counter->timeZone);
        }
        catch(Exception $ex) {
            throw new BadRequestHttpException("Time zone {$counter->timeZone} is invalid.");
        }
        
        if($counter->startDate && $date = new DateTime($counter->startDate, $timezone))
           $counter->startDate = $date->format('Y-m-d');
        else
           throw new BadRequestHttpException('Format for start date cannot be understood.');

        $counter->userId = Yii::$app->user->id;

        if($counter->type == 'weekly') {
            foreach($_POST['day'] as $day=>$val) {
                if($val) {
                    if($counter->on)
                        $counter->on .= ',';
                    $counter->on .= $day;
                }
            }
        }

        if(!$counter->public)
            $counter->public = false;

        //If no error, save counter
        if(!$counter->validate())
           throw new BadRequestHttpException(reset(reset($counter->errors)));

        if(!$counter->save()) {
            Yii::error($counter);
            throw new BadRequestHttpException('Oh no, something is wrong. Your error has been logged.');
        }

        $firstEntry = new History();
        $firstEntry->counterId = $counter->counterId;
        $firstEntry->date = $counter->startDate;
        $firstEntry->miss = false;
        $firstEntry->save();
    }

    /**
     * Returns the json data for a counter.
     */
    public function actionData($counterId) {
        if(!$counter = Counter::findOne($counterId))
            throw new NotFoundHttpException('Counter not found');
        if(Yii::$app->user->isGuest || !$this->canAccessCounter(Yii::$app->user->identity, $counter))
            throw new ForbiddenHttpException();

        $res = [
            'counterId'=>$counter->counterId,
            'userId'=>$counter->userId,
            'label'=>$counter->label,
            'summary'=>$counter->summary,
            'timeZone'=>$counter->getTimeZone(),
            'startDate'=>(new DateTime($counter->startDate, $counter->getTimeZone()))->format('m/j/Y'),
            'type'=>$counter->type,
            'every'=>$counter->every,
            'on'=>$counter->on,
            'active'=>$counter->isActive(),
            'public'=>$counter->public
        ];

        return json_encode($res);
    }

    private function canAccessCounter($user, $counter) {
        return $counter->public || $user->userId == $counter->userId;
    }

    /**
     * Ajax counter update
     * @throws NotFoundHttpException if the counter cannot be found
     */
    public function actionUpdate()
    {
        $counter = $this->findModel($_REQUEST['Counter']['counterId']);

        $counter->load(Yii::$app->request->post());

        if(!$counter->label = trim($counter->label))
           throw new BadRequestHttpException('Please fill in a label.');

        try{
            $timezone = new DateTimeZone($counter->timeZone);
        }
        catch(Exception $ex) {
            throw new BadRequestHttpException("Time zone {$counter->timeZone} is invalid.");
        }
        
        if($counter->startDate && $date = new DateTime($counter->startDate, $timezone))
           $counter->startDate = $date->format('Y-m-d');
        else
           throw new BadRequestHttpException('Format for start date cannot be understood.');

        //$counter->userId = Yii::$app->user->id;

        if($counter->type == 'weekly') {
            $counter->on = '';
            foreach($_POST['day'] as $day=>$val) {
                if($val) {
                    if($counter->on)
                        $counter->on .= ',';
                    $counter->on .= $day;
                }
            }
        }

        if(isset($_POST['Counter']['public']))
            $counter->public = true;
        else
            $counter->public = false;

        //If no error, save counter
        if(!$counter->validate())
           throw new BadRequestHttpException(reset(reset($counter->errors)));

        if(!$counter->save()) {
            Yii::error($counter);
            throw new BadRequestHttpException('Oh no, something is wrong. Your error has been logged.');
        }
    }

    /**
     * Sets start date of the count to today's date.
     * @param $id counter id.
     * @throws ForbiddenHttpException current user is not the owner of the counter.
     */
    public function actionReset($id)
    {
        $model = $this->findModel($id);
        $user = User::findOne(Yii::$app->user->id);
        //Make sure the current user is owners
        if(Yii::$app->user->id === $model->userId)
        {
            $now = new \DateTime('now', new \DateTimeZone($user->timeZone));

            //Record history
            HistoryController::insertHistory($model, $now);

            //Update start date
            $model->startDate = $now->format('Y-m-d');
            $model->save();
            return $this->redirect(['index', 'username'=>$user->userName]);
        }

        throw new ForbiddenHttpException();
    }

    /**
     * Ajax call to set a date.
     */
    public function actionMark($action, $counterId, $date) {
        $counter = Counter::findOne($counterId);
        if(!$counter)
            throw new NotFoundHttpException('Counter not found');

        //Check counter owner
        if(Yii::$app->user->identity->userId != $counter->userId)
            throw new ForbiddenHttpException();

        //$date = new DateTime($date, $counter->getTimeZone());

        if(!$date = new DateTime($date, $counter->getTimeZone()))
            throw new HttpException('Invalid date');
        $date = $date->format(History::$dateFormat);

        $history = History::findOne(['counterId'=>$counterId, 'date'=>$date]);

        if(!$history) { //This date is not marked
            if($action == $this->markNone)
                return;
            $history = new History();
            $history->counterId = $counterId;
            $history->date = $date;
            $history->miss = ($action == $this->markMiss ? true : false);
            $history->save();
        }
        else if($history->miss) { //This date is marked as miss
            if($action == $this->markMiss)
                return;
            else if($action == $this->markDone) {
                $history->miss = false;
                $history->save();
            }
            else if($action == $this->markNone) {
                $history->delete();
            }
        }
        else { //This date is marked as done
            if($action == $this->markDone)
                return;
            else if($action == $this->markMiss) {
                $history->miss = true;
                $history->save();
            }
            else if($action == $this->markNone) {
                $history->delete();
            }
        }
    }

    /**
     * Ajax get counter current day count.
     */
    public function actionGetDays($counterId) {
        if(!$counter = Counter::findOne($counterId))
            throw new NotFoundHttpException('Counter not found');
        return $counter->getDays();
    }

    /**
     * Entry point for ajax remove of a counter.
     */
    public function actionAjaxRemove($counterId) {
        if(!$counter = Counter::findone($counterId))
            throw new NotFoundHttpException('Counter not found.');
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->userId != $counter->userId)
            throw new ForbiddenHttpException();
        History::deleteAll(['counterId'=>$counterId]);
        $counter->delete();
    }

    /**
     * Sets active field of the count to false.
     * @param $id counter id.
     * @throws ForbiddenHttpException current user is not the owner of the counter.
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $user = User::findOne(Yii::$app->user->id);
        //Make sure the current user is owners
        if(Yii::$app->user->id === $model->userId)
        {
            //Record history
            $now = new \DateTime('now', new \DateTimeZone($user->timeZone));
            HistoryController::insertHistory($model, $now);

            $model->active = false;
            $model->save();
            return $this->redirect(['index', 'username'=>$user->userName]);
        }

        throw new ForbiddenHttpException();
    }

    /**
     * Shows details of a counter.
     * TODO: test when model is not found.
     * @param $id counter id.
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Counter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Counter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = COunter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

/*
Views
1. Counter List
2. Counter Add
3. Counter Edit
4. Counter Remove

5. History


up.org          	Info page
up.org/login		Login page
up.org/username		List of counters
up.org/add			Add new counter
up.org/edit/100     Edit counter id 100
up.org/100          View counter id 100
up.org/history
*/
