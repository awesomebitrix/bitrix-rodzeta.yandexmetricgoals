<?php
/*******************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

namespace Rodzeta\Yandexmetricgoals;

use Bitrix\Main\{Application, Config\Option, Localization\Loc};

require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php";
//require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

// TODO заменить на определение доступа к редактированию контента
// 	if (!$USER->CanDoOperation("rodzeta.siteoptions"))
if (!$GLOBALS["USER"]->IsAdmin()) {
	//$APPLICATION->authForm("ACCESS DENIED");
  return;
}

Loc::loadMessages(__FILE__);
//Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . ID . "/admin/" . ID . "/index.php");

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

StorageInit();

$formSaved = check_bitrix_sessid() && $request->isPost();
if ($formSaved) {
	Update($request->getPostList());
}

$currentOptions = array_merge([
	"yandex_metrika_id" => "",
	"yandex_metrika_code" => "",
	"google_analytics_id" => "",
	"google_analytics_code" => "",
], Select());

?>

<form action="" method="post">
	<?= bitrix_sessid_post() ?>

	<table width="100%">

		<tr class="heading">
			<td colspan="2">Настройки для Яндекс.Метрика</td>
		</tr>

		<tr>
			<td class="adm-detail-content-cell-l" width="50%">
				<label>ID счетчика Яндекс.Метрика</label>
			</td>
			<td class="adm-detail-content-cell-r" width="50%">
				<input type="text" size="30" name="yandex_metrika_id"
					value="<?= $currentOptions["yandex_metrika_id"] ?>" ?>
			</td>
		</tr>

		<tr>
			<td class="adm-detail-content-cell-l" width="50%">
				<label>Код счетчика</label>
			</td>
			<td class="adm-detail-content-cell-r" width="50%">
				<textarea name="yandex_metrika_code" rows="10" cols="60"
					><?= $currentOptions["yandex_metrika_code"] ?></textarea>
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
					value="<?= $currentOptions["google_analytics_id"] ?>" ?>
			</td>
		</tr>

		<tr>
			<td class="adm-detail-content-cell-l" width="50%">
				<label>Код отслеживания</label>
			</td>
			<td class="adm-detail-content-cell-r" width="50%">
				<textarea name="google_analytics_code" rows="10" cols="60"
					><?= $currentOptions["google_analytics_code"] ?></textarea>
			</td>
		</tr>

	</table>

</form>

<?php if ($formSaved) { ?>

	<script>
		// close after submit
		top.BX.WindowManager.Get().AllowClose();
		top.BX.WindowManager.Get().Close();
	</script>

<?php } else { ?>

	<script>
		// add buttons for current windows
		BX.WindowManager.Get().SetButtons([
			BX.CDialog.prototype.btnSave,
			BX.CDialog.prototype.btnCancel
			//,BX.CDialog.prototype.btnClose
		]);
	</script>

<?php } ?>
