<?php
/** модель работы с пользователями **/

//Так помечены процедуры, нужные для бота: 

//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА


function make_user($name,$chat_id){
	global $db;
	$name = mysql_real_escape_string($name);
	$chat_id = mysql_real_escape_string($chat_id);
	$query = "insert into `users`(name,chat_id) values('{$name}','{$chat_id}')";
	mysql_query($query,$db) or die("пользователя создать не удалось");
}

function is_user_set($name){
	global $db;
	$name = mysql_real_escape_string($name);
	$result = mysql_query("select * from `users` where name='$name' LIMIT 1",$db);

    if(mysql_fetch_array($result) !== false) return true;
    return false;
}

// задание настройки
function set_udata($name,$data = array()){
	global $db;
	$name = mysql_real_escape_string($name);
	if(!is_user_set($name)){
		make_user($name,0); // если каким-то чудом этот пользователь не зарегистрирован в базе
	}
	$data = json_encode($data,JSON_UNESCAPED_UNICODE);
	mysql_query("update `users` SET data_json = '{$data}' WHERE name = '{$name}'",$db); // обновляем запись в базе
}



// считываение настройки
function is_user_reg($name){
	global $db;
	$res = FALSE;
	$name = mysql_real_escape_string($name);
	$result = mysql_query("select * from `user` where IdUser='$name'",$db);
	$arr = mysql_fetch_assoc($result);
    if(isset($arr['IdUser'])){
		$res = TRUE;
	}
	
	return $res;
}


// Пользователь уже зарегистрирован?
function get_data($name){
        global $db;
        $res = array();
        $name = mysql_real_escape_string($name);
        $result = mysql_query("select * from `users` where name='$name'",$db);
        $arr = mysql_fetch_assoc($result);
    if(isset($arr['data_json'])){
                $res = json_decode($arr['data_json'], true);
        }

        return $res;
}
//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
//Получаем расписание за конкретный день (Передается в параметре $day)
function get_shedule_day($day){
        global $db;
        $res = array();
//        $name = mysql_real_escape_string($name);
        $result = mysql_query("select * from `schedule` where weekday='$day'",$db);
	$arr="\xF0\x9F\x93\x8C".mb_strtoupper($day)."\n";
	$n=0;
	while ($row = mysql_fetch_assoc($result)) {
	    $arr.=$row['n'].")".$row['lesson_name']."\n";
	    $n=$n+1;
        }
	if ($n < 1) $arr.="Расписание на день отсутствует\n";
	    $arr.="\n";
        return $arr;
}



//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
//Получаем мотиватор
function get_motivate(){
        global $db;
        $res = array();
        $name = mysql_real_escape_string($name);
        $result = mysql_query("select * from `motivation` ORDER BY RAND() LIMIT 1",$db);
//	$arr="\xF0\x9F\x93\x8C<b><u>".mb_strtoupper($day)."</u></b>\n";
//	$n=0;
	while ($row = mysql_fetch_assoc($result)) {
	    $arr.=$row['text'];
//	    $n=$n+1;
        }
//	if ($n < 1) $arr.="Расписание на день отсутствует\n";
//	    $arr.="\n";
//$arr=123;
        return $arr;
}

//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
//Получаем сообщение
function get_message(){
        global $db;
        $res = array();
        $name = mysql_real_escape_string($name);
        $result = mysql_query("select * from `messages`",$db);
	while ($row = mysql_fetch_assoc($result)) {
	    $arr.=$row['text']."\n";
        }
        return $arr;
}


//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
//отправляем сообщение
function set_message($name1){
        global $db;
        $res = array();

        $name = mysql_real_escape_string($name1);
//$name="test";
	$query = "insert into `messages`(text) values('{$name}')";
        mysql_query($query,$db) or die("Пользователя создать не удалось");

//        $result = mysql_query("select * from `messages`",$db);
//	while ($row = mysql_fetch_assoc($result)) {
//	    $arr.=$row['text'];
//        }
        return $arr;
}

//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
//Получаем флаг возможности отправки сообщения
function get_flag($name1){
        global $db;
        $res = array();
        $n = 0;
        $name = mysql_real_escape_string($name1);
        $result = mysql_query("select * from `flag` where name='$name1'",$db);
	while ($row = mysql_fetch_assoc($result)) {
	    $arr.=$row['name']."\n";
	    $n=$n+1;
        }
        return $n;
}


//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
//устанавливаем флаг отправки сообщения
function set_flag($name1){
        global $db;
        $res = array();

        $name = mysql_real_escape_string($name1);
	$query = "insert into `flag`(name) values('{$name}')";


//$name="test";
        mysql_query($query,$db) or die("Пользователя создать не удалось");

//        $result = mysql_query("select * from `messages`",$db);
//	while ($row = mysql_fetch_assoc($result)) {
//	    $arr.=$row['text'];
//        }
        return $arr;
}

//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
//Удаляем флаг отправки сообщения
function del_flag($name1){
        global $db;
        $res = array();

        $name = mysql_real_escape_string($name1);
//$name="test";
	$query = "DELETE from `flag` where name = '{$name}'";
	
	//DELETE FROM `alexey_dvfubot`.`messages` WHERE `messages`.`text` = 'ототрттт'

        mysql_query($query,$db) or die("Пользователя создать не удалось");

//        $result = mysql_query("select * from `messages`",$db);
//	while ($row = mysql_fetch_assoc($result)) {
//	    $arr.=$row['text'];
//        }
        return $arr;
}


//////////////////////////////////////////////////////////////////////
//ИСПОЛЬЗУЕТСЯ ДЛЯ ДВФУ БОТА
// Получение расписания
function get_shedule($name){
//Получаем расписание за каждый день
$arr=get_shedule_day("Понедельник");
$arr.=get_shedule_day("Вторник");
$arr.=get_shedule_day("Среда");
$arr.=get_shedule_day("Четверг");
$arr.=get_shedule_day("Пятница");
$arr.=get_shedule_day("Суббота");

//Расписание звонков
$arr.="\xF0\x9F\x95\x97<b><u>ЗВОНКИ</u></b>
 1)8:30-10:00
 2)10:10-11:40
 3)12:00-13:30
 4)13:50-15:20";
return $arr;
}


// Регистрация 
function register_user($message){

//register_user($message->getFrom()->getId());
	global $db;
     	$lastname = $message->getChat()->getLastname();
	$firstname = $message->getChat()->getFirstname();
//.' '.$message->getChat()->getFirstname();
         //Мой ИД: 257902696
       	$usern = $message->getChat()->getUsername();
       	$chatid = $message->getChat()->getId();
       	$lastname = mysql_real_escape_string($lastname);
	$firstname = mysql_real_escape_string($firstname);
	$usern = mysql_real_escape_string($usern);
	$chatid = mysql_real_escape_string($chatid);
	$query = "insert into `user`(FirstName,IdUser,Lastname, TGUserName) values('{$firstname}','{$chatid}','{$lastname}','{$usern}')";
        mysql_query($query,$db) or die("Пользователя создать не удалось");
	//$res = array();
	$res='';
	$name = mysql_real_escape_string($message->getFrom()->getId());
	$result = mysql_query("SELECT * FROM  `promicetype` WHERE 1 ",$db);
	while($arr = mysql_fetch_assoc($result)):
	
	//$res = implode('', $arr);
	$res.="\n".$arr['PromiceTypeDescription'];
$res='Вы успешно зарегистрированы в системе. Ваши данные:'."\n".'Username:'.$usern."\n".'Фамилия:'.$lastname."\n".'Имя:'.$firstname."\n";
//$res.=print_r($message, true);
//$res='OK';	
	endwhile;

	
	
	
//while ($row = mysql_fetch_assoc($result)) {
//    echo $row["userid"];
//    echo $row["fullname"];
//    echo $row["userstatus"];
//    }
	
	//$res=$arr['PromiceTypeDescription'];
	//$res=$arr;
	return $res;
}
