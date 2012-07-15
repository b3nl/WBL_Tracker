<?php
	/**
	 * ./modules/WBL/Tracker/Adapter/Abstract.php
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version SVN: $id$
	 */

	/**
	 * Base-Class for the tracker.
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version SVN: $id$
	 */
	abstract class WBL_Tracker_Adapter_Abstract extends oxSuperCfg implements
		WBL_Tracker_Adapter_Interface
	{
		/**
		 * The used view data.
		 * @var array
		 */
		private $_aViewData = array();

		/**
		 * The used smarty instance.
		 * @var Smarty
		 */
		private $_oSmarty = null;

		/**
		 * The used view or null.
		 * @var oxUBase|void
		 */
		private $_oView = null;

		/**
		 * The config array.
		 * @var array
		 */
		protected $aConfig = array();

		/**
		 * xHTML?
		 * @var    bool
		 */
		protected $bIsXHTML = true;

		/**
		 * Returns the used smarty instance or null.
		 * @author blange <code@wbl-konzept.de>
		 * @return Smarty
		 */
		protected function getSmarty() {
			return $this->_oSmarty;
		} // function

		/**
		 * Returns the used view.
		 * @author blange <code@wbl-konzept.de>
		 * @return oxUBase|void
		 */
		protected function getView() {
			return $this->_oView;
		} // function

		/**
		 * Returns the used view data.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 */
		protected function getViewData() {
			return $this->_aViewData;
		} // function

		/**
		 * Inits the adapter after setting the vars of the framework.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Adapter_Abstract
		 */
		public function init() {
			return $this;
		} // function

		/**
		 * Additional check if this adapter is allowed for the given class.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sclass
		 * @return bool
		 */
		public function isForClass($sclass) {
			return true;
		} // function

		/**
		 * Should this tracker render xHTML?
		 * @author blange <code@wbl-konzept.de>
		 * @param  $bNewState The new state.
		 * @return bool The old state.
		 */
		public function isXHTML($bIsXHTML = false) {
			$bOldValue = $this->bIsXHTML;

			if (func_get_args()) {
				$this->bIsXHTML = $bIsXHTML;
			} // function

			return $bOldValue;
		} // function

		/**
		 * Sets the used smarty instance.
		 * @author blange <code@wbl-konzept.de>
		 * @param Smarty $oSmarty
		 * @return WBL_Tracker_Adapter_Abstract
		 */
		public function setSmarty(Smarty $oSmarty) {
			$this->_oSmarty = $oSmarty;
			unset($oSmarty);

			return $this;
		} // function

		/**
		 * Sets the active view.
		 * @author blange <code@wbl-konzept.de>
		 * @param oxUBase $oView
		 * @return WBL_Tracker_Adapter_Abstract
		 */
		public function setView(oxUBase $oView) {
			$this->_oView = $oView;
			unset($oView);

			return $this;
		} // function

		/**
		 * Sets the used view data.
		 * @author blange <code@wbl-konzept.de>
		 * @param array $aData
		 * @return WBL_Tracker_Adapter_Abstract
		 */
		public function setViewData(array $aData) {
			$this->_aViewData = $aData;
			unset($aData);

			return $this;
		} // function
	} // interface
