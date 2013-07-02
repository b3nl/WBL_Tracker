<?php
	/**
	 * ./modules/WBL/Tracker/Piwik/Details.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik_Adapter
	 * @version SVN: $Id$
	 */

	/**
	 * Tracker for the Details-page.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik_Adapter
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Piwik_Adapter_Details extends WBL_Tracker_Piwik_Adapter {
		/**
		 * Can the tracker use the default api to get the article.
		 * @var bool
		 */
		protected $bWithDefaultArticle = true;

		/**
		 * Can the tracker use the default api to get the category.
		 * @var bool
		 */
		protected $bWithDefaultCat = true;

		/**
		 * Changes the URL addditionally.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Piwik_Adapter_Details
		 */
		public function init() {
			$oParent  = parent::init();
			$oProduct = $this->getView()->getProduct();

			$this->addTrackerCall(array('"setCustomUrl"', 'window.location'), false);

			if ($oProduct) {
				$this->addTrackerCall(
					array(
						'setDocumentTitle',
						trim($oProduct->oxarticles__oxtitle->value . ' ' . $oProduct->oxarticles__oxvarselect->value)
					)
				);
			} // if
			unset($oProduct);

			return $oParent;
		} // function
	} // class