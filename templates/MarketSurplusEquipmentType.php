<?php

use tuefekci\helpers;
use tuefekci\helpers\Templates as Templates;

$hasTech = "has_tech = ".$oldName.PHP_EOL;

if(!empty($newName)) {
	$hasTech .= "has_tech = ".$newName.PHP_EOL;
}

?>

purchase_surplus_<?=$oldName?>_<?=$traderNumber?> = {
	available = {

		check_variable = { ROOT.market_countries^num > 0 }
		set_temp_variable = { traderCount = ROOT.market_countries^num }


		<?php 
			if(!empty($year)) {
				echo "date > ".$year.".01.01".PHP_EOL;
			}
		?>

		if = {
			limit = {
			  check_variable = { traderCount > 2 }
			}

			var:ROOT.market_countries^2 = {
				<?php echo $hasTech .PHP_EOL; ?>
			}
		}

		if = {
			limit = {
				check_variable = { traderCount > 1 }
			}

			var:ROOT.market_countries^1 = {
				<?php echo $hasTech .PHP_EOL; ?>
			}
		}

		if = {
			limit = {
				check_variable = { traderCount > 0}
			}

			var:ROOT.market_countries^0 = {
				<?php echo $hasTech .PHP_EOL; ?>
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
						<?php echo $hasTech .PHP_EOL; ?>
					}
				}

				if = {
					limit = {
						check_variable = { traderCount > 1 }
					}

					var:ROOT.market_countries^1 = {
						<?php echo $hasTech .PHP_EOL; ?>
					}
				}

				if = {
					limit = {
						check_variable = { traderCount > 0}
					}

					var:ROOT.market_countries^0 = {
						<?php echo $hasTech .PHP_EOL; ?>
					}
				}
			}
		}
	}
	icon = <?=$icon.PHP_EOL?>
	cost = 10

	complete_effect = {
	}
	remove_effect = {

		set_temp_variable = { traderCount = ROOT.market_countries^num }

		if = {
			limit = {
			  check_variable = { traderCount > 2 }
			}

			<?php
				for ($i = 0; $i <= 2; $i++) {
					echo Templates::render(__TPL__.'/add_equipment_to_stockpile.php', [
						'type' => $equipment,
						'amount' => round($maxAmount/3),
						'producer' => 'var:ROOT.market_countries^'.$i,
					]).PHP_EOL;
					echo PHP_EOL;
				}
			?>

		} else_if = {
			limit = {
				check_variable = { traderCount > 1 }
			}

			<?php
				for ($i = 0; $i <= 1; $i++) {
					echo Templates::render(__TPL__.'/add_equipment_to_stockpile.php', [
						'type' => $equipment,
						'amount' => round($maxAmount/2),
						'producer' => 'var:ROOT.market_countries^'.$i,
					]).PHP_EOL;
					echo PHP_EOL;
				}
			?>

		} else_if = {
			limit = {
				check_variable = { traderCount > 0}
			}

			<?php
				echo Templates::render(__TPL__.'/add_equipment_to_stockpile.php', [
					'type' => $equipment,
					'amount' => $maxAmount,
					'producer' => 'var:ROOT.market_countries^0',
				]).PHP_EOL;
				echo PHP_EOL;
			?>

		}
	}
}
