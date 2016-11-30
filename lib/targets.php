<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals\Targets;

use const \Rodzeta\Yandexmetricgoals\{FILE_OPTIONS, FILE_JS};

function Update($data) {
	$targetsData = [];
	foreach ($data["analytics_targets"] as $row) {
		$row = array_map("trim", $row);
		if (count(array_filter($row))) {
			$targetsData[] = $row;
		}
	}
	CreateCache($targetsData);
	\Encoding\PhpArray\Write(FILE_OPTIONS . "/targets.php", $targetsData);
}

function Select() {
	$fname = FILE_OPTIONS . "/targets.php";
	return is_readable($fname)? include $fname : [];
}

function CreateCache($targetsData) {
	$options = \Rodzeta\Yandexmetricgoals\Options\Select();
	$counterId = trim($options["yandex_metrika_id"]);
	$counterIdGoogleAnalytics = trim($options["google_analytics_id"]);
	$targets = [];
	foreach ($targetsData as $row) {
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
		$_SERVER["DOCUMENT_ROOT"] . FILE_JS,
		count($targets)?
			('BX.ready(function () { ' . implode("\n", $targets)	. ' });') : ""
	);
}