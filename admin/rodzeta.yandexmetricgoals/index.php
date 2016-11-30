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
	Options\Update($request->getPostList());
}

$currentOptions = array_merge([
	"yandex_metrika_id" => "",
	"yandex_metrika_code" => "",
	"google_analytics_id" => "",
	"google_analytics_code" => "",
], Options\Select());

?>

<form action="" method="post">
	<?= bitrix_sessid_post() ?>

	<table width="100%">

		<tr>
			<td colspan="2">
				<div class="adm-detail-title">Настройки для Яндекс.Метрика</div>

				<input type="text" size="30" name="yandex_metrika_id"
					placeholder="ID счетчика Яндекс.Метрика"
					value="<?= htmlspecialcharsex($currentOptions["yandex_metrika_id"]) ?>"
					style="width:100%">

				<textarea name="yandex_metrika_code" rows="10"
					placeholder="Код счетчика"
					style="width:100%"><?= htmlspecialcharsex($currentOptions["yandex_metrika_code"]) ?></textarea>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<div class="adm-detail-title">Настройки для Google Analytics</div>

				<input type="text" size="30" name="google_analytics_id"
					placeholder="Идентификатор отслеживания"
					value="<?= htmlspecialcharsex($currentOptions["google_analytics_id"]) ?>"
					style="width:100%">

				<textarea name="google_analytics_code" rows="10"
					placeholder="Код отслеживания"
					style="width:100%"><?= htmlspecialcharsex($currentOptions["google_analytics_code"]) ?></textarea>

			</td>
		</tr>

	</table>

</form>

<?php if (0 && $formSaved) { ?>

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
