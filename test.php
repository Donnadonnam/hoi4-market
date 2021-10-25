<?php

require('parser.php');

$parser = new \LUAParser();


$maxAmount = 100;
$icon = "generic_air";

$initKey = "early_fighter";

$file = __DIR__."/air_techs.txt";














/*

$initKey = "gwtank";
$file = __DIR__."/armor.txt";

*/


#$initKey = "armored_car1";
#$file = __DIR__."/infantry.txt";


try {
	//$parser->parseFile('artillery.txt');
	//print_r($parser->data);

	// Parste artillery.txt
	$data = array();
	$dataParentTable  = array();
		
	$parser->parseFile($file);

	$tech = array();

	foreach($parser->data['technologies'] as $technologyKey => $technology) {

		$tmpData = array();

		$tmpData['name'] = $technologyKey;

		if(!empty($technology['enable_equipments'])) {
			$tmpData['enable_equipments'] = $technology['enable_equipments'];
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








	function sortDataInner($array) {

		global $tech;

		if(empty($array['path'])) {
			return false;
		}

		foreach($array['path'] as $path ) {

			if(!empty( $tech[$path['leads_to_tech']]['enable_equipments'])) {
				return $tech[$path['leads_to_tech']];
			} else {
				return sortDataInner($tech[$path['leads_to_tech']]);
			}

		}

	}

	function exPortScript($old, $new, $traderNumber = 0) {

		global $maxAmount;
		global $icon;

		if(empty($new)) {
			return false;
		}


		if(!empty($old['enable_equipments'][0])) {
			$equipment = $old['enable_equipments'][0];
		} else {
			$equipment = $old['name'];
		}



		$name = 'purchase_surplus_'.$old['name'].'_'.$traderNumber;
		echo $name.PHP_EOL;

	return '
	
    '.$name.' = {
        available = {

            check_variable = { ROOT.market_countries^num > 0 }
            set_temp_variable = { traderCount = ROOT.market_countries^num }

            if = {
                limit = {
                  check_variable = { traderCount > 2 }
                }

                var:ROOT.market_countries^2 = {
                    has_tech = '.$old['name'].'
                    has_tech = '.$new['name'].'
                }
            }

            if = {
                limit = {
                    check_variable = { traderCount > 1 }
                }

                var:ROOT.market_countries^1 = {
                    has_tech = '.$old['name'].'
                    has_tech = '.$new['name'].'
                }
            }

            if = {
                limit = {
                    check_variable = { traderCount > 0}
                }

                var:ROOT.market_countries^0 = {
                    has_tech = '.$old['name'].'
                    has_tech = '.$new['name'].'
                }
            }

            NOT = { 
                ROOT = { has_idea = closed_economy }
            }
            num_of_civilian_factories_available_for_projects > 1

        }
        modifier = {
            civilian_factory_use = 1
        }
        days_remove = 90
        visible = {
            has_country_flag = open_surplus_menu


			OR = {
				is_debug = yes
				AND = {

					check_variable = { ROOT.market_countries^num > 0 }
					set_temp_variable = { traderCount = ROOT.market_countries^num }

					if = {
						limit = {
						check_variable = { traderCount > 2 }
						}

						var:ROOT.market_countries^2 = {
							has_tech = '.$old['name'].'
							has_tech = '.$new['name'].'
						}
					}

					if = {
						limit = {
							check_variable = { traderCount > 1 }
						}

						var:ROOT.market_countries^1 = {
							has_tech = '.$old['name'].'
							has_tech = '.$new['name'].'
						}
					}

					if = {
						limit = {
							check_variable = { traderCount > 0}
						}

						var:ROOT.market_countries^0 = {
							has_tech = '.$old['name'].'
							has_tech = '.$new['name'].'
						}
					}
				}
			}
        }
        icon = '.$icon.'
        cost = 10

        complete_effect = {
        }
        remove_effect = {

            set_temp_variable = { traderCount = ROOT.market_countries^num }

            if = {
                limit = {
                  check_variable = { traderCount > 2 }
                }

                add_equipment_to_stockpile = {
                    type = '.$equipment.'
                    amount = '.round($maxAmount/3).'
                    producer = var:ROOT.market_countries^0
                }

                add_equipment_to_stockpile = {
                    type = '.$equipment.'
                    amount = '.round($maxAmount/3).'
                    producer = var:ROOT.market_countries^1
                }

                add_equipment_to_stockpile = {
                    type = '.$equipment.'
                    amount = '.round($maxAmount/3).'
                    producer = var:ROOT.market_countries^2
                }
            } else_if = {
                limit = {
                    check_variable = { traderCount > 1 }
                }

                add_equipment_to_stockpile = {
                    type = '.$equipment.'
                    amount = '.round($maxAmount/2).'
                    producer = var:ROOT.market_countries^0
                }
            
                add_equipment_to_stockpile = {
                    type = '.$equipment.'
                    amount = '.round($maxAmount/2).'
                    producer = var:ROOT.market_countries^1
                }
            } else_if = {
                limit = {
                    check_variable = { traderCount > 0}
                }

                add_equipment_to_stockpile = {
                    type = '.$equipment.'
                    amount = '.$maxAmount.'
                    producer = var:ROOT.market_countries^0
                }
            }
        }
    }
	
	';

	}
	
	
	$run = true;
	$export = "";

	$old = $tech[$initKey];
	$new = sortDataInner($old);
	$export .= exPortScript($old, $new);

	while($run = true) {

		$old = $new;
		$new = sortDataInner($old);
		if(!empty($new)) {
			$export .= exPortScript($old, $new);
		} else {
			$run = false;
		}

	}

	file_put_contents(__DIR__."/".$initKey.".txt", $export);


}
catch(Exception $e) {
    echo 'Exception: ',  $e->getMessage(), PHP_EOL;
}