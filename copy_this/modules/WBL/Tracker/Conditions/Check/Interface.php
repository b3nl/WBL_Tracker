<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Check/Interface.php
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Base-API for the condition check.
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */
	interface WBL_Tracker_Conditions_Check_Interface extends WBL_Tracker_Conditions_Interface {
		/**
		 * Is this condition used?
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function match(); // function
	} // interface