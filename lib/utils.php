<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals;

use \Bitrix\Main\Config\Option;

final class Utils {

	const CACHE_NAME = "/upload/cache.rodzeta.yandexmetricgoals.js";
	const SRC_NAME = "/upload/rodzeta.yandexmetricgoals.csv";

	static function getTargetsFromCsv() {
		$basePath = $_SERVER["DOCUMENT_ROOT"];
		$targets = array();
		if (!file_exists($basePath . self::SRC_NAME)) {
			return $targets;
		}
		$fcsv = fopen($basePath . self::SRC_NAME, "r");
		if ($fcsv === false) {
			return $targets;
		}
		while (($row = fgetcsv($fcsv, 4000, "\t")) !== false) {
			$targets[] = array_map("trim", $row);
		}
		fclose($fcsv);
		return $targets;
	}

	static function saveToCsv($targets) {
		$basePath = $_SERVER["DOCUMENT_ROOT"];
		$fcsv = fopen($basePath . self::SRC_NAME, "w");
		if ($fcsv === false) {
			return;
		}
		foreach ($targets as $row) {
			$row = array_map("trim", $row);
			if (count(array_filter($row)) == 0) {
				continue;
			}
			fputcsv($fcsv, $row, "\t");
		}
		fclose($fcsv);
	}

	static function createCache() {
		$basePath = $_SERVER["DOCUMENT_ROOT"];
		$counterId = trim(Option::get("rodzeta.yandexmetricgoals", "yandex_metrika_id"));
		$counterIdGoogleAnalytics = trim(Option::get("rodzeta.yandexmetricgoals", "google_analytics_id"));
		$targets = array();
		foreach (self::getTargetsFromCsv() as $row) {
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
			$basePath . self::CACHE_NAME,
			count($targets)?
				('BX.ready(function () { ' . implode("\n", $targets)	. ' });') : ""
		);
	}

	static function clearCache() {
		if (file_exists($_SERVER["DOCUMENT_ROOT"] . self::CACHE_NAME)) {
			unlink($_SERVER["DOCUMENT_ROOT"] . self::CACHE_NAME);
		}
	}

}
