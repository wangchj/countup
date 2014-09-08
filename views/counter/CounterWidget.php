<?php
namespace app\views\counter;

use yii\base\Widget;
use yii\helpers\Html;

class CounterWidget extends Widget
{
    /** Instance of Counter model class */
    public $counter;

    public function run()
    {
        return Html::encode($this->counter->label);
    }
}
?>
