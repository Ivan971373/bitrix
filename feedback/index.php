<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Форма обратной связи");
?>
<?

\Bitrix\Main\EventManager::getInstance()->addEventHandler('main','OnBeforeEventSend', "Listen");
function Listen(&$arFields, &$arTemplate){
    global $USER;
    if ($USER->IsAuthorized()) {
        $arFields["AUTHOR"] = "Пользователь авторизован: ".$USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFirstName() . " " . $USER->GetLastName() .", данные из формы: " . $arFields["AUTHOR"];
    } else {
        $arFields["AUTHOR"] = "Пользователь не авторизован, данные из формы: " . $arFields["AUTHOR"];
    }
    CEventLog::Add(
        Array(
            "SEVERITY" => "SECURITY",
            "AUDIT_TYPE_ID" => "FEEDBACK_FORM",
            "MODULE_ID" => "feedback",
            "DESCRIPTION" => "Замена данных в отсылаемом письме – " . $arFields["AUTHOR"],
        )
    );
}

$APPLICATION->IncludeComponent('bitrix:main.feedback','',Array(
    "USE_CAPTCHA" => "N",
    "EVENT_NAME" => "FEEDBACK_FORM",
    "EMAIL_TO" => "nonexistingadmin@mail.com",
    "REQUIRED_FIELDS" => Array(
        "NAME",
        "EMAIL",
        "MESSAGE",
    ),
))?>;
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>