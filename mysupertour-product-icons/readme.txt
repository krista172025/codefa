=== MySuperTour Product Icons ===
Contributors: @l1ghtsun (Telegram: https://t.me/l1ghtsun)
Tags: woocommerce, icons, tours, filters
Requires at least: 6.0
Tested up to: 6.x
Requires PHP: 7.4
Stable tag: 4.2.0
License: GPLv2 or later

Плагин добавляет:
- Метабокс (формат, время, транспорт, 2 иконки).
- Плашки на товарах (format / duration / transport).
- Авто синхронизацию: формат -> product_tag (mst-format-*), транспорт -> brand (mst-transport-*).
- Чиповые фильтры + диапазон цены ([mst_filters]) + автообновление URL.
- Авто‑клики по кастомным dropdown (без отдельной кнопки "Поиск").

== Установка ==
1. Распакуйте папку mysupertour-product-icons в wp-content/plugins или загрузите ZIP.
2. Активируйте плагин.
3. Откройте товар, задайте параметры, сохраните.

== Шорткод ==
[mst_filters] – выводит блоки чипов и фильтр по цене.

== Массовая миграция ==
Перейдите как админ: /wp-admin/?mst_sync_tags_brands=1 (однократно).

== Удаление ==
Стандартное удаление плагина – метаданные товара (_mst_pi_*) остаются.

== Changelog ==
4.2.0 – Полная сборка с чиповыми фильтрами и авто‑применением.