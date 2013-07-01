<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Value/SimpleReturn.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Returning of the given value.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Conditions_Value_SimpleReturn extends WBL_Tracker_Conditions_Abstract
		implements WBL_Tracker_Conditions_Value_Interface {
		/**
		 * Returns a special value for a tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getValue() {
			return $this->getParameterValue();
		} // function
	} // class