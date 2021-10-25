<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'lib/parser.php';
require 'lib/helpers.php';

use tuefekci\helpers\Files as Files;

$cli = new \League\CLImate\CLImate;

// =================================================================
// Welcome
$cli->clear();
$cli->lightGreen()->border("*");
$cli->lightGreen()->out('* HOI4-Market Tools');
$cli->lightGreen()->out('* (c) 2021-'.date("Y").' Giacomo Tüfekci');
$cli->lightGreen()->out('* https://github.com/tuefekci');
$cli->lightGreen()->border("*");
$cli->break();
// =================================================================




$buildType = "thegreatwar";
$buildType = "vanilla";
$buildTarget = "test";

$baseName = "market-";

define("__TPL__", __DIR__.'/templates');
define("__SRC__", __DIR__."/src");
define("__TMP__", __DIR__."/tmp");

switch ($buildTarget) {
    case "release":
        define("__TARGET__", __DIR__."/release");
        break;
    case "test":
        define("__TARGET__", __DIR__."/_mod-folder");
        break;
    case "tmp":
    default:
        define("__TARGET__", __TMP__);

}

switch ($buildType) {
    case "thegreatwar":
        define("__MODNAME__", "thegreatwar");
        define("__MODPATH__", __TARGET__."/".$baseName.__MODNAME__);
        define("__DEFINES__", __DIR__."/_steam-workshop/699709023");
        //include(__DIR__."/TheGreatWar.php");
        break;
    case "vanilla":
        define("__MODNAME__", "vanilla");
        define("__MODPATH__", __TARGET__."/".$baseName.__MODNAME__);
        define("__DEFINES__", __DIR__."/_vanilla");
        //include(__DIR__."/TheGreatWar.php");
        break;
	default:
		die("no build type selected?".PHP_EOL);
}

Files::sync(__SRC__, __MODPATH__, false);
include(__DIR__."/".__MODNAME__.".php");

var_dump(__SRC__);
var_dump(__MODPATH__);

//var_dump(scanCompleteFolder(__DIR__.'/src'));