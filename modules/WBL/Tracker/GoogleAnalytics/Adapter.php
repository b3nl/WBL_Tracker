<?php
	/**
	 * ./modules/WBL/Tracker/GoogleAnalytics/Adapter.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage GoogleAnalytics
	 * @version SVN: $Id$
	 */

	/**
	 * Standard-Adapter for Google Analytics.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage GoogleAnalytics
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_GoogleAnalytics_Adapter extends WBL_Tracker_Adapter_Abstract {
		/**
		 * (non-PHPdoc)
		 * @see www/modules/WBL/Tracker/Adapter/WBL_Tracker_Adapter_Abstract::init()
		 */
		public function init() {
			parent::init();

			$oConfig = $this->getConfig();

			$this->addTrackerCall(array('_setAccount', $oConfig->getConfigParam('sWBLTrackerGATrackingId')));

			if ($oConfig->getConfigParam('bWBLTrackerGAAnonymize')) {
				$this->addTrackerCall(array('_gat._anonymizeIp'));
			} // if

			unset($oConfig);

			return $this->addTrackerCall(array('_trackPageview'));
		} // function

		/**
		 * Returns the tracker HTML.
		 * @author blange <code@wbl-konzept.de>
		 * @return string
		 */
		public function getHTML() {
			$oConfig = $this->getConfig();
			$sHTML   = '<script type="text/javascript">/* <![CDATA[*/' .
				'var _gaq = _gaq || [];';

			foreach ($this->getTrackerCalls() as $sCall) {
				$sHTML .= '_gaq.push(' . $sCall . ');';
			} // foreach

			return $sHTML .
				'(function() { ' .
					'var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true; ' .
					'ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";' .
					'var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);' .
				'})();' .
				'/* ]]>*/</script>';
		} // function
	} // class