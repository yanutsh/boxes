<?php 
// перевод даты в день и месяц на русском
function dateToDay($data_date)
{
	// удаляем лидирующий 0
	$data_day = date('d', strtotime($data_date)) < 10 ? substr(date('d', strtotime($data_date)), 1,1) : date('d', strtotime($data_date));
    
    // переводим в нижний регистр
    $data_month = mb_strtolower(rudate('M', strtotime($data_date)));
	if(date('m-d', strtotime($data_date)) == date('m-d')) $res = 'сег.';
	else $res = $data_day.' '. $data_month.'.' ;		

	return $res;
}