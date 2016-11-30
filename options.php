<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

if (!$USER->isAdmin()) {
	$APPLICATION->authForm("ACCESS DENIED");
}

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages(__FILE__);

$tabControl = new CAdminTabControl("tabControl", array(
  array(
		"DIV" => "edit1",
		"TAB" => Loc::getMessage("RODZETA_YANDEXMETRICGOALS_MAIN_TAB_SET"),
		"TITLE" => Loc::getMessage("RODZETA_YANDEXMETRICGOALS_MAIN_TAB_TITLE_SET"),
  ),
  array(
		"DIV" => "edit2",
		"TAB" => Loc::getMessage("RODZETA_YANDEXMETRICGOALS_DATA_TAB_SET"),
		"TITLE" => Loc::getMessage("RODZETA_YANDEXMETRICGOALS_DATA_TAB_TITLE_SET", array(
			"#FILE#" => \Rodzeta\Yandexmetricgoals\Utils::SRC_NAME)
		),
  ),
));

?>

<?php

if ($request->isPost() && check_bitrix_sessid()) {
	if (!empty($save) || !empty($restore)) {
		Option::set("rodzeta.yandexmetricgoals", "yandex_metrika_code", $request->getPost("yandex_metrika_code"));
		Option::set("rodzeta.yandexmetricgoals", "yandex_metrika_id", $request->getPost("yandex_metrika_id"));
		Option::set("rodzeta.yandexmetricgoals", "google_analytics_code", $request->getPost("google_analytics_code"));
		Option::set("rodzeta.yandexmetricgoals", "google_analytics_id", $request->getPost("google_analytics_id"));

		\Rodzeta\Yandexmetricgoals\Utils::saveToCsv($request->getPost("analytics_targets"));
		\Rodzeta\Yandexmetricgoals\Utils::createCache();

		CAdminMessage::showMessage(array(
	    "MESSAGE" => Loc::getMessage("RODZETA_YANDEXMETRICGOALS_OPTIONS_SAVED"),
	    "TYPE" => "OK",
	  ));
	}	else if ($request->getPost("clear") != "") {
		\Rodzeta\Yandexmetricgoals\Utils::clearCache();

		CAdminMessage::showMessage(array(
	    "MESSAGE" => Loc::getMessage("RODZETA_YANDEXMETRICGOALS_OPTIONS_RESETED"),
	    "TYPE" => "OK",
	  ));
	}
}

$tabControl->begin();

?>

<form method="post" action="<?= sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID) ?> type="get">
	<?= bitrix_sessid_post() ?>

	<?php $tabControl->beginNextTab() ?>

	<tr class="heading">
		<td colspan="2">Настройки для Яндекс.Метрика</td>
	</tr>

	<tr>
		<td class="adm-detail-content-cell-l" width="50%">
			<label>ID счетчика Яндекс.Метрика</label>
		</td>
		<td class="adm-detail-content-cell-r" width="50%">
			<input type="text" size="30" name="yandex_metrika_id"
				value="<?= Option::get("rodzeta.yandexmetricgoals", "yandex_metrika_id") ?>" ?>
		</td>
	</tr>

	<tr>
		<td class="adm-detail-content-cell-l" width="50%">
			<label>Код счетчика</label>
		</td>
		<td class="adm-detail-content-cell-r" width="50%">
			<textarea name="yandex_metrika_code" rows="10" cols="60"
				><?= Option::get("rodzeta.yandexmetricgoals", "yandex_metrika_code") ?></textarea>
		</td>
	</tr>

	<tr class="heading">
		<td colspan="2">Настройки для Google Analytics</td>
	</tr>

	<tr>
		<td class="adm-detail-content-cell-l" width="50%">
			<label>Идентификатор отслеживания</label>
		</td>
		<td class="adm-detail-content-cell-r" width="50%">
			<input type="text" size="30" name="google_analytics_id"
				value="<?= Option::get("rodzeta.yandexmetricgoals", "google_analytics_id") ?>" ?>
		</td>
	</tr>

	<tr>
		<td class="adm-detail-content-cell-l" width="50%">
			<label>Код отслеживания</label>
		</td>
		<td class="adm-detail-content-cell-r" width="50%">
			<textarea name="google_analytics_code" rows="10" cols="60"
				><?= Option::get("rodzeta.yandexmetricgoals", "google_analytics_code") ?></textarea>
		</td>
	</tr>

	<?php $tabControl->beginNextTab() ?>

	<tr>
		<td colspan="2">
			<table width="100%">
				<tbody>
					<?php
					$i = 0;
					foreach (\Rodzeta\Yandexmetricgoals\Utils::getTargetsFromCsv() as $target) {
						$i++;
					?>
						<tr>
							<td>
								<input type="text" placeholder="Селектор"
									name="analytics_targets[<?= $i ?>][0]"
									value="<?= htmlspecialcharsex($target[0]) ?>"
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Название цели (Яндекс.Метрика)"
									name="analytics_targets[<?= $i ?>][1]"
									value="<?= htmlspecialcharsex($target[1]) ?>"
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Событие"
									name="analytics_targets[<?= $i ?>][2]"
									value="<?= htmlspecialcharsex($target[2]) ?>"
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Объект (Google Analytics)"
									name="analytics_targets[<?= $i ?>][3]"
									value="<?= htmlspecialcharsex($target[3]) ?>"
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Тип взаимодействия (Google Analytics)"
									name="analytics_targets[<?= $i ?>][4]"
									value="<?= htmlspecialcharsex($target[4]) ?>"
									style="width:96%;">
							</td>
						</tr>
					<?php } ?>
					<?php foreach (range(1, 20) as $n) {
						$i++;
					?>
						<tr>
							<td>
								<input type="text" placeholder="Селектор"
									name="analytics_targets[<?= $i ?>][0]"
									value=""
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Название цели (Яндекс.Метрика)"
									name="analytics_targets[<?= $i ?>][1]"
									value=""
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Событие"
									name="analytics_targets[<?= $i ?>][2]"
									value=""
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Объект (Google Analytics)"
									name="analytics_targets[<?= $i ?>][3]"
									value=""
									style="width:96%;">
							</td>
							<td>
								<input type="text" placeholder="Тип взаимодействия (Google Analytics)"
									name="analytics_targets[<?= $i ?>][4]"
									value=""
									style="width:96%;">
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</td>
	</tr>

	<?php
	 $tabControl->buttons();
  ?>

  <input class="adm-btn-save" type="submit" name="save" value="Применить настройки">
  <input type="submit" name="clear" value="Сбросить кеш целей">

</form>

<?php

$tabControl->end();
