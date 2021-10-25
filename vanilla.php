<?php
use tuefekci\helpers;
use tuefekci\helpers\Files as Files;
use tuefekci\helpers\Templates as Templates;

require_once 'lib/parser.php';
require_once 'lib/helpers.php';

$surplusWaitAmount = 2;

// ##############################################################################################################
// Surplus Air Techs
// ##############################################################################################################


$tech = processDefines(__DEFINES__."/common/technologies/air_techs.txt");

$surplusAir = array();
$surplusAir[] = generateSurplusTradeDecisionData($tech, "early_fighter", 100, "generic_air");
$surplusAir[] = generateSurplusTradeDecisionData($tech, "CAS1", 100, "generic_air");
$surplusAir[] = generateSurplusTradeDecisionData($tech, "naval_bomber1", 75, "generic_air");
$surplusAir[] = generateSurplusTradeDecisionData($tech, "early_bomber", 50, "generic_air");

file_put_contents(__MODPATH__."/common/decisions/MarketSurplusAir.txt", Templates::render(__TPL__.'/marketsurplus.php', ['content' => renderSurplusDecisions($surplusAir)]));


// ##############################################################################################################
// Surplus Tank Techs
// ##############################################################################################################


$tech = processDefines(__DEFINES__."/common/technologies/armor.txt");

$surplusArmor = array();
$surplusArmor[] = generateSurplusTradeDecisionData($tech, "gwtank", 150, "generic_tank");
//$surplusArmor[] = generateSurplusTradeDecisionData($tech, "basic_light_tank", 100, "generic_tank");
$surplusArmor[] = generateSurplusTradeDecisionData($tech, "basic_heavy_tank", 50, "generic_tank");
$surplusArmor[] = generateSurplusTradeDecisionData($tech, "basic_medium_tank", 100, "generic_tank");

file_put_contents(__MODPATH__."/common/decisions/MarketSurplusArmor.txt", Templates::render(__TPL__.'/marketsurplus.php', ['content' => renderSurplusDecisions($surplusArmor)]));



// ##############################################################################################################
// Surplus Infantery Techs
// ##############################################################################################################
$tech = processDefines(__DEFINES__."/common/technologies/infantry.txt");

$surplusInfantry= array();
$surplusInfantry[] = generateSurplusTradeDecisionData($tech, "infantry_weapons", 2250, "generic_industry");
$surplusInfantry[] = generateSurplusTradeDecisionData($tech, "motorised_infantry", 300, "generic_industry");

file_put_contents(__MODPATH__."/common/decisions/MarketSurplusInfantery.txt", Templates::render(__TPL__.'/marketsurplus.php', ['content' => renderSurplusDecisions($surplusInfantry)]));


// ##############################################################################################################
// Surplus Support Techs
// ##############################################################################################################
$tech = processDefines(__DEFINES__."/common/technologies/support.txt");

$surplusSupport= array();
$surplusSupport[] = generateSurplusTradeDecisionData($tech, "tech_support", 200, "generic_industry");

file_put_contents(__MODPATH__."/common/decisions/MarketSurplusSupport.txt", Templates::render(__TPL__.'/marketsurplus.php', ['content' => renderSurplusDecisions($surplusSupport)]));


// ##############################################################################################################
// Surplus Artillery Techs
// ##############################################################################################################


$tech = processDefines(__DEFINES__."/common/technologies/artillery.txt");

$surplusArtillery = array();
$surplusArtillery[] = generateSurplusTradeDecisionData($tech, "interwar_artillery", 125, "generic_ignite_civil_war");
$surplusArtillery[] = generateSurplusTradeDecisionData($tech, "interwar_antiair", 125, "generic_ignite_civil_war");
$surplusArtillery[] = generateSurplusTradeDecisionData($tech, "interwar_antitank", 125, "generic_ignite_civil_war");

file_put_contents(__MODPATH__."/common/decisions/MarketSurplusArtillery.txt", Templates::render(__TPL__.'/marketsurplus.php', ['content' => renderSurplusDecisions($surplusArtillery)]));


/*
$export .= PHP_EOL;
$export .= PHP_EOL;
$export .= renderTemplate(__TPL__.'/MarketSurplusEquipmentType.php', [
    'traderNumber' => 0,
    'oldName' => $old['name'],
    'newName' => $new['name'],
    'icon' => $icon,
    'equipment' => $old['enable_equipments'][0],
    'maxAmount' => $maxAmount,
]);
$export .= PHP_EOL;
$export .= PHP_EOL;

*/

// ##############################################################################################################