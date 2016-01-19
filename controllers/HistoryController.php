<?php

namespace app\controllers;

use Yii;
use app\models\History;
use app\models\User;
use app\models\Counter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HistoryController implements the CRUD actions for History model.
 */
class HistoryController extends Controller
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
     * Lists all History models.
     * @return mixed
     */
    public function actionIndex($username, $counterId = null)
    {
        $this->viewAccessCheck($username, $counterId);

        $owner = User::findOne(['userName'=>$username]);
        $counterLabel = null;
        $query = null;

        if($counterId == null)
        {
            if(Yii::$app->user->isGuest || Yii::$app->user->id != $owner->userId)
                $query = History::find()
                    ->joinWith('counter')
                    ->where([
                        'Counters.userId'=>$owner->id,
                        'Counters.public'=>true
                    ])
                    ->orderBy('endDate desc, startDate desc');
            else
                $query = History::find()
                    ->joinWith('counter')
                    ->where([
                        'Counters.userId'=>$owner->id,
                    ])
                    ->orderBy('endDate desc, startDate desc');
        }
        else
        {
            $counterLabel = Counter::findOne($counterId)->label;

            if(Yii::$app->user->isGuest || Yii::$app->user->id != $owner->userId)  
                $query = History::find()
                    ->joinWith('counter')
                    ->where([
                        'Counters.userId'=>$owner->id,
                        'History.counterId'=>$counterId,
                        'Counters.public'=>true
                    ])
                    ->orderBy('endDate desc, startDate desc');
            else
                $query = History::find()
                    ->joinWith('counter')
                    ->where([
                        'Counters.userId'=>$owner->id,
                        'History.counterId'=>$counterId,
                    ])
                    ->orderBy('endDate desc, startDate desc');
        }
        
        $dataProvider = new ActiveDataProvider(['query' => $query, 'sort'=>false]);

        return $this->render('index', ['dataProvider'=>$dataProvider, 'owner'=>$owner, 'counterLabel'=>$counterLabel]);
    }

    /**
     * Error checking for view history.
     * @return nothing
     * @throws NotFoundHttpException if error.
     */
    private function viewAccessCheck($username, $counterId)
    {
        //Check if user exists
        $user = User::findOne(['userName'=>$username]);
        if($user == null)
            throw new NotFoundHttpException(400,'User does not exist');

        if($counterId == null)
            return;

        $counter = Counter::findOne($counterId);

        //Check counter exists
        if($counter == null)
            throw new NotFoundHttpException(400,'Counter not found');

        //Check counter belongs to the user
        if($counter->userId != $user->userId)
            throw new NotFoundHttpException(400,'Counter not found');

        //Check that the counter is not private
        if(!$counter->public && (Yii::$app->user->isGuest || Yii::$app->user->id != $counter->userId))
            throw new NotFoundHttpException(400,'Counter not found');
    }

    /**
     * Deletes an existing History model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function actionDelete($counterId, $startDate, $endDate)
    {
        $history = $this->findModel($counterId, $startDate, $endDate);

        //If current user is guest or not owner of counter, redirect to home page.
        if(Yii::$app->user->isGuest || $history->counter->userId != Yii::$app->user->id)
            return $this->redirect(['site/index']);   

        $history->delete();

        $curUser = User::findOne(Yii::$app->user->id);
        return $this->redirect(['index', 'username'=>$curUser->userName]);
    }

    /**
     * Finds the History model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $startDate
     * @param string $endDate
     * @return History the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($counterId, $startDate, $endDate)
    {
        if (($model = History::findOne(['counterId'=>$counterId,'startDate' => $startDate, 'endDate' => $endDate])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
