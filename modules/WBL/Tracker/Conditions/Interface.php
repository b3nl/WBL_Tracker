<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Interface.php
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions
	 * @version SVN: $Id$
	 */

	/**
	 * Base-API for the condition.
	 * @author Bjoern Simon Lange
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions
	 * @version SVN: $Id$
	 */
	interface WBL_Tracker_Conditions_Interface {
		/**
		 * Sets the used adapter.
		 * @author blange <code@wbl-konzept.de>
		 * @param WBL_Tracker_Adapter_Interface $oAdapter
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setAdapter(WBL_Tracker_Adapter_Interface $oAdapter); // function

		/**
		 * Sets the name of the parameter.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sName
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setParameterName($sName); // function

		/**
		 * Sets the found parameter value.
		 * @author blange <code@wbl-konzept.de>
		 * @param mixed $mValue
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setParameterValue($mValue); // function

		/**
		 * Sets the used view.
		 * @author blange <code@wbl-konzept.de>
		 * @param oxUBase $oView
		 * @return WBL_Tracker_Conditions_Interface
		 */
		public function setView(oxUBase $oView); // function
	} // interface