<?php
	/**
	 * ./modules/WBL/Tracker/Helper/Rights.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Helper
	 * @version SVN: $Id$
	 */

	/**
	 * Workaround to know the result of the "oxhasrights"-Block.
	 *
	 * OXID lacks an API to get the internal result of oxhasrights in OXID EE. And this internal
	 * internal is missing in lower version (CE, PE) but the template block exists, so there is
	 * no other way, than to simulate a template call for knowing the result of this block.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Helper
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Helper_Rights extends oxSuperCfg {
		/**
		 * The path to the used template.
		 * @var string
		 */
		protected $sTpl = '';

		/**
		 * Ths string is used as the template var name for the check ident.
		 * @var string
		 */
		const DEFAULT_TEMPLATE_VAR_NAME = 'sTrackerHelperRightsIdent';

		/**
		 * Returns the used template which should evaluate to a boolean value.
		 * @author blange <code@wbl-konzept.de>
		 * @return string
		 */
		protected function getCheckTemplate() {
			if (!$this->sTpl) {
				$this->setCheckTemplate(realpath(dirname(__FILE__) . '/_files/rights.tpl'));
			} // if

			return $this->sTpl;
		} // function

		/**
		 * Returns the result of the oxhasrights-Block.
		 * @author blange <code@wbl-konzept.de>
		 * @param  string $sIdent The ident for the check.
		 * @param  Smarty $oSmarty
		 * @return bool
		 */
		public function hasRight($sIdent, Smarty $oSmarty = null) {
			if (!$oSmarty) {
				$oSmarty = oxUTilsView::getInstance()->getSmarty();
			} // if

			$oSmarty->assign($sKey = self::DEFAULT_TEMPLATE_VAR_NAME, $sIdent);
			return (bool) trim($oSmarty->fetch(
				$this->getCheckTemplate(),
				$sKey . '_' . $sIdent . '_' . uniqid()
			));
		} // function

		/**
		 * Sets the used template which should evaluate to a boolean value.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sTpl The used template.
		 * @return string
		 */
		public function setCheckTemplate($sTpl) {
			$this->sTpl = $sTpl;

			return $this;
		} // function
	} // class