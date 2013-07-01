<?php
	/**
	 * ./modules/WBL/Tracker/GoogleAnalytics/trackerdata.php
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage GoogleAnalytics
	 * @version SVN: $Id$
	 */

	/**
	 * Meta-Data for the Google Analytics tracker.
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage GoogleAnalytics
	 * @version SVN: $Id$
	 */
	return array(
		'settings' => array(
			array('name' => 'bWBLTrackerGAAnonymize',  'type' => 'bool', 'value' => true),
			array('name' => 'sWBLTrackerGATrackingId', 'type' => 'str')
		),
		'alias' => 'GoglAnlytics'
	);
