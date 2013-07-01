<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Value/Interface.php
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Base-API for the condition value
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */
	interface WBL_Tracker_Conditions_Value_Interface extends WBL_Tracker_Conditions_Interface {
		/**
		 * Returns a special value for a tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getValue(); // function
	} // interface