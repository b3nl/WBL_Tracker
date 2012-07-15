<?php
	/**
	 * ./modules/WBL/Tracker/Adapter/Interface.php
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version SVN: $id$
	 */

	/**
	 * API of the Tracker-Adapter.
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version SVN: $id$
	 */
	interface WBL_Tracker_Adapter_Interface
	{
		/**
		 * Returns the tracker HTML.
		 * @author blange <code@wbl-konzept.de>
		 * @return string
		 */
		public function getHTML(); // function

		/**
		 * Inits the adapter after setting the vars of the framework.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Adapter_Interface
		 */
		public function init(); // function

		/**
		 * Additional check if this adapter is allowed for the given class.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sclass
		 * @return bool
		 */
		public function isForClass($sclass); // function

		/**
		 * Should this tracker render xHTML?
		 * @author blange <code@wbl-konzept.de>
		 * @param  $bNewState The new state.
		 * @return bool The old state.
		 * @todo   USe!
		 */
		public function isXHTML($bIsXHTML = false); // function

		/**
		 * Sets the used smarty instance.
		 * @author blange <code@wbl-konzept.de>
		 * @param Smarty $oSmarty
		 * @return WBL_Tracker_Adapter_Interface
		 */
		public function setSmarty(Smarty $oSmarty); // function

		/**
		 * Sets the active view.
		 * @author blange <code@wbl-konzept.de>
		 * @param oxUBase $oView
		 * @return WBL_Tracker_Adapter_Interface
		 */
		public function setView(oxUBase $oView); // function

		/**
		 * Sets the used view data.
		 * @author blange <code@wbl-konzept.de>
		 * @param array $aData
		 * @return WBL_Tracker_Adapter_Interface
		 */
		public function setViewData(array $aData); // function
	} // interface
