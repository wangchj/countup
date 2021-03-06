<?php

namespace app\controllers;

use \DateTime;
use \DateTimeZone;
use \Exception;
use \InvalidArgumentException;
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
					['allow'=>true, 
                        'actions'=>[
                            'add','update','reset','stop', 'get-days', 'ajax-remove', 'data', 'update-display-order'
                        ],
                        'roles'=>['@']],
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

        //Summary
        $counter->summary = trim($counter->summary);

        //Time zone
        try{
            $timezone = new DateTimeZone($counter->timeZone);
        }
        catch(Exception $ex) {
            throw new BadRequestHttpException("Time zone {$counter->timeZone} is invalid.");
        }

        //Start date use for the first entry of History for this counter
        if(!$startDate = new DateTime(Yii::$app->request->post('Counter')['startDate'], $timezone))
           throw new BadRequestHttpException('Format for start date cannot be understood.');

        //Owner user id
        $counter->userId = Yii::$app->user->id;

        //whether or not this counter is public
        if(!$counter->public)
            $counter->public = false;

        //The display order of this counter
        $counter->dispOrder = Counter::getNextOrderNum($counter->userId);

        //If no error, save counter
        if(!$counter->validate())
           throw new BadRequestHttpException(reset(reset($counter->errors)));

        if(!$counter->save()) {
            Yii::error($counter);
            throw new BadRequestHttpException('Oh no, something is wrong. Your error has been logged.');
        }

        //First history entry of this counter
        $history = new History();
        $history->counterId = $counter->counterId;
        $history->startDate = $startDate->format('Y-m-d');
        $history->save();
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
     * Ajax update counter display order.
     * Index are 0 based where smaller index is displayed before larger index.
     */
    public function actionUpdateDisplayOrder($counterId, $oldIndex, $newIndex) {
        if(!$counter = Counter::findOne($counterId))
            throw new NotFoundHttpException('Counter not found');
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->userId != $counter->userId)
            throw new ForbiddenHttpException('Permission denied');
        if($oldIndex == $newIndex || $newIndex < 0)
            return;
        if($oldIndex > $newIndex)
            $command = Yii::$app->db->createCommand(
                "update Counters set dispOrder = dispOrder + 1 where dispOrder < $oldIndex and dispOrder >= $newIndex"
            );
        else
            $command = Yii::$app->db->createCommand(
                "update Counters set dispOrder = dispOrder - 1 where dispOrder > $oldIndex and dispOrder <= $newIndex"
            );
        $command->execute();
        $counter->dispOrder = $newIndex;
        $counter->save();
    }

    /**
     * Resets the count of the counter with the id $counterId to 0.
     * This action should only used by Ajax call and returns a json response.
     *
     * If the counter is not active, this action has no effect.
     *
     * @param $counterId integer The database id of the counter to reset.
     * @param $resetDate string  A date string in a format in
     *        http://php.net/manual/en/datetime.formats.php
     *
     * @return An array with the summary information of the counter. This information is used to update the user
     *         interface using JavaScript. The array has the following format:
     *         [
     *             'count' => ['count' => integer, 'startDate' => DateTime],
     *             'best' => ['count' => integer, 'startDate' => DateTime, 'endDate' => DateTime]
     *         ]
     *
     * @throws ForbiddenHttpException current user is not the owner of the counter.
     * @throws NotFoundHttpException if the counter cannot be found
     * @throws BadRequestHttpException if $resetDate is before the current start date of the counter or after today.
     */
    public function actionReset($counterId, $resetDate)
    {
        $counter = $this->findModel($counterId);
        
        //Make sure the current user is owners
        if(Yii::$app->user->id !== $counter->userId)
            throw new ForbiddenHttpException();

        try{
            $counter->reset($resetDate);
        }
        catch(InvalidArgumentException $ex){
            throw new BadRequestHttpException($ex->getMessage());
        }

        $count = ['count'=>$counter->getDays(), 'startDate'=>$counter->getCurrentStartDate()];
        $best  = $counter->getBest();

        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

        return ['count'=>$count, 'best'=>$best];
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
     *
     * @param $counterId integer The id of the counter to be deleted.
     *
     * @throws NotFoundHttpException if $counterId does not exist in the database.
     * @throws ForbiddenHttpException if the current user is not the owner of the counter.
     */
    public function actionAjaxRemove($counterId) {
        if(!$counter = Counter::findone($counterId))
            throw new NotFoundHttpException('Counter not found.');
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->userId != $counter->userId)
            throw new ForbiddenHttpException();
        History::deleteAll(['counterId'=>$counterId]);
        $command = Yii::$app->db->createCommand(
            "update Counters set dispOrder = dispOrder - 1 where dispOrder > {$counter->dispOrder} and
            userId = {$counter->userId}")->execute();
        $counter->delete();
    }

    /**
     * Stops the count of the counter specified by $counterId. This action is intended to be called by Ajax from the
     * client side.
     *
     * @param $counterId integer The database id of the counter.
     * @param $stopDate  string  A date string in a format in http://php.net/manual/en/datetime.formats.php
     *
     * @return An array with the summary information of the counter. This information is used to update the user
     *         interface using JavaScript. The array has the following format:
     *         [
     *             'count' => ['count' => integer, 'startDate' => DateTime],
     *             'best' => ['count' => integer, 'startDate' => DateTime, 'endDate' => DateTime]
     *         ]
     *
     * @throws ForbiddenHttpException current user is not the owner of the counter.
     * @throws NotFoundHttpException if the counter cannot be found
     * @throws BadRequestHttpException if $stopDate is before the current start date of the counter or after today.
     */
    public function actionStop($counterId, $stopDate)
    {
        $counter = $this->findModel($counterId);
        
        //Make sure the current user is owners
        if(Yii::$app->user->id !== $counter->userId)
            throw new ForbiddenHttpException();

        try{
            $counter->stop($stopDate);
        }
        catch(InvalidArgumentException $ex){
            throw new BadRequestHttpException($ex->getMessage());
        }

        $count = ['count'=>$counter->getDays(), 'startDate'=>$counter->getCurrentStartDate()];
        $best  = $counter->getBest();

        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

        return ['count'=>$count, 'best'=>$best];
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
