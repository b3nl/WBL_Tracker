<?php
	/**
	 * ./modulesWBL/Tracker/Piwik/Adapter/Thankyou.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik_Adapter
	 * @version SVN: $Id$
	 */

	/**
	 * Adapter for the thankyou view.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik_Adapter
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Piwik_Adapter_Thankyou extends WBL_Tracker_Piwik_Adapter {
		/**
		 * (non-PHPdoc)
		 * @see http/modules/WBL/Tracker/Piwik/WBL_Tracker_Piwik_Adapter::init()
		 */
		public function init() {
			parent::init();

			/* @var $oView Thankyou */
			if (!(($oView = $this->getView()) instanceof Thankyou)) {
				return $this;
			} // if

			$this->loadBasket($oBasket = $oView->getBasket());
			$fVat   = 0;
			$oOrder = $oView->getOrder();

			foreach ($oBasket->getProductVats(false) as $iId => $fVatPosSum) {
				$fVat += $fVatPosSum;
			} // foreach

			return $this->addCall(array(
				'trackEcommerceOrder',
				$this->getConfig()->getShopId() . '_' . $oOrder->oxorder__oxordernr->value,
				$oOrder->oxorder__oxtotalordersum->value,
				$oOrder->oxorder__oxtotalnetsum->value,
				$fVat,
				$oOrder->oxorder__oxdelcost->value, // (optional) Shipping amount
				(bool) ($oOrder->oxorder__oxdiscount->value + $oOrder->oxorder__oxvoucherdiscount->value)
			));
		} // function
	} // class