<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals;

defined("B_PROLOG_INCLUDED") and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\{Loader, EventManager};

require __DIR__ . "/.init.php";

EventManager::getInstance()->addEventHandler("main", "OnPanelCreate", function () {
	// TODO заменить на определение доступа к редактированию конента
	if (!$GLOBALS["USER"]->IsAdmin()) {
	  return;
	}

	$link = "javascript:" . $GLOBALS["APPLICATION"]->GetPopupLink([
		"URL" => URL_ADMIN,
		"PARAMS" => [
			"resizable" => true,
			//"width" => 780,
			//"height" => 570,
			//"min_width" => 400,
			//"min_height" => 200,
			"buttons" => "[BX.CDialog.prototype.btnClose]"
		]
	]);
  $GLOBALS["APPLICATION"]->AddPanelButton([
		"HREF" => $link,
		"ICON"  => "bx-panel-site-structure-icon",
		//"SRC" => URL_ADMIN . "/icon.gif",
		"TEXT"  => "Настройки счетчиков метрики",
		"ALT" => "Настройки счетчиков Яндекс.Метрика и Google Analytics",
		"MAIN_SORT" => 2000,
		"SORT"      => 20
	]);

	$link = "javascript:" . $GLOBALS["APPLICATION"]->GetPopupLink([
		"URL" => URL_ADMIN . "targets/",
		"PARAMS" => [
			"resizable" => true,
			//"width" => 780,
			//"height" => 570,
			//"min_width" => 400,
			//"min_height" => 200,
			"buttons" => "[BX.CDialog.prototype.btnClose]"
		]
	]);
	$GLOBALS["APPLICATION"]->AddPanelButton([
		"HREF" => $link,
		"ICON"  => "bx-panel-site-structure-icon",
		//"SRC" => URL_ADMIN . "/icon.gif",
		"TEXT"  => "Настройка целей",
		"ALT" => "Настройка целей Яндекс.Метрика и Google Analytics",
		"MAIN_SORT" => 2000,
		"SORT"      => 30
	]);
});

EventManager::getInstance()->addEventHandler("main", "OnEpilog", function () {
	if (\CSite::InDir("/bitrix/")) {
		return;
	}

	$options = Options\Select();
	$GLOBALS["APPLICATION"]->AddHeadString($options["yandex_metrika_code"], true);
	$GLOBALS["APPLICATION"]->AddHeadString($options["google_analytics_code"], true);

	if (is_readable(FILE_JS)) {
		$GLOBALS["APPLICATION"]->AddHeadScript(FILE_JS);
	}
});
