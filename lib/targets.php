<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals\Targets;

use const \Rodzeta\Yandexmetricgoals\FILE_OPTIONS;

function Update($data) {
	$options = $data["analytics_targets"];
	\Encoding\PhpArray\Write(FILE_OPTIONS . "/targets.php", $options);
}

function Select() {
	$fname = FILE_OPTIONS . "/targets.php";
	return is_readable($fname)? include $fname : [];
}

function CreateCache($targets) {
	return;

	$basePath = $_SERVER["DOCUMENT_ROOT"];
	$counterId = trim(Option::get("rodzeta.yandexmetricgoals", "yandex_metrika_id"));
	$counterIdGoogleAnalytics = trim(Option::get("rodzeta.yandexmetricgoals", "google_analytics_id"));
	$targets = array();
	foreach ($targets as $row) {
		if ($counterId != "" || $counterIdGoogleAnalytics != "") {
			$event = $row[2];
			$selector = addslashes($row[0]);
			$sendTargetCode = "";

			// Яндекс.Метрика js-code
			if ($counterId != "" && !empty($row[1])) {
				$sendTargetCode .= '
							if (typeof yaCounter' . $counterId . ' != "undefined") {
								yaCounter' . $counterId . '.reachGoal("' . $row[1] . '");
							}
				';
			}

			// Google Analytics js-code
			if ($counterIdGoogleAnalytics != "" && !empty($row[3]) && !empty($row[4])) {
				$sendTargetCode .= '
							if (typeof ga != "undefined") {
								ga("send", "event", "' . $row[3] . '", "' . $row[4] . '");
							}
				';
			}
			if (trim($sendTargetCode) == "") {
				continue;
			}

			// event bind js-code
			if ($event == "ready") {
				$targets[] = '
					if (document.querySelector("' . $selector . '")) {
						' . $sendTargetCode . '
					}
				';
			} else {
				$targets[] = '
					BX.bind(
						document.querySelector("' . $selector . '"),
						"' . $event . '",
						function () {
							' . $sendTargetCode . '
						}
					);
				';
			}
		}
	}

	file_put_contents(
		$basePath . CACHE_NAME,
		count($targets)?
			('BX.ready(function () { ' . implode("\n", $targets)	. ' });') : ""
	);
}