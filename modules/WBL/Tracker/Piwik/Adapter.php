<?php
	/**
	 * ./modules/WBL/Tracker/Standard.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @version SVN: $Id$
	 */

	/**
	 * Standard-Adapter.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Piwik_Adapter extends WBL_Tracker_Adapter_Abstract {
		/**
		 * The array for the piwik _paq calls.
		 * @var array
		 */
		protected $aCalls = array();

		/**
		 * Can the tracker use the default api to get the article.
		 * @var bool
		 */
		protected $bWithDefaultArticle = false;

		/**
		 * Can the tracker use the default api to get the basket.
		 * @var bool
		 */
		protected $bWithDefaultBasket = false;

		/**
		 * Can the tracker use the default api to get the category.
		 * @var bool
		 */
		protected $bWithDefaultCat = false;

		/**
		 * The helper for goal and var conditions.
		 * @var WBL_Tracker_Conditions_Helper Filled with getter.
		 */
		protected $oConditionsHelper = null;

		/**
		 * Getting the goal rules of the config.
		 * @var string
		 */
		const CONFIG_KEY_GOALS = 'aWBLTrackerPiwikGoals';

		/**
		 * Getting the page vars from the config.
		 * @var string
		 */
		const CONFIG_KEY_VARS_PAGE = 'aWBLTrackerPiwikPageVars';

		/**
		 * Getting the page vars from the config.
		 * @var string
		 */
		const CONFIG_KEY_VARS_VISIT = 'aWBLTrackerPiwikVisitVars';

		/**
		 * The values of this scope are valid for the single hit.
		 * @var string
		 */
		const SCOPE_PAGE = 'page';

		/**
		 * The values of this scope are valid for the whole session.
		 * @var string
		 */
		const SCOPE_VISIT = 'visit';

		/**
		 * Adds a call for the piwik tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @param  array $aCallData
		 * @param  bool  $bWithParsing Should a helper parse the php values to javascript parts?
		 * @return WBL_Tracker_Piwik_Adapter_Standard
		 */
		public function addCall(array $aCallData, $bWithParsing = true) {
			$this->aCalls[] = $bWithParsing
				? wblNew('WBL_Tracker_Helper')->parseValueToJSPart($aCallData)
				: '[' . implode(',', $aCallData) . ']';

			return $this;
		} // function

		/**
		 * Checks if there is a basket which can be used for the ecommerce tracking of piwik.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		protected function checkAndLoadBasket() {
			$oBasket = $this->getSession()->getBasket();

			if (($oBasket instanceof WBL_Tracker_Basket) && (($bNewItemAdded = $oBasket->isNewItemAdded()) ||
				($oBasket->isUpdatedForWBLTracker()) || $this->withDefaultBasket()))
			{
				$this->loadBasket($oBasket);
			} // if
			unset($oBasket);

			return $this;
		} // function

		/**
		 * Checks if there is a view which can be used for the ecommerce views of piwik.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		protected function checkAndLoadECommerceViews() {
			if (($this->withDefaultCat() || $this->withDefaultArticle()) && ($oView = $this->getView())) {
				$this->loadECommerceViews($oView);
			} // if

			return $this;
		} // function

		/**
		 * Returns the array with the piwik calls.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 */
		public function getCalls() {
			return $this->aCalls;
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
		 * Returns the helper for goals and vars.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Conditions_Helper
		 */
		protected function getConditionsHelper() {
			if (!$this->oConditionsHelper) {
				$this->setConditionsHelper(wblNew('WBL_Tracker_Conditions_Helper'));
			} // if

			return $this->oConditionsHelper;
		} // function

		/**
		 * Returns the tracker HTML.
		 * @author blange <code@wbl-konzept.de>
		 * @return string
		 */
		public function getHTML() {
			$bIsXML  = $this->isXHTML();
			$oConfig = $this->getConfig();
			$sURL    = (string) $oConfig->getConfigParam('sWBLTrackerPiwikTrackerURL');
			$sHTML   = '<script type="text/javascript">';

			if ($bIsXML) {
				$sHTML .= '/*<![CDATA[*/';
			} // if

			$sHTML .= 'var _paq = _paq || [];' .
				'(function(){ var u=(("https:" == document.location.protocol) ? "https://' .
				$sURL . '" : "http://' . $sURL . '");';

			foreach ($this->getCalls() as $sCall) {
				$sHTML .= '_paq.push(' . $sCall . ');';
			} // foreach

			$sHTML .= '_paq.push([\'trackPageView\']);' .
			'_paq.push([\'enableLinkTracking\']);' .
			'var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0]; ' .
				'g.type=\'text/javascript\'; g.defer=true; g.async=true; g.src=u+\'piwik.js\';' .
				's.parentNode.insertBefore(g,s); })();';

			if ($bIsXML) {
				$sHTML .= '/*]]>*/';
			} // if

			$sHTML .= '</script>' .
				'<noscript><p><img src="' . ($oConfig->isSsl() ? 'https' : 'http') . '://' . $sURL .
					'piwik.php?idsite=' . $oConfig->getConfigParam('sWBLTrackerPiwikSiteId') .
					'&amp;rec=1" style="border:0" alt=""';

			return $sHTML . ($bIsXML ? ' /' : '') . '></p></noscript>';
		} // function

		/**
		 * (non-PHPdoc)
		 * @see http/modules/WBL/Tracker/Adapter/WBL_Tracker_Adapter_Abstract::init()
		 */
		public function init() {
			$oView = $this->getView();

			$this
				->addCall(array('setSiteId', (int) $this->getConfig()->getConfigParam('sWBLTrackerPiwikSiteId')))
				->addCall(array("'setTrackerUrl'", "u+'piwik.php'"), false)
				->checkAndLoadECommerceViews()
				->checkAndLoadBasket()
				->processGoals()
				->processPageVars()
				->processVisitVars();

			return $this;
		} // function

		/**
		 * Adds the basket data to the piwik call.
		 * @author blange <code@wbl-konzept.de>
		 * @param  WBL_Tracker_Basket $oBasket
		 * @return WBL_Tracker_Piwik_Adapter_Standard
		 */
		protected function loadBasket(WBL_Tracker_Basket $oBasket = null) {
			if (!$oBasket) {
				$oBasket = $this->getSession()->getBasket();
			} //if

			if ($oBasket instanceof WBL_Tracker_Basket) {
				if ($aItems = $oBasket->getContents()) {
					/* @var $oItem oxBasketItem */
					foreach ($aItems as $oItem) {
						$oArticle = $oItem->getArticle(false);
						$oPrice   = $oItem->getPrice();
						$this->addCall(array(
							'addEcommerceItem', $oArticle->oxarticles__oxartnum->value,
							$oArticle->oxarticles__oxtitle->value,
								($oItem instanceof WBL_Tracker_Basket_Item && ($oCat = $oItem->getUsedWBLCategory()))
									? $this->getCatString($oCat)
									: array()
							,
							$oPrice->getBruttoPrice(), $oItem->getAmount()
						));
					} // foreach
					unset($aItems, $oArticle, $oItem, $oPrice);
				} // if

				if ($oBasket->isNewItemAdded() || $oBasket->isUpdatedForWBLTracker()) {
					$this->addCall(array(
						'trackEcommerceCartUpdate', $oBasket->getPrice()->getBruttoPrice()
					));
				} // if
			} // if

			return $this;
		} // function

		/**
		 * Adds the ecommerce values of the view to the tracker.
		 * @author blange <code@wbl-konzept.de>
		 * @param oxUBase $oView
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		protected function loadECommerceViews(oxUBase $oView = null) {
			if (!$oView) {
				$oView = $this->getView();
			} // if

			if ($oView) {
				$bWithArticle = $this->withDefaultArticle();
				$aCat         = (($this->withDefaultCat() || $bWithArticle) && $oCat = $oView->getActiveCategory())
					? $this->getCatString($oCat)
					: array();

				/* @var $oProduct oxArticle */
				if ($bWithArticle && $oProduct = $oView->getProduct()) {
					$oPrice = $oProduct->getPrice();

					$this->addCall(array(
						'setEcommerceView',
						$oProduct->oxarticles__oxartnum->value,
						$oProduct->oxarticles__oxtitle->value,
						$aCat,
						$oPrice ? $oPrice->getBruttoPrice() : null
					));
				} elseif ($aCat) {
					$this->addCall(array('setEcommerceView', false, false, $aCat));
				} // else
			} // if

			return $this;
		} // function

		/**
		 * Processes the goals from the config.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		protected function processGoals() {
			$aConfig = array_filter((array) $this->getConfig()->getConfigParam(self::CONFIG_KEY_GOALS));
			$oHelper = $this->getConditionsHelper();

			foreach ($aConfig as $iIndex => $sRules) {
@				list($sCheck, $mView, $mParam, $mValue) = explode('|', $sRules);

				if ($oHelper->checkCondition(trim($sCheck), trim($mView), $sParam = trim($mParam))) {
					$aCall = array('trackGoal', $iIndex);

					if ($sValue = trim($mValue)) {
						if (($oCall = $oHelper->getCallback($sValue, $sParam)) instanceof WBL_Tracker_Conditions_Value_Interface) {
							$sValue = $oCall->getValue();
						} // if

						$aCall[] = $sValue;
					} // if

					$this->addCall($aCall);
				} // if
			} // foreach

			return $this;
		} // function

		/**
		 * Processes the page vars from the config.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		protected function processPageVars() {
			return $this->processVars(self::CONFIG_KEY_VARS_PAGE);

			return $this;
		} // function

		/**
		 * Processes the vars from the config.
		 * @author blange <code@wbl-konzept.de>
		 * @param  string $sConfig The name of the config var.
		 * @param  string $sScopde The piwik scope.
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		protected function processVars($sConfig, $sScope = self::SCOPE_PAGE) {
			$aConfig = array_filter((array) $this->getConfig()->getConfigParam($sConfig));
			$oHelper = $this->getConditionsHelper();

			foreach ($aConfig as $iIndex => $sRules) {
@				list($sName, $mValue, $mCheck, $mView, $mParam) = explode('|', $sRules);

				if ($oHelper->checkCondition(trim($mCheck), trim($mView), $sParam = trim($mParam))) {
					$oCall = $oHelper->getCallback($sValue = trim($mValue), $sParam);

					if ($oCall instanceof WBL_Tracker_Conditions_Value_Interface) {
						$sValue = $oCall->getValue();
					} // if
					unset($oCall);

					$this->addCall(
						array('setCustomVariable', $iIndex, $sName, $sValue, $sScope)
					);
				} // if
			} // foreach

			return $this;
		} // function

		/**
		 * Processes the visit vars from the config.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		protected function processVisitVars() {
			return $this->processVars(self::CONFIG_KEY_VARS_VISIT, self::SCOPE_VISIT);
		} // function

		/**
		 * Sets the helper for the goals and the vars.
		 * @author blange <code@wbl-konzept.de>
		 * @param WBL_Tracker_Conditions_Helper $oHelper
		 * @return WBL_Tracker_Piwik_Adapter
		 */
		public function setConditionsHelper(WBL_Tracker_Conditions_Helper $oHelper) {
			$this->oConditionsHelper = $oHelper;

			$oHelper->setAdapter($this);

			if ($oView = $this->getView()) {
				$oHelper->setView($oView);
				unset($oView);
			} // if

			unset($oHelper);

			return $this;
		} // function

		/**
		 * Can the tracker use the default api to get the article.
		 * @author blange <code@wbl-konzept.de>
		 * @param bool $bNewState The new state.
		 * @return bool The old state
		 */
		public function withDefaultArticle($bNewState = true) {
			$bOldState = $this->bWithDefaultArticle;

			if (func_num_args()) {
				$this->bWithDefaultArticle = $bNewState;
			} // if

			return $bOldState;
		} // function

		/**
		 * Can the tracker use the default api to get the basket.
		 * @author blange <code@wbl-konzept.de>
		 * @param bool $bNewState The new state.
		 * @return bool The old state
		 */
		public function withDefaultBasket($bNewState = true) {
			$bOldState = $this->bWithDefaultBasket;

			if (func_num_args()) {
				$this->bWithDefaultBasket = $bNewState;
			} // if

			return $bOldState;
		} // function

		/**
		 * Can the tracker use the default api to get the category.
		 * @author blange <code@wbl-konzept.de>
		 * @param bool $bNewState The new state.
		 * @return bool The old state
		 */
		public function withDefaultCat($bNewState = true) {
			$bOldState = $this->bWithDefaultCat;

			if (func_num_args()) {
				$this->bWithDefaultCat = $bNewState;
			} // if

			return $bOldState;
		} // function
	} // class