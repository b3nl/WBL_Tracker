<?php
	/**
	 * ./modules/WBL/Tracker/Conditions/Value/Search/Query.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */

	/**
	 * Returns the query for a search.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Conditions_Value_Search_Query extends WBL_Tracker_Conditions_Abstract
		implements WBL_Tracker_Conditions_Value_Interface {
		/**
		 * Returns a special value for a tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @return mixed
		 */
		public function getValue() {
			/* @var $oView Search */
			return (($oView = $this->getView()) instanceof Search)
				? (string) $oView->getSearchParamForHtml()
				: '';
		} // function
	} // class