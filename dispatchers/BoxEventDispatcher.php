<?php	

namespace app\dispatchers;
 
class BoxEventDispatcher implements EventDispatcher
{
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
        	echo('event-'.$event.'<br>');
        }
    }
}