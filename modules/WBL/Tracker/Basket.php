<?php
	/**
	 * ./modules/WBL/Tracker/Basket.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage oxBasket
	 * @version SVN: $Id$
	 */

	/**
	 * Basket-Extension
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage oxBasket
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Basket extends WBL_Tracker_Basket_parent {
		/**
		 * Was the basket updated?
		 * @var bool|void
		 */
		protected $mIsWBLUpdate = null;

		/**
		 * Was the basket updated?
		 * @var string
		 */
		const SESSION_KEY_UPDATE_MARKER = 'bIsWBLBasketUpdated';

		/**
		 * (non-PHPdoc)
		 * @see http/core/oxBasket::__wakeUp()
		 */
		public function __wakeUp() {
			$this->mIsWBLUpdate = null;

			return parent::__wakeUp();
		} // function

		/**
		 * Was the basket updated?
		 * @author blange <code@wbl-konzept.de>
		 * @param bool $bNewState The new state. With this parameter the status is set, without and the status is deleted.
		 * @return bool The old state.
		 * @see oxBasket::isNewItemAdded
		 */
		public function isUpdatedForWBLTracker($bNewState = false) {
			if ($this->mIsWBLUpdate === null) {
				$this->mIsWBLUpdate = false;
			} // if

			$bOldState = $this->mIsWBLUpdate;
			$sKey      = self::SESSION_KEY_UPDATE_MARKER;

			if (func_num_args()) {
				$this->mIsWBLUpdate = $bNewState;
				oxSession::setVar($sKey, $bNewState);
			} elseif (is_bool($mTmp = oxSession::getVar($sKey))) {
				$this->mIsWBLUpdate = $bOldState = $mTmp;
				oxSession::deleteVar($sKey);
			} // else

			return $bOldState;
		} // function

		/**
		 * (non-PHPdoc)
		 * @see http/core/oxBasket::onUpdate()
		 */
		public function onUpdate() {
			$this->isUpdatedForWBLTracker(true);

			return parent::onUpdate();
		} // function
	} // class