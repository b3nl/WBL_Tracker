<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Value/User/Id.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Tracks the ID of an user.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @todo Not realy working on full page caches.
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Conditions_Value_User_Id extends WBL_Tracker_Conditions_Abstract
		implements WBL_Tracker_Conditions_Value_Interface {
		/**
		 * Returns a special value for a tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getValue() {
			return ($oUser = $this->getUser()) ? $oUser->getId() : '-';
				$sReturn = $oUser->getId();
		} // function
	} // class