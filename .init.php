<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals;

define(__NAMESPACE__ . "\ID", "rodzeta.yandexmetricgoals");
define(__NAMESPACE__ . "\URL_ADMIN", "/bitrix/admin/" . ID . "/");
define(__NAMESPACE__ . "\APP", __DIR__ . "/");
define(__NAMESPACE__ . "\LIB", __DIR__  . "/lib/");
define(__NAMESPACE__ . "\FILE_OPTIONS", $_SERVER["DOCUMENT_ROOT"] . "/upload/" . $_SERVER["SERVER_NAME"] . "/." . ID);

require LIB . "encoding/php-array.php";
require LIB . "options.php";
require LIB . "targets.php";

function StorageInit() {
	$path = dirname(FILE_OPTIONS);
	if (!is_dir($path)) {
		mkdir($path, 0700, true);
	}
}

function AppendValues($data, $n, $v) {
	yield from $data;
	for ($i = 0; $i < $n; $i++) {
		yield  $v;
	}
}
