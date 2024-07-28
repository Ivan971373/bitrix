if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => "Мой компонент",
    "DESCRIPTION" => "Простой компонент для вывода каталога товаров",
    "PATH" => [
        "ID" => "simplecomp",
        "CHILD" => [
            "ID" => "catalog",
            "NAME" => "Каталог товаров"
        ]
    ]
];