<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

//Models
use app\models\Counter;
use app\models\User;

class CounterController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
			'access'=>[
				'class'=>AccessControl::className(),
				'rules'=>[
                    ['allow'=>true, 'actions'=>['index','view'], 'roles'=>['?','@']],
					['allow'=>true, 'actions'=>['add','update','reset','deactivate'], 'roles'=>['@']],
				]
			]
		];
	}

    /**
     * Show user's counters
     */
    public function actionIndex($username)
    {
        //Check if user exists
        $user = User::findOne(['userName'=>$username]);
        if($user == null) throw new HttpException(400,'User does not exist');

        if(Yii::$app->user->id === $user->id)
            $counters = Counter::find()->where(['userId'=>$user->id, 'active'=>true])->all();
        else
            $counters = Counter::find()->where(['userId'=>$user->id, 'active'=>true, 'public'=>true])->all();
        
        return $this->render('index', ['counters'=>$counters, 'user'=>$user]);
    }

    /**
     * Action for adding a new user counter
     */
    public function actionAdd()
    {
    	$counter = new Counter();
        
        //If post back
        if($counter->load(Yii::$app->request->post()))
        {
            //Process label
            $counter->label = trim($counter->label);
            if($counter->label === '') $counter.addError('label', 'Label cannot be blank.');

            //Process date
            if($date = new \DateTime($counter->startDate)) $counter->startDate = $date->format('Y-m-d');
            else $counter.addError('startDate', 'In correct date format.');

            $counter->userId = Yii::$app->user->id;
            $counter->active = true;

            //If no error, save counter
            if($counter->validate() && !$counter->hasErrors())
            {
                $counter->save();
                $this->redirect('index');
            }
        }

        $counter->public = true;
   		return $this->render('add', ['model'=>$counter]);
    }

    /**
     * Updates a counter.
     * @param $id counter id.
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->userId]);
        }
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
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
            $date = new \DateTime();
            $model->startDate = $date->format('Y-m-d');
            $model->save();
            return $this->redirect(['index', 'username'=>$user->userName]);
        }

        throw new ForbiddenHttpException();
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
