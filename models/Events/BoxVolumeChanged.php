<?php
// событие при изменении продукта в коробке - проверить совпадение количеств
namespace app\models\Events;

class BoxVolumeChanged
{
	public $box_id;
		
	public function __construct($box_id){
		$this->box_id = $box_id;			
	}
}	
