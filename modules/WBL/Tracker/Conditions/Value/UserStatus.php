<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Value/UserStatus.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Checks the status of the user.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @todo Not realy working on full page caches.
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Conditions_Value_UserStatus extends WBL_Tracker_Conditions_Abstract
		implements WBL_Tracker_Conditions_Value_Interface {
		/**
		 * Returns a special value for a tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getValue() {
			$sReturn = '';

			if (!$oUser = $this->getUser()) {
				$sReturn = '-';
			} elseif (!$oUser->oxuser__oxpassword) {
				$sReturn = '-1';
			// TODO Check for thankyou view and the order count
			} elseif ($oUser->inGroup('oxidnotyetordered')) {
				$sReturn = '0';
			}  else {
				$sReturn = '1';
			} // else

			return $sReturn;
		} // function
	} // class