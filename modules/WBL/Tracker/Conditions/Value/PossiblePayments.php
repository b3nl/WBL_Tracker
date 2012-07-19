<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Value/PossiblePayments.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Callback for getting the possible payments of a payment view.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Conditions_Value_PossiblePayments extends WBL_Tracker_Conditions_Abstract
		implements WBL_Tracker_Conditions_Value_Interface
	{
		/**
		 * Returns a special value for a tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getValue() {
			$mReturn = null;

			/* @var $oView Payment */
			if (($oView = $this->getView()) instanceof Payment) {
				$mReturn = array();

				if (count($mList = $oView->getPaymentList())) {
					$mReturn = is_array($mList) ? array_keys($mList) : $mList->arrayKeys();
				} // if
			} // if

			return $mReturn;
		} // function
	} // class