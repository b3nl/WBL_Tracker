<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Abstract.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions
	 * @version SVN: $Id$
	 */

	/**
	 * Base-Class for a web tracker condition
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions
	 * @version SVN: $Id$
	 */
	abstract class WBL_Tracker_Conditions_Abstract extends oxSuperCfg implements WBL_Tracker_Conditions_Interface {
		/**
		 * The value of the parameter.
		 * @var null
		 */
		private $_mParamValue = null;

		/**
		 * The used adapter.
		 * @var WBL_Tracker_Adapter_Interface|void
		 */
		private $_oAdapter = null;

		/**
		 * The used view.
		 * @var oxUBase|void
		 */
		private $_oView = null;

		/**
		 * The parameter name.
		 * @var string
		 */
		private $_sParamName = '';

		/**
		 * Returns the used adapter.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Adapter_Interface|void
		 */
		public function getAdapter() {
			return $this->_oAdapter;
		} // function

		/**
		 * Returns the used parameter name.
		 * @author blange <code@wbl-konzept.de>
		 * @return string
		 */
		public function getParameterName() {
			return $this->_sParamName;
		} // function

		/**
		 * Returns the found parameter value.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getParameterValue() {
			return $this->_mParamValue;
		} // function

		/**
		 * Returns the used view
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getView() {
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
		 * Sets the name of the parameter.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sName
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setParameterName($sName) {
			$this->_sParamName = $sName;

			return $this;
		} // function

		/**
		 * Sets the found parameter value.
		 * @author blange <code@wbl-konzept.de>
		 * @param mixed $mValue
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setParameterValue($mValue) {
			$this->_mParamValue = $mValue;

			return $this;
		} // function

		/**
		 * Sets the used view.
		 * @author blange <code@wbl-konzept.de>
		 * @param oxUBase $oView
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setView(oxUBase $oView) {
			$this->_oView = $oView;
			unset($oView);

			return $this;
		} // function
	} // class