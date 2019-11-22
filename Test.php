<?require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
use \Bitrix\Main\Data\Cache;

Class Test
{
	public function __construct() {
		if (!Bitrix\Main\Loader::includeModule('iblock')) {
			ShowError('Не подключен модуль iblock');
		}
	}

	public function getItems($params = []) {
		$sort = $params['SORT'] ? : [];
		$filter = $params['FILTER'] ? : [];
		$select = $params['SELECT'] ? : [];
		$cache_time = (int)$params['CACHE_TIME'] ? : 3600;

		$arResult = [];
		$cache_id = serialize($params);
		$obCache = Cache::createInstance();

		if ($obCache->InitCache($cache_time, $cache_id, '/')) {
			$vars = $obCache->GetVars();
			$arResult = $vars['arResult'];
		} elseif ($obCache->StartDataCache()) {
			$rsFields = CIBlockElement::GetList($sort, $filter, false, false, $select);
			while ($arFields = $rsFields->Fetch()) {
				$arResult[] = $arFields;
			}

			$obCache->EndDataCache([
				'arResult' => $arResult,
			]);
		}
		return $arResult;
	}
}

$test = new Test();

$arItems = $test->getItems([
	'CACHE_TIME' => 10000,
	'FILTER' => [
		'IBLOCK_ID' => 31,
	],
	'SELECT' => [
		'NAME'
	],
]);

echo '<pre>' . print_r($arItems, true) . '</pre>';
