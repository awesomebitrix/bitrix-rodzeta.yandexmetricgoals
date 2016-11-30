<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals\Options;

function Update($data) {
	$options = [
		"yandex_metrika_id" => $data["yandex_metrika_id"],
		"yandex_metrika_code" => $data["yandex_metrika_code"],
		"google_analytics_id" => $data["google_analytics_id"],
		"google_analytics_code" => $data["google_analytics_code"],
	];
	\Encoding\PhpArray\Write(
		\Rodzeta\Yandexmetricgoals\FILE_OPTIONS . "/options.php",
		$options
	);
}

function Select() {
	$fname = \Rodzeta\Yandexmetricgoals\FILE_OPTIONS . "/options.php";
	return is_readable($fname)? include $fname : [];
}
