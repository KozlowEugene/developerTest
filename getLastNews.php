<?
$url = 'https://lenta.ru/rss';
$xml = simplexml_load_file($url);

if ($xml) {
	$count = 5;
	foreach ($xml->channel->xpath('//item') as $item) {
		echo $item->title, PHP_EOL, $item->link, PHP_EOL, $item->description, PHP_EOL, PHP_EOL;
		if (--$count <= 0) {
			break;
		}
	}
} else {
	echo 'Не удалось загрузить ленту';
}
