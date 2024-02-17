<?php
// событие при изменении продукта в коробке - проверить совпадение количеств
namespace app\models\Events;

class BoxProductChanged
{
	public $box_id;
	public $sku;
	
	public function __construct($box_id, $sku){
		$this->box_id = $box_id;
		$this->sku = $sku;		
	}
}	
