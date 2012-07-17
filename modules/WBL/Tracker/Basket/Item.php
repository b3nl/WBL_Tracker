<?php
	/**
	 * ./modules/WBL/Tracker/Basket/Item.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Basket
	 * @version SVN: $Id$
	 */

	/**
	 * Extension for the basket item.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Basket
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Basket_Item extends WBL_Tracker_Basket_Item_parent {
		/**
		 * Caching of the active category id.
		 * @var string
		 */
		private $_sUsedWBLCatId = '';

		/**
		 * The category which was used for the basket click.
		 * @var oxCategory|void|false
		 */
		protected $mUsedWBLCat = false;

		/**
		 * (non-PHPdoc)
		 * @see http/core/oxBasketItem::__sleep()
		 */
		public function __sleep() {
			return array_merge(array('_sUsedWBLCatId'), parent::__sleep());
		} // function

		/**
		 * Returns the used category.
		 * @author Bjoern Simon Lange <code@wbl-konzept.de>
		 * @return oxCategory|void
		 */
		public function getUsedWBLCategory() {
			if ($this->mUsedWBLCat === false) {
				$this->mUsedWBLCat = null;

				if ($sCatId = $this->getUsedWBLCatId()) {
					$this->mUsedWBLCat = oxNew('oxcategory');
					$this->mUsedWBLCat->load($sCatId);
				} // if
			} // if

			return $this->mUsedWBLCat;
		} // function

		/**
		 * Returns the id of the used category.
		 * @author Bjoern Simon Lange <code@wbl-konzept.de>
		 * @return string
		 */
		public function getUsedWBLCatId() {
			return $this->_sUsedWBLCatId;
		} // function

		/**
		 * (non-PHPdoc)
		 * @todo Is not working everytime
		 * @see http/core/oxBasketItem::init()
		 */
		public function init($sProductId, $fAmount, $mSel = null, $mPersParam = null, $mBundle = null) {
			if (($oView = $this->getConfig()->getActiveView()) instanceof oxUBase) {
				$sCatId = '';

				if ($oCat = $oView->getActCategory()) {
					$sCatId = $oCat->getId();
				} elseif ($oCat = $oView->getActiveCategory()) {
					$sCatId = $oCat->getId();
				} else {
					$sCatId = $oView->getCategoryId();
				} // else
				unset($oCat, $oView);

				$this->setUsedWBLCatId($sCatId);
			} // if

			return parent::init($sProductId, $fAmount, $mSel, $mPersParam, $mBundle);
		} // function

		/**
		 * Sets the used cat id.
		 * @author Bjoern Simon Lange <code@wbl-konzept.de>
		 * @param string $sCatId
		 * @return WBL_Tracker_Basket_Item
		 */
		public function setUsedWBLCatId($sCatId) {
			$this->_sUsedWBLCatId = $sCatId;

			return $this;
		} // function
	} // class