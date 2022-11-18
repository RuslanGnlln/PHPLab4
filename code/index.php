<form method="POST">
	<label> email
		<input type="email" name="email">
	</label><br>

	<label> Категория<Br>
		<input type="radio" name="category" value="Продажа"> Продажа<Br>
		<input type="radio" name="category" value="Покупка"> Покупка<Br>
		<input type="radio" name="category" value="Фестивали и ярмарки"> Фестивали и ярмарки<Br>
		<input type="radio" name="category" value="Реклама"> Реклама<Br>
		<input type="radio" name="category" value="Жалоба"> Жалоба<Br>
	</label><br>

	<label> Заголовок
		<input type="text" name="header">
	</label><br>

	<label> Текст объявления
<textarea name="textarea"></textarea>
	</label><br>

	<input type="submit" name="send" value="Добавить">
</form>
<a href="https://docs.google.com/spreadsheets/d/1NFHT7my3W2BloH6DGVhIMbbFG63qaFuIRVuyA_Wbi78/edit#gid=0">Таблица</a>

<?php
// Подключаем клиент Google таблиц
require_once 'google-api/vendor/autoload.php';

// Наш ключ доступа к сервисному аккаунту
$googleAccountKeyFilePath = 'service-key.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);

// Создаем новый клиент
$client = new Google_Client();
// Устанавливаем полномочия
$client->useApplicationDefaultCredentials();

// Добавляем область доступа к чтению, редактированию, созданию и удалению таблиц
$client->addScope(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/spreadsheets']);

$service = new Google_Service_Sheets($client);

// ID таблицы
$spreadsheetId = '1NFHT7my3W2BloH6DGVhIMbbFG63qaFuIRVuyA_Wbi78';

$range = 'A1:Z';

if($_POST['send'] && !empty($_POST['email']) && !empty($_POST['category']) && !empty($_POST['header']) && $_POST['textarea'])
{
	$email = $_POST['email'];
	$category = $_POST['category'];
	$header = $_POST['header'];
	$textarea = $_POST['textarea'];

	$values = [
		[$category, $header, $textarea, $email],
	];

	// Объект - диапазон значений
	$ValueRange = new Google_Service_Sheets_ValueRange();
	// Устанавливаем наши данные
	$ValueRange->setValues($values);
	// Указываем в опциях обрабатывать пользовательские данные
	$options = ['valueInputOption' => 'USER_ENTERED'];
	// Добавляем наши значения в последнюю строку
	$service->spreadsheets_values->append($spreadsheetId, $range, $ValueRange, $options);
}