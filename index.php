<?php

//$GLOBALS['sendmessageflag'] = 0;
$sendmessageflag = 0;

/**
 * revcom_bot
 *
 * @author - Александр Штокман
 */
header('Content-Type: text/html; charset=utf-8');
// подрубаем API
require_once("vendor/autoload.php");
// подрубаем базу данных
require_once("db_connect.php");
require_once("users.php");

// дебаг
if(true){
	error_reporting(E_ALL & ~(E_NOTICE | E_USER_NOTICE | E_DEPRECATED));
	ini_set('display_errors', 1);
}

// создаем переменную бота
$token = "1619983375:AAElotD9IbrwfvHfIQBYB17nA0SvUNVwiHk";
$bot = new \TelegramBot\Api\Client($token,null);






// демо постинга в канал(бот должен быть админом в канале)
 if($_GET["bname"] == "doiteasybot"){
       $bot->sendMessage("@Aikidovladivistok", "Тест");
}

// если бот еще не зарегистрирован - регистируем
if(!file_exists("registered.trigger")){
	/**
	 * файл registered.trigger будет создаваться после регистрации бота.
	 * если этого файла нет значит бот не зарегистрирован
	 */

	// URl текущей страницы
	$page_url = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$result = $bot->setWebhook($page_url);
	if($result){
		file_put_contents("registered.trigger",time()); // создаем файл дабы прекратить повторные регистрации
	} else die("ошибка регистрации");
}

// Команды бота
// пинг. Тестовая
$bot->command('ping', function ($message) use ($bot) {
	$bot->sendMessage($message->getChat()->getId(), 'pong!');
});


 // сообщение
 $bot->command('m', function ($message) use ($bot) {
#       $usern = '@'.$message->getFrom()->getUsername();
       $username = $message->getFrom()->getLastname().' '.$message->getFrom()->getFirstname();
//Мой ИД: 257902696
       $usern = $message->getFrom()->getId();
       $chatid = $message->getChat()->getId();
       //Если сообщение лично боту:
       if ($usern == $chatid) {
            	    $bot->sendMessage($message->getFrom()->getId(), "<b>Hello</b>\r\n\r\n<i>How are you?</i>\r\n\r\n This is bot WITH mysql. \r\n\r\n Test private  message TO ".$usern.' ChatID:'.$chatid.hex2bin('f09f9880'),html);




       } else {

        $bot->sendMessage($message->getChat()->getId(), 'Было отправлено личное сообщение для  '.$username);
    	    $bot->sendMessage($message->getFrom()->getId(), 'Отправляю приватное сообщение в ответ на сообщение в группе TO '.$usern.' ChatID:'.$chatid);

       }
 });



// обязательное. Запуск бота
$bot->command('start', function ($message) use ($bot) {
    $answer = "Добро пожаловать! \r\nДля помощи введите /help \r\nДля тестирования введите /menurole";
    $bot->sendMessage($message->getChat()->getId(), $answer);
});

// помощь, написать текст,описание 
$bot->command('help', function ($message) use ($bot) {
    $answer = "Привет!\xE2\x9C\x8B Меня зовут Нафаня. Я онлайн бот, созданный специально для ДВФУ \xF0\x9F\x90\x9A, который может помочь тебе. В независимости от того, что у тебя случилось.
Нажав кнопку ниже, ты сможешь выбать роль \xF0\x9F\x91\x87
/menurole 
";
    $bot->sendMessage($message->getChat()->getId(), $answer);
});

// передаем картинку
$bot->command('getpic', function ($message) use ($bot) {
	$pic = "http://aftamat4ik.ru/wp-content/uploads/2017/03/photo_2016-12-13_23-21-07.jpg";

    $bot->sendPhoto($message->getChat()->getId(), $pic);
});

// передаем документ
$bot->command('getdoc', function ($message) use ($bot) {
	$document = new \CURLFile('shtirner.txt');

    $bot->sendDocument($message->getChat()->getId(), $document);
});

// Выбор роли
$bot->command("menurole", function ($message) use ($bot) {
//Если пользователь - я (257902696), то возможность перейти в тестовый режим

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'menu2', 'text' => 'Ученик'],
				['callback_data' => 'menu1', 'text' => 'Учитель']
			]

		],
	 true,true);

	$bot->sendMessage($message->getChat()->getId(), "Выберите роль", false, null,null,$keyboard);
});



// Кнопки у сообщений
$bot->command("menu", function ($message) use ($bot) {
//Если пользователь - я (257902696), то возможность перейти в тестовый режим

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'help', 'text' => 'Помощь', true , true],
				['callback_data' => 'data_test2', 'text' => 'Где я?'],
				['callback_data' => 'promice_type', 'text' => 'Запрос из БД'],
				['callback_data' => 'register_user', 'text' => 'Регистрация']
			],
			[
				['callback_data' => 'help', 'text' => 'Помощь2'],
				['callback_data' => 'data_test2', 'text' => 'Где я?2'],
				['callback_data' => 'promice_type', 'text' => 'Запрос из БД2']
			]
		],
	 true,true);

	$bot->sendMessage($message->getChat()->getId(), "Выберите роль", false, null,null,$keyboard);
});


// Обработка кнопок у сообщений
$bot->on(function($update) use ($bot, $callback_loc, $find_command){
	$callback = $update->getCallbackQuery();
	$message = $callback->getMessage();
	$chatId = $message->getChat()->getId();
	$data = $callback->getData();

	if($data == "help"){
		$bot->answerCallbackQuery( $callback->getId(), "Для помощи введите /help",true);
	}

	if($data == "motivate"){
		$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule2', 'text' => 'Уроки', true , true],
				['callback_data' => 'duty', 'text' => 'Дежурства'],
				['callback_data' => 'dr', 'text' => 'ДР класса']
			],
			[

				['callback_data' => 'register_user', 'text' => 'Сообщить об отсутствии']
			],
			[

				['callback_data' => 'motivate', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(),  get_motivate()."\nДля вывода меню введите /menurole", html, null,null,$keyboard);

//		$bot->sendMessage($chatId, get_motivate()."\nДля вывода меню введите /menurole");
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке

$res.="\nДля вывода меню введите /menurole";

	}

	if($data == "motivate2"){
//			]

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule3', 'text' => 'Расписание', true , true],
				['callback_data' => 'message_get', 'text' => 'Отсутствующие']
				],
				[
				['callback_data' => 'message_set', 'text' => 'Написать сообщение']
				],
				[
				['callback_data' => 'dr2', 'text' => 'ДР класса'],
				['callback_data' => 'motivate2', 'text' => 'Motivation']
			]

		],
	 true,true);
//	$bot->sendMessage($message->getChat()->getId(), "Выберите действие для учителя", false, null,null,$keyboard);


	$bot->sendMessage($message->getChat()->getId(),  get_motivate()."\nДля вывода меню введите /menurole", html, null,null,$keyboard);

//		$bot->sendMessage($chatId, get_motivate()."\nДля вывода меню введите /menurole");
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке

$res.="\nДля вывода меню введите /menurole";

	}


//Меню УЧИТЕЛЯ
	if($data == "menu1"){
	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule3', 'text' => 'Расписание', true , true],
				['callback_data' => 'message_get', 'text' => 'Отсутствующие']
				],
				[
				['callback_data' => 'message_set', 'text' => 'Написать сообщение']
				],
				[
				['callback_data' => 'dr2', 'text' => 'ДР класса'],
				['callback_data' => 'motivate2', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(), "Выберите действие для учителя", false, null,null,$keyboard);


	}

//МЕНЮ УЧЕНИКОВ
	if($data == "menu2"){
	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule2', 'text' => 'Уроки', true , true],
				['callback_data' => 'duty', 'text' => 'Дежурства'],
				['callback_data' => 'dr', 'text' => 'ДР класса']
			],
			[

				['callback_data' => 'register_user', 'text' => 'Сообщить об отсутствии']
			],
			[

				['callback_data' => 'motivate', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(), "Выберите действие для ученика", false, null,null,$keyboard);


	}

	if($data == "data_test2"){
$bot->sendMessage($message->getChat()->getId(), $answer);
		$bot->sendMessage($chatId, "Это бот, который позволит выработать привычку");
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
	}


	if($data == "duty"){
$res = "\xF0\x9F\x93\x8D Дежурства
\xF0\x9F\x94\x86Понедельник – Петров, Смирнов, Сидоров, Куйдавов
\xF0\x9F\x94\x86Вторник -  Милюхина, Уварова, Гусина, Бородина
\xF0\x9F\x94\x86Среда – Николаев, Орлова, Андреев, Макарова
\xF0\x9F\x94\x86Четверг – Шаповалова, Покровский, Бочарова, Никольский
\xF0\x9F\x94\x86Пятница – Яковлева, Григорьева, Романов, Воробьев";
$res.="\nДля вывода меню введите /menurole";

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule2', 'text' => 'Уроки', true , true],
				['callback_data' => 'duty', 'text' => 'Дежурства'],
				['callback_data' => 'dr', 'text' => 'ДР класса']
			],
			[

				['callback_data' => 'register_user', 'text' => 'Сообщить об отсутствии']
			],
			[

				['callback_data' => 'motivate', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(), $res, html, null,null,$keyboard);


//		$bot->sendMessage($chatId, $res,html);
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
	}

	if($data == "dr"){
$res = "Бочарова Елизавета Анатольевна 17.04.2005
Бородина Ирина Леонидовна 01.05.2005
Яковлева Анастасия Владиславовна 15.05.2004";
$res.="\nДля вывода меню введите /menurole";

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule2', 'text' => 'Уроки', true , true],
				['callback_data' => 'duty', 'text' => 'Дежурства'],
				['callback_data' => 'dr', 'text' => 'ДР класса']
			],
			[

				['callback_data' => 'register_user', 'text' => 'Сообщить об отсутствии']
			],
			[

				['callback_data' => 'motivate', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(), $res, html, null,null,$keyboard);


//		$bot->sendMessage($chatId, $res,html);
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке

}

	if($data == "dr2"){
$res = "Бочарова Елизавета Анатольевна 17.04.2005
Бородина Ирина Леонидовна 01.05.2005
Яковлева Анастасия Владиславовна 15.05.2004";
$res.="\nДля вывода меню введите /menurole";

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule3', 'text' => 'Расписание', true , true],
				['callback_data' => 'message_get', 'text' => 'Отсутствующие']
				],
				[
				['callback_data' => 'message_set', 'text' => 'Написать сообщение']
				],
				[
				['callback_data' => 'dr2', 'text' => 'ДР класса'],
				['callback_data' => 'motivate2', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(), $res, html, null,null,$keyboard);


//		$bot->sendMessage($chatId, $res,html);
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке

}


	if($data == "shedule3"){
$res = "Расписание учителя
Симакова Юлия Александровна
\xF0\x9F\x93\x8C Понедельник:
1) алгебра, 9в, 322 аудитория 
2) алгебра, 7а, 112 аудитория 
3) алгебра, 9а, 321 аудитория 
\xF0\x9F\x93\x8C Вторник:
1) геометрия, 9в, 322 аудитория 
2) геометрия, 7а, 112 аудитория 
3) алгебра, 9д, 409 аудитория 
\xF0\x9F\x93\x8C Среда:
1) геометрия, 9д, 409 аудитория 
2) геометрия, 9а, 321 аудитория 
3) алгебра, 9в, 322 аудитория 
\xF0\x9F\x93\x8C Пятница:
1) алгебра, 9а, 321 аудитория
2) алгебра, 7а, 112 аудитория 
3) алгебра, 9д, 409 аудитория ";
$res.="\nДля вывода меню введите /menurole";

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule3', 'text' => 'Расписание', true , true],
				['callback_data' => 'message_get', 'text' => 'Отсутствующие']
				],
				[
				['callback_data' => 'message_set', 'text' => 'Написать сообщение']
				],
				[
				['callback_data' => 'dr2', 'text' => 'ДР класса'],
				['callback_data' => 'motivate2', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(), $res, html, null,null,$keyboard);


//		$bot->sendMessage($chatId, $res,html);
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
	}

	
	
	if($data == "promice_type"){
		$data1 = get_data($message->getFrom()->getId()); // получаем массив данных

	foreach ($data1 as $k => $v) {
	$str1.="|".$k.":".$v;
	foreach($v  as  $inner_key => $value)
	        {    $str.=$inner_key."|".$value;
    	        }    }

	            //    echo $str;

		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
	}

//РАСПИСАНИЕ Учителя
	if($data == "shedule1"){
		$data1 = get_shedule($message->getFrom()->getId()); // получаем массив данных
$res = "РАСПИСАНИЕ УЧИТЕЛЯ:\n";

$res.=$data1;
		$bot->sendMessage($chatId, $res,html);
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
	}



//РАСПИСАНИЕ Учеников
	if($data == "shedule2"){
		$data1 = get_shedule($message->getFrom()->getId()); // получаем массив данных
$res = "РАСПИСАНИЕ:\n";

$res.=$data1;
$res.="\nДля вывода меню введите /menurole";


	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule2', 'text' => 'Уроки', true , true],
				['callback_data' => 'duty', 'text' => 'Дежурства'],
				['callback_data' => 'dr', 'text' => 'ДР класса']
			],
			[

				['callback_data' => 'message_set', 'text' => 'Сообщить об отсутствии']
			],
			[

				['callback_data' => 'motivate', 'text' => 'Motivation']
			]

		],
	 true,true);

	$bot->sendMessage($message->getChat()->getId(), $res, html, null,null,$keyboard);
		
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
	}



//ИНФОРМАЦИЯ об отсутствии
	if($data == "message_get"){
		$data1 = get_message($message->getFrom()->getId()); // получаем массив данных
$res = "СООБЩЕНИЯ от учеников:\n";
$res.=$data1;
$res.="\nДля вывода меню введите /menurole";

	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'shedule3', 'text' => 'Расписание', true , true],
				['callback_data' => 'message_get', 'text' => 'Отсутствующие']
				],
				[
				['callback_data' => 'message_set', 'text' => 'Написать сообщение']
				],
				[
				['callback_data' => 'dr2', 'text' => 'ДР класса'],
				['callback_data' => 'motivate2', 'text' => 'Motivation']
			]

		],
	 true,true);
	$bot->sendMessage($message->getChat()->getId(), $res, html, null,null,$keyboard);


//		$bot->sendMessage($chatId, $res,html);
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ОТПРАВКА сообщения
	if($data == "message_set"){
       $chatid = $message->getChat()->getId();
	$cid = $message->getFrom()->getId();
	set_flag($chatid);
//	global $sendmessageflag;
//	$GLOBALS['sendmessageflag'] = 1;
//	$flag=$GLOBALS['sendmessageflag'];
		$bot->sendMessage($message->getChat()->getId(), "Добрый день, напишите сообщение, flag=$chatid");
 

	}


///////////////////////////////////////////////////////////////////////////////////////////////


	if($data == "register_user()"){
//		$data1 = register_user($message->getFrom()->getId()); // получаем массив данных
        	if (is_user_reg($message->getChat()->getId())){
                $bot->sendMessage($chatId,  "<b>РЕГИСТРАЦИЯ</b> статус: \n Вы уже зарегистрированы ",html);
                $bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке


		} else {

		$data1 = register_user($message);
	foreach ($data1 as $k => $v) {
	$str1.="|".$k.":".$v;
	foreach($v  as  $inner_key => $value)
	        {    $str.=$inner_key."|".$value;
    	        }    }
		$bot->sendMessage($chatId,  "<b>РЕГИСТРАЦИЯ</b> статус: \n  ".$data1,html);
		$bot->answerCallbackQuery($callback->getId()); // можно отослать пустое, чтобы просто убрать "часики" на кнопке


		}
	}

}, function($update){
	$callback = $update->getCallbackQuery();
	if (is_null($callback) || !strlen($callback->getData()))
		return false;
	return true;
});









// обработка инлайнов
$bot->inlineQuery(function ($inlineQuery) use ($bot) {
	mb_internal_encoding("UTF-8");
	$qid = $inlineQuery->getId();
	$text = $inlineQuery->getQuery();

	// Это - базовое содержимое сообщения, оно выводится, когда тыкаем на выбранный нами инлайн
	$str = "Что другие?
Свора голодных нищих.
Им все равно...
В этом мире немытом
Душу человеческую
Ухорашивают рублем,
И если преступно здесь быть бандитом,
То не более преступно,
Чем быть королем...
Я слышал, как этот прохвост
Говорил тебе о Гамлете.
Что он в нем смыслит?
<b>Гамлет</b> восстал против лжи,
В которой варился королевский двор.
Но если б теперь он жил,
То был бы бандит и вор.";
	$base = new \TelegramBot\Api\Types\Inline\InputMessageContent\Text($str,"Html");

	// Это список инлайнов
	// инлайн для стихотворения
	$msg = new \TelegramBot\Api\Types\Inline\QueryResult\Article("1","С. Есенин","Отрывок из поэмы `Страна негодяев`");
	$msg->setInputMessageContent($base); // указываем, что в ответ к этому сообщению надо показать стихотворение

	// инлайн для картинки
#	$full = "http://aftamat4ik.ru/wp-content/uploads/2017/05/14277366494961.jpg"; // собственно урл на картинку
#	$thumb = "http://aftamat4ik.ru/wp-content/uploads/2017/05/14277366494961-150x150.jpg"; // и миниятюра

#	$photo = new \TelegramBot\Api\Types\Inline\QueryResult\Photo("2",$full,$thumb);

	// инлайн для музыки
	$url = "http://aftamat4ik.ru/wp-content/uploads/2017/05/mongol-shuudan_-_kozyr-nash-mandat.mp3";
	$mp3 = new \TelegramBot\Api\Types\Inline\QueryResult\Audio("3",$url,"Монгол Шуудан - Козырь наш Мандат!");

	// инлайн для видео
	$vurl = "http://aftamat4ik.ru/wp-content/uploads/2017/05/bb.mp4";
	$thumb = "http://aftamat4ik.ru/wp-content/uploads/2017/05/joker_5-150x150.jpg";
	$video = new \TelegramBot\Api\Types\Inline\QueryResult\Video("4",$vurl,$thumb, "video/mp4","коммунальные службы","тут тоже может быть описание");

	// отправка
	try{
		$result = $bot->answerInlineQuery( $qid, [$msg,$photo,$mp3,$video],100,false);
	}catch(Exception $e){
		file_put_contents("errdata",print_r($e,true));
	}
});

// Reply-Кнопки
$bot->command("buttons", function ($message) use ($bot) {
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup([[["text" => "Test1"], ["text" => "Test2"]]], true, true);

	$bot->sendMessage($message->getChat()->getId(), "тест", false, null,null, $keyboard);
});


// регистрация юзера
$bot->on(function($Update) use ($bot){
	$message = $Update->getMessage();
//	$mtext = $message->getText();
#	$cid = $message->getChat()->getId();
	$cid = $message->getFrom()->getId();
#       $usern = '@'.$message->getFrom()->getUsername();
#       $usern = $message->getFrom()->getLastname().' '.$message->getFrom()->getFirstname();
#       $usern = $message->getFrom()->getId();
$mtext = $message->getText();
//$mtext = "gewrgewrgerg";
//global $sendmessageflag;
///if ($data == "message_set")

//////////////////////////////////////////////////////////////////////////////////////////////////////
//ОТПРАВКА В БД

if (get_flag($cid) > 0)
{
$res=set_message($mtext); // сохраняем изменения
//$flag=$GLOBALS['sendmessageflag'];
$bot->sendMessage($message->getChat()->getId(), "Данные в БД были записаны. Вы ввели: $mtext,flag: $cid");
del_flag($cid);
//$GLOBALS['sendmessageflag'] =  0;
}



//////////////////////////////////////////////////////////////////////////////////////////////////////


/*
#	if(is_user_set($message->getFrom()->getUsername()) == false){
#		make_user($message->getFrom()->getUsername(),$cid);
	if(is_user_set($message->getFrom()->getId()) == false){
		make_user($message->getFrom()->getId(),$cid);
	}

	/*-// сохранение тестовых данных
	$data = array( "prevmsg" => $mtext );
	set_udata($message->getFrom()->getUsername(), $data);

	// тест получения данных
	$data = get_udata($message->getFrom()->getUsername());
	$bot->sendMessage($message->getChat()->getId(), json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));*-/


#	$data = get_udata($message->getFrom()->getUsername()); // получаем массив данных
	$data = get_udata($message->getFrom()->getId()); // получаем массив данных
	if(!isset($data["mode"])){ // если в нем нет режима - значит человек еще не взаимодействовал с этой командой
		$mode = "name"; // поэтому задаем ему действие по дефолту
	}else{
		$mode = $data["mode"];
	}

	if($mtext == "/dbact"){
		// по команде /dbact запускаем цепочку
		if($mode == "name"){
			$bot->sendMessage($message->getChat()->getId(), "Добрый день, укажите, пожалуйста, ваше имя");
			$data["mode"] = "aftername";
#			set_udata($message->getFrom()->getUsername(), $data); // сохраняем изменения
			set_udata($message->getFrom()->getId(), $data); // сохраняем изменения
		}

	}
	if($mode == "aftername"){
		// помещаем имя в массив данных
		$data["name"] = $message->getText(); // очевидно, что после запроса имени пользователь отправит следюущей командой свое имя, то есть оно будет в тексте сообщения.
		$bot->sendMessage($message->getChat()->getId(), "Добрый день, укажите ваш сайт");
		$data["mode"] = "website";
#		set_udata($message->getFrom()->getUsername(), $data); // сохраняем изменения
		set_udata($message->getFrom()->getId(), $data); // сохраняем изменения
	}
	if($mode == "website"){
		$data["website"] = $message->getText(); // очевидно, что после запроса сайта пользователь отправит следюущей командой свой сайт, то есть адрес будет в тексте сообщения.
		$bot->sendMessage($message->getChat()->getId(), "спасибо.");
		$data["mode"] = "done";
#		set_udata($message->getFrom()->getUsername(), $data); // сохраняем изменения
		set_udata($message->getFrom()->getId(), $data); // сохраняем изменения
	}

	if($mode == "done1"){
		// если человек уже прошел опрос - выводим ему собранную у него-же информацию
		$bot->sendMessage($message->getChat()->getId(), "Вы уже проходили опрос и указали такие данные:\nИмя - ".$data["name"]."\nсайт - ".$data["website"]);
	}
*/
}, function($message) use ($name){
	return true; // когда тут true - команда проходит
});
/*
// Отлов любых сообщений + обрабтка reply-кнопок
$bot->on(function($Update) use ($bot){



	/* обработка постов из канала
	$cpost = $Update->getChannelPost();
	if($cpost){
		// текст
		$text = $cpost->getText();
		// фотки
		$photo = $cpost->getPhoto();
		if($photo){
			$photo_id = $photo[0]->getFileId();
			$file = $bot->getFile($photo_id);
			$furl = $bot->getFileUrl().'/'.$file->getFilePath();
			file_put_contents(basename($furl), file_get_contents( $furl ) );
		}
		file_put_contents("lastmsg",$text);
	}*-/
	// все что ниже - не нужно(внашем случае)!
	//file_put_contents("mtext",$bot->getRawBody()); - получение всего json ответа
	$message = $Update->getMessage();
	$mtext = $message->getText();
	$cid = $message->getChat()->getId();

	// array of https://github.com/TelegramBot/Api/blob/master/src/Types/PhotoSize.php
	$photos = $message->getPhoto();
	if(!empty($photos)) foreach($photos as $ph){
		$fileId = $ph->getFileId();
		$data = $bot->downloadFile($fileId);
		file_put_contents("file.jpg",$data);
		$bot->sendMessage($message->getChat()->getId(), "Файл загружен");
	}

	//if(mb_stripos($mtext,"Сиськи") !== false){
	//	$pic = "http://aftamat4ik.ru/wp-content/uploads/2017/05/14277366494961.jpg";

	//	$bot->sendPhoto($message->getChat()->getId(), $pic);
	//}
	if(mb_stripos($mtext,"Test1") !== false){
		$bot->sendMessage($message->getChat()->getId(), "Вы нажали кнопку Test1");
	}
}, function($message) use ($name){
	return true; // когда тут true - команда проходит
});
*/
// запускаем обработку
$bot->run();

echo "бот";
