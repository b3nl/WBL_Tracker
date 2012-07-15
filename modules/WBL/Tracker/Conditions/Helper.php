<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Helper.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Helper
	 * @version SVN: $Id$
	 */

	/**
	 * Helper to handle custom conditions through the config.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Helper
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Conditions_Helper extends oxSuperCfg {
		/**
		 * The used adapter.
		 * @var WBL_Tracker_Adapter_Interface|void
		 */
		private $_oAdapter = null;

		/**
		 * The used view or null.
		 * @var oxUBase|void
		 */
		private $_oView = null;

		/**
		 * String to separate the condition values.
		 * @var string
		 */
		const DEFAULT_SEPARATOR = '|';

		/**
		 * Checks if the given condition was met.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sCheck The var can be a control value for the field or a function name.
		 * @param string $sView  The checked view.
		 * @param string $sParam The name of a request parameter. If you want to check this parameter
		 * 						 for a special view, add the view name with ':' as a prefix.
		 * @return bool
		 */
		public function checkCondition($sCheck = '', $sView = '', $sParam = '') {
			$bAllowed = false;
			$oView    = $this->getView();

			// if there is no or a matching view.
			if (!$sView || (($oView && (!strcasecmp($sView, $oView->getClassName()))))) {
				// Is there a check to be made?
				if ($sCheck) {
					$oCall = $this->getCallback($sCheck, $sParam);

					if ($oCall instanceof WBL_Tracker_Conditions_Check_Interface) {
						$bAllowed = $oCall->match();
					} elseif (is_callable($sCheck)) {
						$mValue = $sParam
							? oxConfig::getParameter($sParam, true)
							: null;

						$bAllowed = (bool) call_user_func($sCheck, $mValue);
					} else {
						$bAllowed = $sParam && (string) $sCheck === (string) oxConfig::getParameter($sParam, true);
					} // else
				} else {
					// return true if there should be no parameter check.
					$bAllowed = $sParam ? (bool) oxConfig::getParameter($sParam) : true;
				} // else
			} // if

			return $bAllowed;
		} // function

		/**
		 * Returns the used adapter.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Adapter_Interface|void
		 */
		public function getAdapter() {
			return $this->_oAdapter;
		} // function

		/**
		 * Returns a callback class if $sCallback is a full qualified class name.
		 * @author blange <code@wbl-konzept.de>
		 * @param string  $sCallback      Could be a class name.
		 * @param string  $sParameterName
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function getCallback($sCallback, $sParameterName = '') {
			$mReturn = null;

			if ((class_exists($sCallback, true)) && (($oCall = wblNew($sCallback)) instanceof WBL_Tracker_Conditions_Interface)) {
				$mReturn = $oCall
					->setAdapter($this->getAdapter())
					->setParameterName($sParameterName)
					->setParameterValue($sParameterName ? oxConfig::getParameter($sParameterName) : null);

				if ($oView = $this->getView()) {
					$mReturn->setView($oView);
					unset($oView);
				} // if
			} // if

			return $mReturn;
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
		 * Sets the used adapter.
		 * @author blange <code@wbl-konzept.de>
		 * @param WBL_Tracker_Adapter_Interface $oAdapter
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setAdapter(WBL_Tracker_Adapter_Interface $oAdapter) {
			$this->_oAdapter = $oAdapter;
			unset($oAdapter);

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
	} // class