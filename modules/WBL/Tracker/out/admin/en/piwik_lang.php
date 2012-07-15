<?php
	/**
	 * ./modules/WBL/Tracker/out/admin/en/piwik_lang.php
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage out_admin
	 * @version SVN: $Id$
	 */

	/**
	 * english language file.
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage out_admin
	 * @version SVN: $Id$
	 */

	$sLangName = 'English';

	$aLang = array(
		'charset' => 'UTF-8',
		'SHOP_MODULE_GROUP_WBL_Tracker_Piwik' => 'Piwik',
		'SHOP_MODULE_aWBLTrackerPiwikGoals'   => 'Goals; Please use the following array notation: Goal-ID => checkvalue|viewname|parametername|revenue',

		'SHOP_MODULE_aWBLTrackerPiwikPageVars'  => 'Var-Tracking for a single impressionn. Please use the following array notation: Var-ID => varname|value|checkvalue|viewname|parametername',
		'SHOP_MODULE_aWBLTrackerPiwikVisitVars' => 'Var-Tracking for an Unique-Visit. Please use the following array notation: Var-ID => varname|value|checkvalue|viewname|parametername',

		'SHOP_MODULE_bIsWBLTrackerPiwikActive'   => 'Tracking with Piwik?',
		'SHOP_MODULE_sWBLTrackerPiwikSiteId'     => 'The Site-ID for your site from the Piwik Setup?',
		'SHOP_MODULE_sWBLTrackerPiwikTrackerURL' => 'Which URL, without Protocol etc., is used for your tracking? (Use t.wbl-konzept.de/, if your tracker is http://t.wbl-konzept.de/piwik.php)'
	);
