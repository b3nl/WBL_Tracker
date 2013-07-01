<?php
	/**
	 * ./modules/WBL/Tracker/Piwik/Basket.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik_Adapter
	 * @version SVN: $Id$
	 */

	/**
	 * Tracker for the Basket-page.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik_Adapter
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Piwik_Adapter_Basket extends WBL_Tracker_Piwik_Adapter {
		/**
		 * Can the tracker use the default api to get the category.
		 * @var bool
		 */
		protected $bWithDefaultBasket = true;
	} // class