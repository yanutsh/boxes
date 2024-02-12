<?php 
// определяем функцию сортировки массива
	function sortByOrder($a, $b) {
        return $a['listorder'] - $b['listorder'];
    }

// загрузка файлов любого расширения
	function download_file($file_name){ 
	  //$file_name=$myrow['file'];
	  header("Content-Length: ".filesize($file_name));
	  header("Content-Disposition: attachment; filename=".$file_name); 
	  header("Content-Type: application/x-force-download; name=\"".$file_name."\"");
	  dfile($file_name);
	  return;
	}

// Поиск аватара
function user_photo($avatar) {
	if (isset($avatar) && !empty($avatar) && !(trim($avatar)=="") && !is_null($avatar)) {	
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/uploads/images/users/".$avatar)) 
			return "/web/uploads/images/users/".$avatar;		
	} 	
	return "/web/uploads/images/users/nophoto.jpg";
}	

// загрузка превью видео с Ютуба
	function savePreviewVideoYouTube($link, $filename, $video_id) {
		$sourcecode=GetImageFromUrl($link);

		// проверяем наличие папки, если нет - создаем
		if (!is_dir(Yii::getAlias('@webroot').'/upload/images/Videos/Video'.$video_id))
			mkdir(Yii::getAlias('@webroot').'/upload/images/Videos/Video'.$video_id);

	    $savefile = fopen(Yii::getAlias('@webroot').'/upload/images/Videos/Video'.$video_id.'/'.$filename, 'w');
	    fwrite($savefile, $sourcecode);
	    fclose($savefile);
	    return;	
	}	 

// загрузка картинки по ее url 
	function GetImageFromUrl($link) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch,CURLOPT_URL,$link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

// Генерация кода из заданного числа цифр для подтверждения почты или телефона
function confirm_code($len=6){		
	$str="";
	for($i=0; $i<$len; $i++){
		$num=rand(0,9);
		$str .=$num;
	}
	return $str;		
}	

// отправка смс
function send_sms($phone,$text){
	//$phone = '79218471113';	
	// отправка смс на телефон $phone с текстом  $text
	$login_sms = \Yii::$app->params['login_sms'];
	$password_sms = \Yii::$app->params['password_sms'];
	$title_sms = \Yii::$app->params['title_sms'];
	$sadr_sms = \Yii::$app->params['sadr_sms'];
	
	

	//$id_sms = file_get_contents ('http://gateway.api.sc/get/?user='.$login_sms.'&pwd='.$password_sms.'&name_deliver='.$title_sms.'&sadr='.$sadr_sms.'&dadr='.$phone.'&text='.$text);

	// новый API ================================		
		// $id_sms = file_get_contents ('https://smsc.ru/sys/send.php?login=posobi.online@yandex.ru&psw=aA!49567823&phones=79218471113&mes=Простотекст&sender=Podsobi'); 		
	// новый API конец ================================

	// API от Expecto =================================
		$text_new = str_replace ( ' ' , '+' ,$text ); // обработка для API Expecto
		$str = 'https://apisms.expecto.me/messages/v2/send/?login='.$login_sms.'&password='.$password_sms.'&phone='.$phone.'&text='.$text.'&sender='.$sadr_sms;
		$id_sms = file_get_contents ('https://apisms.expecto.me/messages/v2/send/?login='.$login_sms.'&password='.$password_sms.'&phone='.$phone.'&text='.$text_new);
        // возвращает accepted;6261419441
	// API от Expecto  Конец ==========================

	
	return $id_sms; // id сообщения 
}	

// отправка почты
	function send_email($email_to, $email_from, $email_reply, $subject,$message) {
        //include($_SERVER['DOCUMENT_ROOT'].'/'.$subdir.'/masters_ini.php'); 

		//$email_admin = Yii::$app->params['adminEmail'];
        $headers = "FROM: $email_from\r\nReply-to: $email_reply\r\nContent-type: text/html; charset=utf-8\r\n";

    	if(mail($email_to, $subject, $message, $headers)) return TRUE;  
        else return FALSE;            
    }   	 
	
// работа с датами ==============================================================
	// преобразовывает дату из формата Y-m-d в формат d-m-Y  */     
	function convert_date_en_ru($str_date){   
	    $date = date_create_from_format('Y-m-d',  $str_date);
	    $date_ru=date_format($date, 'd.m.Y');    
	    return $date_ru; 
	}

	/* преобразовывает дату из формата d-m-Y в формат Y-m-d  */ 
	function convert_date_ru_en($str_date){     
	    $date = date_create_from_format('d.m.Y',  $str_date);
	    $date_en = date_format($date, 'Y-m-d H:i:s');  
	    return $date_en; 
	}

	/* преобразовывает дату БД из Y-m-d H:i:s в разные форматы формата   */ 
	function convert_datetime_en_ru($str_date){ 
	    
	    $date = date_create_from_format('Y-m-d H:i:s',  $str_date);
	  
	    $date_ru['dmY']=date_format($date, 'd.m.Y');  			// без времени
	    $date_ru['dmYHis']=date_format($date, 'd.m.Y H:i:s'); 	// со временем 
	    $date_ru['HidmY']=date_format($date, 'H:i d.m.Y'); 
	    $date_ru['dmYHi']=date_format($date, 'd.m.Y H:i'); 
	    $date_ru['Hi']=date_format($date, 'H:i'); 	            // часы + минуты 

	    $timeunix = strtotime($str_date); 						// строка в timestamp				
	    $date_ru['dMruY']=rdate('d M Y', $timeunix);    		// месяц на русском
	    $date_ru['Mru']=rdate('M', $timeunix); 	 				// месяц на русском

		$days = array(
		    // 'Понедельник', 'Вторник', 'Среда',
		    // 'Четверг', 'Пятница', 'Суббота','Воскресенье'
		    'Пн', 'Вт', 'Ср','Чт', 'Пт', 'Сб','Вс'
		);

		$date_ru['w']=$days[date('w',$timeunix)];

	    return $date_ru; 
	} 

	// Считает количество дней, прошедших с заданной даты
	function days_from($str_date) {
		// $str_date в формате YYYY-mm-dd H:s:i
		$today = time();
		$timeunix = strtotime($str_date); 	
		return floor(($today - $timeunix) / 86400);
	}

	// Считает количество дней, оставшихся до заданной даты по 
	function days_to($str_date) {
		// $str_date в формате YYYY-mm-dd H:s:i
		$today = time();
		$timeunix = strtotime($str_date); 	
		return floor(($timeunix - $today) / 86400);
	}

	// Считает время, прошедшее с заданной даты
	function time_from($str_date) {
		// $str_date в формате YYYY-mm-dd H:s:i
		$spend_time=array();
		$today = time();
		$timeunix = strtotime($str_date);
		$spend_time['days'] = floor(($today - $timeunix) / 86400);
		$spend_time['hours'] = floor(($today - $timeunix) / 3600);
		$spend_time['minutes'] = floor(($today - $timeunix) / 60);
		return $spend_time;
	}

	// Вывод даты с месяцем на русском языке
	function rdate($param, $time=0) {
		// $param - формат вывода
		// $time - время в UNIX ормате TIMESTAMP
		if(intval($time)==0) $time=time();
		$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
		if(strpos($param,'M')===false) return date($param, $time);
			else return date(str_replace('M',$MonthNames[date('n',$time)-1],$param), $time);
	}

	// вывод месяца и дня недели на русском
	function rudate($format, $timestamp = 0, $nominative_month = false)
	{
	  if(!$timestamp) $timestamp = time();
	  elseif(!preg_match("/^[0-9]+$/", $timestamp)) $timestamp = strtotime($timestamp);
	  
	  $F = $nominative_month ? array(1=>"Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь") : array(1=>"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
	  $M = array(1=>"Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек");
	  $l = array("Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");
	  $D = array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб");
	  $d = array("вс", "пн", "вт", "ср", "чт", "пт", "сб");
	  
	  $format = str_replace("F", $F[date("n", $timestamp)], $format);
	  $format = str_replace("M", $M[date("n", $timestamp)], $format);
	  $format = str_replace("l", $l[date("w", $timestamp)], $format);
	  $format = str_replace("D", $D[date("w", $timestamp)], $format);
	  $format = str_replace("d", $d[date("w", $timestamp)], $format);
	  
	  return date($format, $timestamp);
	}
	

	// Определяем количество и тип единицы измерения
	function showDate($time) { 
	  $time = time() - $time;
	  if ($time < 60) {
	    return 'меньше минуты назад';
	  } elseif ($time < 3600) {
	    return dimension((int)($time/60), 'i');
	  } elseif ($time < 86400) {
	    return dimension((int)($time/3600), 'G');
	  } elseif ($time < 2592000) {
	    return dimension((int)($time/86400), 'j');
	  } elseif ($time < 31104000) {
	    return dimension((int)($time/2592000), 'n');
	  } elseif ($time >= 31104000) {
	    return dimension((int)($time/31104000), 'Y');
	  }
	}

	// Определяем склонение единицы измерения
	function dimension($time, $type) { 
	  $dimension = array(
	    'n' => array('месяцев', 'месяц', 'месяца', 'месяц'),
	    'j' => array('дней', 'день', 'дня'),
	    'G' => array('часов', 'час', 'часа'),
	    'i' => array('минут', 'минуту', 'минуты'),
	    'Y' => array('лет', 'год', 'года')
	  );
	    if ($time >= 5 && $time <= 20)
	        $n = 0;
	    else if ($time == 1 || $time % 10 == 1)
	        $n = 1;
	    else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
	        $n = 2;
	    else
	        $n = 0;
	    return $time.' '.$dimension[$type][$n]. ' назад';

	}

// работа с датами КОНЕЦ ========================================================

// функция отправки сообщения от бота в диалог с юзером ==============================
// источник https://www.novelsite.ru/dobavlyaem-punkty-menyu-telegram-bota-na-php.html
	function message_to_telegram($bot_token, $chat_id, $text, $reply_markup = '')
	{    
	    $ch = curl_init();
	    if ($reply_markup == '') {
	        $btn[] = ["text"=>"Мои заказы", "callback_data"=>'/srv'];
	        $btn[] = ["text"=>"Заказ", "callback_data"=>'/order'];
	        $reply_markup = json_encode(["keyboard" => [$btn],  "resize_keyboard" => true]);
	    }
	    $ch_post = [
	        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
	        CURLOPT_POST => TRUE,
	        CURLOPT_RETURNTRANSFER => TRUE,
	        CURLOPT_TIMEOUT => 10,
	        CURLOPT_POSTFIELDS => [
	            'chat_id' => $chat_id,
	            'parse_mode' => 'HTML',
	            'text' => $text,
	            'reply_markup' => $reply_markup,
	        ]
	    ];

	    curl_setopt_array($ch, $ch_post);
	    curl_exec($ch);
	}

// Ввод массива на печать в удобном виде
	function debug($data,$die=true){	
		echo "<pre>";
		print_r($data);
		echo"<pre>";
		if ($die) die;
	}

?>