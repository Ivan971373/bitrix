if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$newsList = [];

$newsFilter = [
    'IBLOCK_ID' => $arParams['IBLOCK_ID_NEWS'],
    'ID' => $arResult['NEWS'],
    'ACTIVE' => 'Y'
];

$newsSelect = [
    'ID',
    'NAME',
    'ACTIVE_FROM'
];

$newsResult = CIBlockElement::GetList([], $newsFilter, false, false, $newsSelect);

while ($news = $newsResult->Fetch()) {
    $newsList[$news['ID']] = $news;
}
?>

<div class="catalog">
    <?php foreach ($arResult['SECTIONS'] as $section): ?>
        <h2><?= $section['NAME'] ?></h2>
        <?php if (!empty($section['UF_NEWS_LINK'])): ?>
            <div class="news">
                <h3>Новости:</h3>
                <ul>
                    <?php foreach ($section['UF_NEWS_LINK'] as $newsId): ?>
                        <li><?= $newsList[$newsId]['ACTIVE_FROM'] ?> - <?= $newsList[$newsId]['NAME'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="items">
            <h3>Товары:</h3>
            <ul>
                <?php foreach ($arResult['ITEMS'][$section['ID']] as $item): ?>
                    <li><?= $item['NAME'] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>