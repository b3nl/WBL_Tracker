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
		 * The array for calls to the tracker class. The key or the first parameter could be the method name of the tracker.
		 * @var array
		 */
		protected $aTrackerCalls = array();

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
		 * Adds a call for the tracker class.
		 * @author blange <code@wbl-konzept.de>
		 * @param  array  $aCallData    The data for the tracker call.
		 * @param  bool   $bWithParsing Should a helper parse the php values to javascript parts?
		 * @param  string $sKey         A Special key for this call, maybe the method name.
		 * @return WBL_Tracker_Piwik_Adapter_Standard
		 */
		public function addTrackerCall(array $aCallData, $bWithParsing = true, $sKey = '') {
			$this->aTrackerCalls[$sKey ? $sKey : count($this->aTrackerCalls) - 1] = $bWithParsing
				? wblNew('WBL_Tracker_Helper')->parseValueToJSPart($aCallData)
				: '[' . implode(',', $aCallData) . ']';

			return $this;
		} // function

		/**
		 * Returns the category breadcrumb starting with the deepest used child.
		 * @author blange <code@wbl-konzept.de>
		 * @param oxCategory $oCat
		 * @return array
		 */
		protected function getCatString(oxCategory $oCat) {
			$aData = array();

			if ($aTree = wblNew('WBL_Tracker_Helper')->getCategoryTree($oCat)) {
				/* @var $oCat oxCategory */
				foreach ($aTree as $sCatId => $oCat) {
					$aData[] = $oCat->oxcategories__oxtitle->value;
				} // foreach
			} // if

			return $aData;
		} // function

		/**
		 * Returns the used smarty instance or null.
		 * @author blange <code@wbl-konzept.de>
		 * @return Smarty
		 */
		protected function getSmarty() {
			return $this->_oSmarty;
		} // function

		/**
		 * Returns the array with the tracker calls.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 */
		public function getTrackerCalls() {
			return $this->aTrackerCalls;
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
