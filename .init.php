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
define(__NAMESPACE__ . "\FILE_OPTIONS", $_SERVER["DOCUMENT_ROOT"] . "/upload/" . $_SERVER["SERVER_NAME"] . "/." . ID . "/default.php");

require LIB . "encoding/php-array.php";

function StorageInit() {
	$path = dirname(FILE_OPTIONS);
	if (!is_dir($path)) {
		mkdir($path, 0700, true);
	}
	if (!file_exists(FILE_OPTIONS)) {
		copy(__DIR__ . "/install/data/default.php", FILE_OPTIONS);
	}
}

function Update($key, $data) {
	return;
	//...

	\Encoding\PhpArray\Write(FILE_OPTIONS, $options);
}

function Select($key) {
	return is_readable(FILE_OPTIONS)? include FILE_OPTIONS : [];
}

function AppendValues($data, $n, $v) {
	yield from $data;
	for ($i = 0; $i < $n; $i++) {
		yield  $v;
	}
}
