<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Counter;

class CounterController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
			'access'=>[
				'class'=>AccessControl::className(),
				'rules'=>[
					['allow'=>true, 'actions'=>['index','add'],'roles'=>['@']]
				]
			]
		];
	}

    /**
     * Show user's counters
     */
    public function actionIndex()
    {
        $counters = Counter::find()->where(['userId'=>Yii::$app->user->id, 'shown'=>true])->all();
        return $this->render('index', ['counters'=>$counters]);
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
            $counter->shown = true;

            //If no error, save counter
            if($counter->validate() && !$counter->hasErrors())
            {
                $counter->save();
                $this->redirect('index');
            }
        }

   		return $this->render('add', ['model'=>$counter]);
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
