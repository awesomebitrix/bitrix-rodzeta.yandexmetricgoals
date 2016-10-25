﻿
# Модуль Привязки целей для Яндекс.Метрика и Google Analytics

## Описание

Данный модуль позволяет добавлять код счетчика для Яндекс.Метрика и Google Analytics на все страницы сайта, а так же назначать отправку целей по событию элементов страницы https:// yandex.ru/support/metrika/objects/reachgoal.xml без программирования. Список целей настраивается через админку (вкладка "Цели") или в csv-файле (/upload/rodzeta.yandexmetricgoals.csv)

## Описание установки и настройки решения

Для идентификации объекта (кнопки, формы и т.п.) поддерживаются стандартные css-селекторы - в коде шаблона необходимо задать идентификатор для селектора или же использовать существующий. 

Назначение целей на типовые действия (submit - отправка формы, click - клик по кнопке и т.п.) https://dev.1c-bitrix.ru/api_help/main/js_lib/kernel/events/bx_bind.php. Событие "ready" используется для отправки целей после успешной отправки формы (проверяется по наличию сообщения об успешной отправке при загрузке страницы - например элемента с классом form-result-success).

При редактировании файла rodzeta.yandexmetricgoals.csv через фтп или стандартный файловый менеджер bitrix - нажмите в настройке модуля кнопку "Применить настройки".

Для отключения отправки целей из csv-файла нажмите "Сбросить кеш целей".

## Описание техподдержки и контактных данных

Тех. поддержка и кастомизация оказывается на платной основе, e-mail: rivetweb@yandex.ru

Багрепорты и предложения на https://github.com/rivetweb/bitrix-rodzeta.yandexmetricgoals/issues

Пул реквесты на https://github.com/rivetweb/bitrix-rodzeta.yandexmetricgoals/pulls

## Ссылка на демо-версию

http://villa-mia.ru/
