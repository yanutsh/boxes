<?php	

namespace app\dispatchers;

use Yii;
use app\models\Box;

class BoxEventDispatcher implements EventDispatcher
{
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $event_name = get_class($event); 
            $path = 'app\models\Events';   
            switch ($event_name) {
                case $path.'\BoxProductChanged':
                    // обработка

                    if(Box::updateBoxPrice($event->box_id))
                        Yii::$app->session->setFlash('success', 'Сумма продуктов пересчитана, количества проверены');
                    else
                        Yii::$app->session->setFlash('error', 'Сумму продуктов пересчитать или проверить количества не удалось');
                    
                        break;
               
                case $path.'\BoxVolumeChanged':
                    // расчет объема коробки и запись в БД
                    if(Box::updateBoxVolume($event->box_id))
                        Yii::$app->session->setFlash('success', 'Объем коробки пересчитан и сохранен');
                    else
                        Yii::$app->session->setFlash('error', 'Объем коробки пересчитать и сохранить не удалось');
                         
            }    
        }
    }
}