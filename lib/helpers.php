<?php

use tuefekci\helpers;
use tuefekci\helpers\Files as Files;
use tuefekci\helpers\Templates as Templates;




function processDefines($path) {

	if(!file_exists($path)) {
		throw new Exception('Define File: '.$path.' does not exists.');
	}

	$parser = new \LUAParser();
	$parser->parseFile(realpath($path));

	$tech = array();

	foreach($parser->data['technologies'] as $technologyKey => $technology) {



		$tmpData = array();

		$tmpData['name'] = $technologyKey;

		if(!empty($technology['enable_equipments'])) {

			if(is_array($technology['enable_equipments'])) {

				foreach($technology['enable_equipments'] as $key => $value) {
					$technology['enable_equipments'][$key] = preg_replace('/[^a-zA-Z0-9_-]/', '', $value);
				}
	
				$tmpData['enable_equipments'] = $technology['enable_equipments'];


			} else {
				$tmpData['enable_equipments'] = array();
				$tmpData['enable_equipments'][0] = preg_replace('/[^a-zA-Z0-9_-]/', '', $technology['enable_equipments']);
			}




		}


		if(!empty($technology['path']) && is_array($technology['path'])) {

			if(!empty($technology['path']['leads_to_tech'])) {
				$tmpData['path'][] = $technology['path'];
			} else {
				$tmpData['path'] = $technology['path'];
			}

		
			foreach($tmpData['path'] as $path) {
				$dataParentTable[$path['leads_to_tech']] = $technologyKey;
			}

		}

		if(!empty($technology['start_year'])) {
			$tmpData['start_year'] = $technology['start_year'];
		}

		$tech[$technologyKey] = $tmpData;

	}

	return $tech;

}

function sortDataInner($tech, $array) {

	// Todo: Rework... needs to search through the data until it finds someething useful or not i dont know why it is like it is at the moment makes no sense this worked at some point ....

	if(empty($array['path'])) {
		return false;
	}

	foreach($array['path'] as $path ) {

		if(!empty( $tech[$path['leads_to_tech']]['enable_equipments'])) {
			return $tech[$path['leads_to_tech']];
		} else {
			return sortDataInner($tech, $tech[$path['leads_to_tech']]);
		}

	}
}


function searchDataInner($array) {

}

function generateSurplusTradeDecisionData($tech, $initKey, $maxAmount, $icon) {

	// How many years after release date item can be counted as surplus
	// TODO: Change to better system to set this...
	global $surplusWaitAmount;

	if(!isset($surplusWaitAmount)) {
		$surplusWaitAmount = 2;
	}

	$result = array();

	$old = $tech[$initKey];
	$new = sortDataInner($tech, $old);

	if(empty($old)) {
		var_dump($old);
		throw new Exception('generateSurplusTradeDecisionData something is wrong....');
	}

	if(empty($old['enable_equipments'])) {
		return false;
	}

	$tmpResult = [
		'traderNumber' => 0,
		'oldName' => $old['name'],
		'icon' => $icon,
		'equipment' => $old['enable_equipments'][0],
		'maxAmount' => $maxAmount,
	];

	if(!empty($new)) {
		$tmpResult['newName'] = $new['name'];
	}

	$result[] = $tmpResult;

	$run = true;
	while($run = true) {
	
		$old = $new;
	
		if(empty($old)) {
			$run = false;
			break;
		}
	
		$new = sortDataInner($tech, $old);
	
		if($initKey == "infantry_weapons") {
			//var_dump($old);
			//var_dump($new);
		}


		if(!empty($new)) {
	
			$data = array();
			$data = [
				'traderNumber' => 0,
				'oldName' => $old['name'],
				'newName' => $new['name'],
				'icon' => $icon,
				'equipment' => $old['enable_equipments'][0],
				'maxAmount' => $maxAmount,
			];
	
			if(!empty($old['start_year'])) {
				$data['year'] = (int)$old['start_year']+$surplusWaitAmount;
			}
	
			$result[] = $data;
	
		} else {
			$run = false;
			break;
		}
	
	}

	return $result;
	
}

function renderSurplusDecisions($decisionsByType) {

	if(empty($decisionsByType)) {
		return "";
	}
		
	$export = "";
	foreach($decisionsByType as $type) {

		if(!empty($type)) {
			foreach($type as $decision) {
				$export .= PHP_EOL;
				$export .= PHP_EOL;
				$export .= Templates::render(__TPL__.'/MarketSurplusEquipmentType.php', $decision);
				$export .= PHP_EOL; 
				$export .= PHP_EOL;
			}
		}
	}
	return $export;
}
