if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;

class SimpleCatalogComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        $params['CACHE_TIME'] = $params['CACHE_TIME'] ?? 36000000;
        return $params;
    }

    public function executeComponent()
    {
        if ($this->startResultCache(false, [])) {
            if (!Loader::includeModule("iblock")) {
                $this->abortResultCache();
                ShowError("Модуль Информационных блоков не установлен");
                return;
            }

            $this->arResult['ITEMS'] = [];
            $this->arResult['SECTIONS'] = [];
            $this->arResult['NEWS'] = [];

            $sections = CIBlockSection::GetList(
                [],
                ['IBLOCK_ID' => $this->arParams['IBLOCK_ID_CATALOG'], 'ACTIVE' => 'Y'],
                false,
                ['ID', 'NAME', 'UF_NEWS_LINK']
            );

            while ($section = $sections->Fetch()) {
                $this->arResult['SECTIONS'][$section['ID']] = $section;
                $newsIds = unserialize($section['UF_NEWS_LINK']);
                if ($newsIds) {
                    $this->arResult['NEWS'] = array_merge($this->arResult['NEWS'], $newsIds);
                }
            }

            $elements = CIBlockElement::GetList(
                [],
                ['IBLOCK_ID' => $this->arParams['IBLOCK_ID_CATALOG'], 'ACTIVE' => 'Y'],
                false,
                false,
                ['ID', 'NAME', 'IBLOCK_SECTION_ID']
            );

            while ($element = $elements->Fetch()) {
                $this->arResult['ITEMS'][$element['IBLOCK_SECTION_ID']][] = $element;
            }

            $this->includeComponentTemplate();
        }
    }
}

$APPLICATION->SetTitle("В каталоге товаров представлено товаров: " . count($this->arResult['ITEMS']))