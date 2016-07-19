
# Модуль Привязки целей для Яндекс.Метрика

## Описание

Данный модуль позволяет добавлять код счетчика для Яндекс.Метрика и назначать отправку целей по событию элементов страницы https://yandex.ru/support/metrika/objects/reachgoal.xml без программирования.

## Особенности

- позволяет быстро назначать список целей например, оперативно применить полученный список целей составленный специалистом без ручного прописывания js-кода;
- назначение целей на типовые действия (submit - отправка формы, click - клик по кнопке и т.п.) https://dev.1c-bitrix.ru/api_help/main/js_lib/kernel/events/bx_bind.php

## Как работает

- загрузите или создайте файл rodzeta.yandexmetricgoals.csv в папке /upload/ с помощью стандартного файлового менеджера Bitrix или по FTP;
- формат файла: см. пример ниже;
- после изменений в файле rodzeta.yandexmetricgoals.csv - нажмите в настройке модуля кнопку "Применить настройки";
- для идентификации объекта (кнопки, формы и т.п.) поддерживаются стандартные css-селекторы - в коде шаблона необходимо задать идентификатор для селектора или же использовать существующий.

Для отключения отправки целей из csv-файла нажмите "Сбросить кеш целей".

Пример содержимого файла rodzeta.yandexmetricgoals.csv:

    Селектор    Название цели   Событие
    .mfeedback form input[type="submit"]    ObratniyZvonok  click
    .form-order1    Zakaz   submit
    .mfeedback form Feedback    submit

## Демо сайт

http://villa-mia.ru/

## Тех. поддержка и кастомизация

Оказывается на платной основе, e-mail: rivetweb@yandex.ru

Багрепорты и предложения на https://github.com/rivetweb/bitrix-rodzeta.yandexmetricgoals/issues

Пул реквесты на https://github.com/rivetweb/bitrix-rodzeta.yandexmetricgoals/pulls
