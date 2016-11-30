<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals;

defined("B_PROLOG_INCLUDED") and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\{Loader, EventManager, Config\Option};

EventManager::getInstance()->addEventHandler("main", "OnEpilog", function () {
	if (\CSite::InDir("/bitrix/")) {
		return;
	}

	$GLOBALS["APPLICATION"]->AddHeadString(Option::get("rodzeta.yandexmetricgoals", "yandex_metrika_code"), true);
	$GLOBALS["APPLICATION"]->AddHeadString(Option::get("rodzeta.yandexmetricgoals", "google_analytics_code"), true);

	if (is_readable($_SERVER["DOCUMENT_ROOT"] . Utils::CACHE_NAME)) {
		$GLOBALS["APPLICATION"]->AddHeadScript(Utils::CACHE_NAME);
	}
});
