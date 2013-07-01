<?php
	/**
	 * ./modules/WBL/Tracker/GoogleAnalytics/Adapter/Thankyou.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage GoogleAnalytics
	 * @version SVN: $Id$
	 */

	/**
	 * Thankyou-Tracking for Google-Analytics.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage GoogleAnalytics
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_GoogleAnalytics_Adapter_Thankyou extends WBL_Tracker_GoogleAnalytics_Adapter {
		/**
		 * (non-PHPdoc)
		 * @see www/modules/WBL/Tracker/GoogleAnalytics/WBL_Tracker_GoogleAnalytics_Adapter::init()
		 */
		public function init() {
			parent::init();

			/* @var $oView Thankyou */
			if ((($oView = $this->getView()) instanceof Thankyou)) {
				$oOrder = $oView->getOrder();

				if (($oBasket = $oView->getBasket()) instanceof oxBasket) {
					$fVat = 0;

					foreach ($oBasket->getProductVats(false) as $iId => $fVatPosSum) {
						$fVat += $fVatPosSum;
					} // foreach

					$this->addTrackerCall(array(
						'_addTrans',
						$sOrderId = $this->getConfig()->getShopId() . '_' . $oOrder->oxorder__oxordernr->value,
						$this->getConfig()->getActiveShop()->oxshops__oxname->value,
						$oOrder->oxorder__oxtotalordersum->value,
						$fVat,
						$oOrder->oxorder__oxdelcost->value,
						$oOrder->oxorder__oxbillcity->value,
						'',
						$oOrder->getBillCountry()->value
					));

					if ($aItems = $oBasket->getContents()) {
						/* @var $oItem oxBasketItem */
						foreach ($aItems as $oItem) {
							$oArticle = $oItem->getArticle(false);

							$this->addTrackerCall(array(
								'_addItem',
								$sOrderId,
								$oArticle->oxarticles__oxartnum->value,
								$oItem->getTitle(),
								($oItem instanceof WBL_Tracker_Basket_Item && ($oCat = $oItem->getUsedWBLCategory()))
									? $this->getCatString($oCat)
									: array()
								,
								$oItem->getUnitPrice()->getBruttoPrice(),
								$oItem->getAmount()
							));
						} // foreach
						unset($aItems, $oArticle, $oItem);
					} // if
				} // if

				$this->addTrackerCall(array('_trackTrans'));
			} // if

			return $this;
		} // function
	} // class