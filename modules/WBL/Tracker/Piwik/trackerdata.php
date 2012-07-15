<?php
	/**
	 * ./modules/WBL/Tracker/Piwik/trackerdata.php
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @version SVN: $Id$
	 */

	/**
	 * Meta-Data for the piwik tracker.
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @version SVN: $Id$
	 */
	return array(
		'settings' => array(
			array('name' => 'aWBLTrackerPiwikGoals',       'type' => 'aarr', 'value' => array()),
			array('name' => 'sWBLTrackerPiwikTrackerURL',  'type' => 'str', 'value' => 'example.com'),
			array('name' => 'sWBLTrackerPiwikSiteId',      'type' => 'str', 'value' => 0),
			array('name' => 'aWBLTrackerPiwikPageVars',    'type' => 'aarr', 'value' => array(
				1 => 'searchhits|WBL_Tracker_Conditions_Value_Search_Hits||search',
				'searchquery|WBL_Tracker_Conditions_Value_Search_Query||search',
				4 => 'orderpayment|WBL_Tracker_Conditions_Value_OrderPayment||thankyou'
			)),
			array('name' => 'aWBLTrackerPiwikVisitVars',   'type' => 'aarr', 'value' => array(
				3 => 'userstatus|WBL_Tracker_Conditions_Value_UserStatus'
			))
		)
	);