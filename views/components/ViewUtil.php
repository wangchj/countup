<?php
namespace app\views\components;

class ViewUtil
{
    /**
     * Get formatted string of day count.
     * @param $interval DateInterval object
     * @return a string.
     */
    public static function getCountStr($interval)
    {
        $result = $interval->days . ' days';
        if($interval->y > 0)
            $result = $result . ' (' . $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ')';
        else if($interval->m > 0)
            $result = $result . ' (' . $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ')';
        return $result;
    }

    /**
     * Outputs 'active' when $route matches current controller/action.
     * Outputs '' empty string otherwise.
     */
    public static function activeAt($route)
    {
        if(Yii::$app->controller->route === $route)
            return 'active';
        else
            return '';
    }
}