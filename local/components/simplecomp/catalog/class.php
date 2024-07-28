if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;

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

            $sections = SectionTable::getList([
                'filter' => ['IBLOCK_ID' => $this->arParams['IBLOCK_ID']],
                'select' => ['ID', 'NAME', 'UF_NEWS_LINK']
            ])->fetchAll();

            foreach ($sections as $section) {
                $this->arResult['SECTIONS'][$section['ID']] = $section;
                $newsIds = unserialize($section['UF_NEWS_LINK']);
                if ($newsIds) {
                    $this->arResult['NEWS'] = array_merge($this->arResult['NEWS'], $newsIds);
                }
            }

            $elements = ElementTable::getList([
                'filter' => ['IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'],
                'select' => ['ID', 'NAME', 'IBLOCK_SECTION_ID']
            ])->fetchAll();

            foreach ($elements as $element) {
                $this->arResult['ITEMS'][$element['IBLOCK_SECTION_ID']][] = $element;
            }

            $this->includeComponentTemplate();
        }
    }
}