-- ###########################################################################
-- Capture
NDefines.NProduction.ANNEX_FIELD_EQUIPMENT_RATIO = 0.75;
NDefines.NProduction.ANNEX_FUEL_RATIO = 0.75;
NDefines.NProduction.ANNEX_CONVOYS_RATIO = 0.75;
NDefines.NProduction.CAPITULATE_STOCKPILES_RATIO = 0.75;
NDefines.NProduction.CAPITULATE_FUEL_RATIO = 0.75;
NDefines.NMilitary.BASE_CAPTURE_EQUIPMENT_RATIO = 0.50;


-- ###########################################################################
-- Max PP
NDefines.NCountry.POLITICAL_POWER_CAP = 99999;			-- Max amount of political power country should have
NDefines.NCountry.BASE_MAX_COMMAND_POWER = 99999;					-- base value for maximum command power

-- ###########################################################################
-- Max XP
NDefines.NMilitary.MAX_ARMY_EXPERIENCE = 99999;
NDefines.NMilitary.MAX_NAVY_EXPERIENCE = 99999;
NDefines.NMilitary.MAX_AIR_EXPERIENCE = 99999;


-- ###########################################################################
-- XP Costs
NDefines.NMilitary.LAND_EQUIPMENT_BASE_COST = 1; -- Cost in XP to upgrade base + ( total levels * ramp )
NDefines.NMilitary.LAND_EQUIPMENT_RAMP_COST = 1;
NDefines.NMilitary.NAVAL_EQUIPMENT_BASE_COST = 3;
NDefines.NMilitary.NAVAL_EQUIPMENT_RAMP_COST = 3;
NDefines.NMilitary.AIR_EQUIPMENT_BASE_COST = 2;
NDefines.NMilitary.AIR_EQUIPMEN_RAMP_COST = 2;

NDefines.NMilitary.BASE_DIVISION_BRIGADE_GROUP_COST = 1;
NDefines.NMilitary.BASE_DIVISION_BRIGADE_CHANGE_COST = 1;
NDefines.NMilitary.BASE_DIVISION_SUPPORT_SLOT_COST = 1;

-- ###########################################################################
-- XP Gain
-- NDefines.NMilitary.UNIT_EXPERIENCE_PER_COMBAT_HOUR = 0.0115;
-- NDefines.NMilitary.UNIT_EXPERIENCE_SCALE = 1.0;
-- NDefines.NMilitary.UNIT_EXPERIENCE_PER_TRAINING_DAY = 0.0115;

-- NDefines.NMilitary.FIELD_EXPERIENCE_SCALE = 0.022;
-- NDefines.NMilitary.FIELD_EXPERIENCE_MAX_PER_DAY = 6;				-- Most xp you can gain per day

-- NDefines.NMilitary.EXPEDITIONARY_FIELD_EXPERIENCE_SCALE = 0.5;		-- reduction factor in Xp from expeditionary forces
-- NDefines.NMilitary.LEND_LEASE_FIELD_EXPERIENCE_SCALE = 0.025;		-- Experience scale for lend leased equipment used in combat.
-- NDefines.NMilitary.LEADER_EXPERIENCE_SCALE = 1.0;

-- NDefines.NMilitary.EXPERIENCE_COMBAT_FACTOR = 0.25;
-- NDefines.NMilitary.EXPERIENCE_LOSS_FACTOR = 0.75;                 -- percentage of experienced solders who die when manpower is removed

-- NDefines.NMilitary.BATALION_NOT_CHANGED_EXPERIENCE_DROP = 0.0;		-- Division experience drop if unit has same batalion
-- NDefines.NMilitary.BATALION_CHANGED_EXPERIENCE_DROP = 0.35;			-- Division experience drop if unit has different batalion

-- NDefines.NMilitary.BASE_LEADER_TRAIT_GAIN_XP = 1.25;

-- ###########################################################################
-- ATTRITION
NDefines.NMilitary.ATTRITION_DAMAGE_ORG = 0.01;					   -- damage from attrition to Organisation
NDefines.NMilitary.ATTRITION_EQUIPMENT_LOSS_CHANCE = 0.01;		   -- Chance for loosing equipment when suffer attrition. Scaled up the stronger attrition is. Then scaled down by equipment reliability.
NDefines.NMilitary.ATTRITION_EQUIPMENT_PER_TYPE_LOSS_CHANCE = 0.01; -- Chance for loosing equipment when suffer attrition. Scaled up the stronger attrition is. Then scaled down by equipment reliability.
NDefines.NMilitary.ATTRITION_WHILE_MOVING_FACTOR = 0.2;

NDefines.NMilitary.OUT_OF_SUPPLY_ATTRITION = 0.35;               	-- max attrition when out of supply
NDefines.NMilitary.TRAINING_ATTRITION = 0.08;	  			   		-- amount of extra attrition from being in training
NDefines.NMilitary.BORDER_WAR_ATTRITION_FACTOR = 0.15;		   		-- How much of borderwar balance of power makes it into attrition
NDefines.NMilitary.UNIT_UPKEEP_ATTRITION = 0.00;					--Constant attrition value applied to armies.

-- Navy
NDefines.NNavy.ATTRITION_WHILE_MOVING_FACTOR = 0.35;							-- attrition multiplier while moving & doing missions
NDefines.NNavy.ATTRITION_DAMAGE_ORG = 0.015;			   				-- damage from attrition to Organisation (relative to max org)
NDefines.NNavy.ATTRITION_DAMAGE_STR = 0.02;				   				-- damage from attrition to str (relative to max str)
NDefines.NNavy.ATTRITION_STR_DAMAGE_CHANCE = 0.1;							-- chance to get damaged at highest attrition