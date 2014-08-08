<?php
date_default_timezone_set('Europe/Moscow');
$UserMessage = 'Артём';
//Создаем объект представления интерфейса
$Veiw = new View;
//Устанавливаем шаблон представления
$Veiw->SetTemplate('tmpltest.php');
//Установка значения переменной-маркера сообщения пользователю



$toTemplate = array(
    'UserMessage' => $UserMessage
);
$Veiw->AssignVar($toTemplate);
//Отображение интерфейса
$Veiw->Display();