<?php
/**
* File containing constants and basic autoloading
*/


/** @var constant project root */
define("ROOT", __DIR__);

/** @var constant public folder */
define("PUBLIC_PATH", ROOT . '/public');

/** @var constant path to settings.yml */
define("SETTINGS_PATH", ROOT . '/settings.yml');

require_once __DIR__.'/vendor/autoload.php';
