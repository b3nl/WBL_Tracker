<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Value/OrderPayment.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Logs the payment of an order.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_TRacker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Conditions_Value_OrderPayment extends WBL_Tracker_Conditions_Abstract
		implements WBL_Tracker_Conditions_Value_Interface {
		/**
		 * Returns a special value for a tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getValue() {
			/* @var $oView Thankyou */
			$oView = $this->getView();

			return ($oView instanceof Thankyou) && ($oOrder = $oView->getOrder())
				? $oOrder->oxorder__oxpaymenttype->value
				: '';
		} // function
	} // class