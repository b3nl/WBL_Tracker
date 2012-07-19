<?php
	/**
	 * ./unit/modules/WBL/Tracker/Piwik/AdapterTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Piwik_Adapter.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @todo change and to basket testen
	 * @version $id$
	 */
	class WBL_Tracker_Piwik_AdapterTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Piwik_Adapter
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/modules/WBL/WBL_TestCase::getGetterAndGetterRules()
		 */
		public function getGetterAndGetterRules() {
			return array(
				array(
					'getCalls',
					'addCall',
					array(),
					array(array('test', 1.00)),
					array('[\'test\',1]')
				),
				array(
					'getCalls',
					'addCall',
					array(),
					array(array('test', 1.00), false),
					array('[test,1]')
				),
				array(
					'getConditionsHelper',
					'setConditionsHelper',
					array('sWBLTestType' => 'WBL_Tracker_Conditions_Helper'),
					array($oReturn = $this->getMock('WBL_Tracker_Conditions_Helper')),
					$oReturn
				)
			);
		} // function

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Piwik_Adapter();
		} // function

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::tearDown()
		 */
		public function tearDown() {
			$this->oFixture = null;

			parent::tearDown();
		} // function

		/**
		 * Checks the constants of the class.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testConstants() {
			$this->assertSame(
				'aWBLTrackerPiwikGoals', WBL_Tracker_Piwik_Adapter::CONFIG_KEY_GOALS
			);

			$this->assertSame(
				'aWBLTrackerPiwikPageVars', WBL_Tracker_Piwik_Adapter::CONFIG_KEY_VARS_PAGE
			);

			$this->assertSame(
				'aWBLTrackerPiwikVisitVars', WBL_Tracker_Piwik_Adapter::CONFIG_KEY_VARS_VISIT
			);

			$this->assertSame('page',  WBL_Tracker_Piwik_Adapter::SCOPE_PAGE);
			$this->assertSame('visit', WBL_Tracker_Piwik_Adapter::SCOPE_VISIT);
		} // function

		/**
		 * Checks if an array with the categorynames is returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetCatString() {
			$this->oFixture = $this->getProxyClass(get_class($this->oFixture));

			/* @var $oCat oxCategory */
			$oCat = oxNew('oxcategory');

			$oCat->setId(uniqid());
			$oCat->oxcategories__oxparentid = new oxField(uniqid());
			$oCat->oxcategories__oxtitle    = new oxField($sTitle = uniqid());
			$oCat->setParentCategory($oParent1 = oxNew('oxcategory'));

			$oParent1->setId(uniqid());
			$oParent1->oxcategories__oxparentid = new oxField(uniqid());
			$oParent1->oxcategories__oxtitle    = new oxField($sTitle1 = uniqid());
			$oParent1->setParentCategory($oParent2 = oxNew('oxcategory'));

			$oParent2->setId(uniqid());
			$oParent2->oxcategories__oxparentid = new oxField('oxrootid');
			$oParent2->oxcategories__oxtitle    = new oxField($sTitle2 = uniqid());

			$this->assertEquals(array($sTitle, $sTitle1, $sTitle2), $this->oFixture->getCatString($oCat));
		} // function

		/**
		 * Checks if an empty array is returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetCatStringDefault() {
			$this->oFixture = $this->getProxyClass(get_class($this->oFixture));

			$this->assertSame(array(), $this->oFixture->getCatString(oxNew('oxcategory')));
		} // function

		/**
		 * Checks the html getter.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetHTML() {
			$oConfig        = modConfig::getInstance();
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('getCalls'));

			$oConfig->setConfigParam('sWBLTrackerPiwikTrackerURL', $sURL = 'example.com');
			$oConfig->setConfigParam('sWBLTrackerPiwikSiteId', $sId = uniqid());
			unset($oConfig);

			$this->oFixture
				->expects($this->once())
				->method('getCalls')
				->will($this->returnValue(array('test')));
			$this->oFixture->isXHTML(false);

			$this->assertSame(
				'<script type="text/javascript">var _paq = _paq || [];(function(){ var u=(("https:" ' .
				'== document.location.protocol) ? "https://' . $sURL . '" : "http://' . $sURL. '");'.
				'_paq.push(test);_paq.push([\'trackPageView\']);_paq.push([\'enableLinkTracking\']);' .
				'var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];' .
				' g.type=\'text/javascript\'; g.defer=true; g.async=true; g.src=u+\'piwik.js\';' .
				's.parentNode.insertBefore(g,s); })();</script><noscript><p>' .
				'<img src="http://' . $sURL . 'piwik.php?idsite=' . $sId .
				'&amp;rec=1" style="border:0" alt=""></p></noscript>',
				$this->oFixture->getHTML()
			);
		} // function

		/**
		 * Checks the xHTML getter.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetXHTML() {
			$oConfig        = modConfig::getInstance();
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('getCalls'));

			$oConfig->setConfigParam('sWBLTrackerPiwikTrackerURL', $sURL = 'example.com');
			$oConfig->setConfigParam('sWBLTrackerPiwikSiteId', $sId = uniqid());
			unset($oConfig);

			$this->oFixture
				->expects($this->once())
				->method('getCalls')
				->will($this->returnValue(array('test')));
			$this->oFixture->isXHTML(true);

			$this->assertSame(
				'<script type="text/javascript">/*<![CDATA[*/var _paq = _paq || [];(function(){ var u=(("https:" ' .
				'== document.location.protocol) ? "https://' . $sURL . '" : "http://' . $sURL. '");'.
				'_paq.push(test);_paq.push([\'trackPageView\']);_paq.push([\'enableLinkTracking\']);' .
				'var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];' .
				' g.type=\'text/javascript\'; g.defer=true; g.async=true; g.src=u+\'piwik.js\';' .
				's.parentNode.insertBefore(g,s); })();/*]]>*/</script><noscript><p>' .
				'<img src="http://' . $sURL . 'piwik.php?idsite=' . $sId .
				'&amp;rec=1" style="border:0" alt="" /></p></noscript>',
				$this->oFixture->getHTML()
			);
		} // function

		/**
		 * Checks if the price is called after an update.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitDeleteBasketArticles() {
			oxSession::getInstance()->setBasket(
				$oBasket = $this->getMock(get_class($this->getOXIDModuleForWBL('oxbasket', 'WBL_Tracker_Basket')), array('getPrice'))
			);

			$oBasket
				->expects($this->once())
				->method('getPrice')
				->will($this->returnValue($oPrice = oxNew('oxprice')));

			$oBasket
				->isUpdatedForWBLTracker(true);
			unset($oBasket);

			$oPrice->setBruttoPriceMode(true);
			$oPrice->setPrice($fPrice = 200.89);
			unset($oPrice);

			$this->oFixture->init();
			$this->assertTrue(
				in_array("['trackEcommerceCartUpdate',{$fPrice}]", $this->oFixture->getCalls()),
				'The update for an empty cart is missing.'
			);
		} // function

		/**
		 * Checks if the init method calls for the vars and the goals.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 * @copyright
		 */
		public function testInitGoalAndVarDelegation() {
			$this->oFixture = $this->getMock(
				get_class($this->oFixture), array('processGoals', 'processPageVars', 'processVisitVars')
			);

			$this->oFixture
				->expects($this->once())
				->method('processGoals')
				->will($this->returnValue($this->oFixture));

			$this->oFixture
				->expects($this->once())
				->method('processPageVars')
				->will($this->returnValue($this->oFixture));

			$this->oFixture
				->expects($this->once())
				->method('processVisitVars')
				->will($this->returnValue($this->oFixture));

			$this->oFixture->init();
		} // function

		/**
		 * Checks if nothing is added when the basket is empty.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitNoBasketAction() {
			$this->getOXIDModuleForWBL('oxbasket', 'WBL_Tracker_Basket');
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('loadBasket'));

			$this->oFixture
				->expects($this->never())
				->method('loadBasket');

			$this->oFixture->init();
		} // function

		/**
		 * The basket items should be part of the tracking.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitWithBasketAndUpdate() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('getCatString'));
			$this->oFixture->withDefaultBasket(false);
			oxSession::getInstance()->setBasket(
				$oBasket = $this->getMock(
					get_class($this->getOXIDModuleForWBL('oxbasket', 'WBL_Tracker_Basket'))),
					array('getContents', 'getPrice', 'isNewItemAdded')
			);

			$this->oFixture
				->expects($this->once())
				->method('getCatString')
				->with($oCat = oxNew('oxcategory'))
				->will($this->returnValue(array($sCatTitle = uniqid())));

			$oBasket
				->expects($this->atLeastOnce())
				->method('isNewItemAdded')
				->will($this->returnValue(true));

			$oBasket
				->expects($this->once())
				->method('getPrice')
				->will($this->returnValue($oPrice = oxNew('oxprice')));

			$oPrice->setBruttoPriceMode(true);
			$oPrice->setPrice($fPrice = 200.89);
			unset($oPrice);

			$oBasket
				->expects($this->once())
				->method('getContents')
				->will($this->returnValue(array(
					/* @var $oItem1 oxBasketItem */
					$oItem1 = $this->getMock(
						get_class($this->getOXIDModuleForWBL('oxbasketitem', 'WBL_Tracker_Basket_Item')),
						array('getAmount', 'getArticle', 'getUsedWBLCategory')
					),

					/* @var $oItem2 oxBasketItem */
					$oItem2 = $this->getMock('oxbasketitem', array('getAmount', 'getArticle'))
				)));

			$oPrice1 = oxNew('oxprice');
			$oPrice1->setBruttoPriceMode();
			$oPrice1->setPrice($fPrice1 = 100);

			$oItem1
				->expects($this->any())
				->method('getAmount')
				->will($this->returnValue($iAmount1 = 1));
			$oItem1
				->expects($this->once())
				->method('getArticle')
				->with(false)
				->will($this->returnValue($oArticle1 = oxNew('oxarticle')));
			$oItem1
				->expects($this->once())
				->method('getUsedWBLCategory')
				->will($this->returnValue($oCat));
			$oItem1->setPrice($oPrice1);
			unset($oItem1, $oPrice1);

			$oArticle1->oxarticles__oxartnum = new oxField($sArtNum1 = uniqid());
			$oArticle1->oxarticles__oxtitle  = new oxField($sTitle1 = uniqid());
			unset($oArticle1);

			$oPrice2 = oxNew('oxprice');
			$oPrice2->setBruttoPriceMode();
			$oPrice2->setPrice($fPrice2 = 100.99);

			$oItem2
				->expects($this->any())
				->method('getAmount')
				->will($this->returnValue($iAmount2 = 2));
			$oItem2
				->expects($this->once())
				->method('getArticle')
				->with(false)
				->will($this->returnValue($oArticle2 = oxNew('oxarticle')));
			$oItem2->setPrice($oPrice2);
			unset($oItem1, $oPrice2);

			$oArticle2->oxarticles__oxartnum = new oxField($sArtNum2 = uniqid());
			$oArticle2->oxarticles__oxtitle  = new oxField($sTitle2 = uniqid());
			unset($oArticle2);

			unset($oBasket);
			$this->oFixture->init();

			$aCalls = $this->oFixture->getCalls();
			$this->assertTrue(
				in_array("['addEcommerceItem','{$sArtNum1}','{$sTitle1}',['{$sCatTitle}'],{$fPrice1},{$iAmount1}]", $aCalls),
				'Missing first article'
			);
			$this->assertTrue(
				in_array("['addEcommerceItem','{$sArtNum2}','{$sTitle2}',[]," . ($fPrice2*$iAmount2) . ",{$iAmount2}]", $aCalls),
				'Missing second article'
			);
			$this->assertTrue(
				in_array("['trackEcommerceCartUpdate',{$fPrice}]", $aCalls),
				'The update for an empty cart is missing.'
			);
		} // function

		/**
		 * The method should not be called, if it is no required.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitWithBasketButNoDefault() {
			$this->oFixture->withDefaultBasket(false);
			oxSession::getInstance()->setBasket(
				$oBasket = $this->getMock(
					get_class($this->getOXIDModuleForWBL('oxbasket', 'WBL_Tracker_Basket'))),
					array('getContents')
			);

			$oBasket
				->expects($this->never())
				->method('getContents')
				->will($this->returnValue(array(
					$oItem1 = oxNew('oxbasketitem'),
					$oItem2 = oxNew('oxbasketitem')
				)));

			unset($oBasket);
			$this->oFixture->init();
		} // function

		/**
		 * The basket items should be part of the tracking.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitWithBasketNoUpdate() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('getCatString'));
			$this->oFixture->withDefaultBasket(true);
			oxSession::getInstance()->setBasket(
				$oBasket = $this->getMock(
					get_class($this->getOXIDModuleForWBL('oxbasket', 'WBL_Tracker_Basket'))),
					array('getContents', 'getPrice')
			);

			$this->oFixture
				->expects($this->once())
				->method('getCatString')
				->with($oCat = oxNew('oxcategory'))
				->will($this->returnValue(array($sCatTitle = uniqid())));

			$oBasket
				->expects($this->once())
				->method('getContents')
				->will($this->returnValue(array(
					/* @var $oItem1 oxBasketItem */
					$oItem1 = $this->getMock(
						get_class($this->getOXIDModuleForWBL('oxbasketitem', 'WBL_Tracker_Basket_Item')),
						array('getAmount', 'getArticle', 'getUsedWBLCategory')
					),

					/* @var $oItem2 oxBasketItem */
					$oItem2 = $this->getMock('oxbasketitem', array('getAmount', 'getArticle'))
				)));

			$oBasket
				->expects($this->never())
				->method('getPrice');

			$oPrice1 = oxNew('oxprice');
			$oPrice1->setBruttoPriceMode();
			$oPrice1->setPrice($fPrice1 = 100);

			$oItem1
				->expects($this->any())
				->method('getAmount')
				->will($this->returnValue($iAmount1 = 1));
			$oItem1
				->expects($this->once())
				->method('getArticle')
				->with(false)
				->will($this->returnValue($oArticle1 = oxNew('oxarticle')));
			$oItem1
				->expects($this->once())
				->method('getUsedWBLCategory')
				->will($this->returnValue($oCat));
			$oItem1->setPrice($oPrice1);
			unset($oItem1, $oPrice1);

			$oArticle1->oxarticles__oxartnum = new oxField($sArtNum1 = uniqid());
			$oArticle1->oxarticles__oxtitle  = new oxField($sTitle1 = uniqid());
			unset($oArticle1);

			$oPrice2 = oxNew('oxprice');
			$oPrice2->setBruttoPriceMode();
			$oPrice2->setPrice($fPrice2 = 100.99);

			$oItem2
				->expects($this->any())
				->method('getAmount')
				->will($this->returnValue($iAmount2 = 2));
			$oItem2
				->expects($this->once())
				->method('getArticle')
				->with(false)
				->will($this->returnValue($oArticle2 = oxNew('oxarticle')));
			$oItem2->setPrice($oPrice2);
			unset($oItem1, $oPrice2);

			$oArticle2->oxarticles__oxartnum = new oxField($sArtNum2 = uniqid());
			$oArticle2->oxarticles__oxtitle  = new oxField($sTitle2 = uniqid());
			unset($oArticle2);

			unset($oBasket);
			$this->oFixture->init();

			$aCalls = $this->oFixture->getCalls();

			$this->assertTrue(
				in_array("['addEcommerceItem','{$sArtNum1}','{$sTitle1}',['{$sCatTitle}'],{$fPrice1},{$iAmount1}]", $aCalls),
				'Missing first article'
			);
			$this->assertTrue(
				in_array("['addEcommerceItem','{$sArtNum2}','{$sTitle2}',[]," . ($fPrice2*$iAmount2) . ",{$iAmount2}]", $aCalls),
				'Missing second article'
			);
		} // function

		/**
		 * Checks if the init call trys to track a category.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitWithCategory() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('loadECommerceViews'));

			$this->oFixture
				->expects($this->once())
				->method('loadECommerceViews')
				->with($oView = oxNew('oxubase'));

			$this->oFixture->setView($oView)->withDefaultCat(true);
			unset($oView);

			$this->oFixture->init();
		} // function

		/**
		 * Checks if the basket from the session is used without a parameter.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadBasketNoParam() {
			oxSession::getInstance()->setBasket($oBasket = $this->getMock('oxbasket'));

			$oBasket
				->expects($this->never())
				->method('getContents');
			unset($oBasket);

			$this->oFixture = $this->getProxyClass(get_class($this->oFixture));
			$this->assertSame($this->oFixture, $this->oFixture->loadBasket());
		} // function

		/**
		 * Checks if a categeory page is tracked correctly.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadECommerceViewsCategory() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall', 'getCatString', 'getView')
			);

			$this->oFixture
				->expects($this->once())
				->method('addCall')
				->with(array('setEcommerceView', false, false, $aData = array(uniqid())));

			$this->oFixture
				->expects($this->once())
				->method('getCatString')
				->with($oCat = oxNew('oxcategory'))
				->will($this->returnValue($aData));

			$this->oFixture
				->expects($this->once())
				->method('getView')
				->will($this->returnValue($oView = oxNew('oxubase')));

			/* @var $oView oxUBase */
			$oView->setActiveCategory($oCat);
			unset($oCat, $oView);

			$this->oFixture->withDefaultArticle(false);
			$this->oFixture->withDefaultCat(true);

			$this->assertSame($this->oFixture, $this->oFixture->loadECommerceViews());
		} // function

		/**
		 * Checks the default return.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadeCommerceViewsDefault() {
			$this->oFixture = $this->getProxyClass(get_class(
				$this->getMock(get_class($this->oFixture), array('getView', 'withDefaultArticle'))
			));

			$this->oFixture
				->expects($this->once())
				->method('getView')
				->will($this->returnValue(null));

			$this->oFixture
				->expects($this->never())
				->method('withDefaultArticle');

			$this->assertSame($this->oFixture, $this->oFixture->loadECommerceViews());
		} // function

		/**
		 * Checks if a details page is tracked correctly.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadECommerceViewsProductWithCat() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall', 'getCatString', 'getView')
			);

			/* @var $oProduct oxArticle */
			$oProduct = oxNew('oxarticle');
			$oProduct->oxarticles__oxartnum = new oxField($sArtNum = uniqid());
			$oProduct->oxarticles__oxtitle  = new oxField($sTitle = uniqid());
			$oProduct->setPrice($oPrice = oxNew('oxprice'));

			$oPrice->setBruttoPriceMode();
			$oPrice->setPrice($fPrice = 123.99);
			unset($oPrice);

			$this->oFixture
				->expects($this->once())
				->method('addCall')
				->with(array('setEcommerceView', $sArtNum, $sTitle, $aData = array(uniqid()), $fPrice));

			$this->oFixture
				->expects($this->once())
				->method('getCatString')
				->with($oCat = oxNew('oxcategory'))
				->will($this->returnValue($aData));

			$this->oFixture
				->expects($this->once())
				->method('getView')
				->will($this->returnValue($oView = $this->getMock('oxubase', array('getProduct'))));

			$oView
				->expects($this->once())
				->method('getProduct')
				->will($this->returnValue($oProduct));
			unset($oProduct);

			/* @var $oView oxUBase */
			$oView->setActiveCategory($oCat);
			unset($oCat, $oView);

			$this->oFixture->withDefaultArticle(true);
			$this->oFixture->withDefaultCat(false);

			$this->assertSame($this->oFixture, $this->oFixture->loadECommerceViews());
		} // function

		/**
		 * Checks if a details page is tracked correctly without category data and a price.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadECommerceViewsProductWithoutCatAndPrice() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall', 'getView')
			);

			/* @var $oProduct oxArticle */
			$oProduct = $this->getMock('oxarticle', array('getPrice'));
			$oProduct->oxarticles__oxartnum = new oxField($sArtNum = uniqid());
			$oProduct->oxarticles__oxtitle  = new oxField($sTitle = uniqid());

			$this->oFixture
				->expects($this->once())
				->method('addCall')
				->with(array('setEcommerceView', $sArtNum, $sTitle, array(), null));

			$this->oFixture
				->expects($this->once())
				->method('getView')
				->will($this->returnValue($oView = $this->getMock('oxubase', array('getActiveCategory', 'getProduct'))));

			$oView
				->expects($this->once())
				->method('getProduct')
				->will($this->returnValue($oProduct));
			unset($oProduct, $oView);

			$this->oFixture->withDefaultArticle(true);
			$this->oFixture->withDefaultCat(false);

			$this->assertSame($this->oFixture, $this->oFixture->loadECommerceViews());
		} // function

		/**
		 * Checks if the goals are correctly fetched.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessGoals() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall')
			);

			modConfig::getInstance()->setConfigParam(WBL_Tracker_Piwik_Adapter::CONFIG_KEY_GOALS, array(
				2 => sprintf(
					'%s|%s|%s|%s',
					$sCheck = uniqid(),
					$sView  = 'start',
					$sField = uniqid(),
					$sValue = uniqid()
				)
			));

			$this->oFixture
				->expects($this->once())
				->method('addCall')
				->with(array('trackGoal', 2, $sValue));

			$this->oFixture->setConditionsHelper(
				$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('checkCondition'))
			);

			$oHelper
				->expects($this->once())
				->method('checkCondition')
				->with($sCheck, $sView, $sField)
				->will($this->returnValue(true));
			unset($oHelper);

			$this->assertSame($this->oFixture, $this->oFixture->processGoals(), 'fluent interface failed.');
		} // function

		/**
		 * Checks if the goals are correctly fetched.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessGoalsWithValueFromCallback() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall')
			);

			modConfig::getInstance()->setConfigParam(WBL_Tracker_Piwik_Adapter::CONFIG_KEY_GOALS, array(
				2 => sprintf(
					'%s|%s|%s|%s',
					$sCheck = uniqid(),
					$sView  = 'start',
					$sField = uniqid(),
					$sValue = uniqid()
				)
			));

			$this->oFixture->setConditionsHelper(
				$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('checkCondition', 'getCallback'))
			);

			$oHelper
				->expects($this->once())
				->method('checkCondition')
				->with($sCheck, $sView, $sField)
				->will($this->returnValue(true));

			$oHelper
				->expects($this->once())
				->method('getCallback')
				->with($sValue)
				->will($this->returnValue($oCall = $this->getMock('WBL_Tracker_Conditions_Value_Interface')));
			unset($oHelper);

			$oCall->expects($this->once())->method('getValue')->will($this->returnValue( $sCallValue = uniqid()));
			unset($oCall);

			$this->oFixture
				->expects($this->once())
				->method('addCall')
				->with(array('trackGoal', 2, $sCallValue));

			$this->assertSame($this->oFixture, $this->oFixture->processGoals(), 'fluent interface failed.');
		} // function

		/**
		 * Checks if nothing is done, if the config is empty.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessGoalsEmptyArray() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall')
			);

			modConfig::getInstance()->setConfigParam(WBL_Tracker_Piwik_Adapter::CONFIG_KEY_GOALS, '');

			$this->oFixture
				->expects($this->never())
				->method('addCall');;

			$this->oFixture->setConditionsHelper(
				$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('checkCondition'))
			);

			$oHelper
				->expects($this->never())
				->method('checkCondition');
			unset($oHelper);

			$this->assertSame($this->oFixture, $this->oFixture->processGoals(), 'fluent interface failed.');
		} // function

		/**
		 * Checks if the custom vars are correctly fetched.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessPageVars() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('processVars')
			);

			$this->oFixture
				->expects($this->once())
				->method('processVars')
				->with(WBL_Tracker_Piwik_Adapter::CONFIG_KEY_VARS_PAGE)
				->will($this->returnValue($this->oFixture));

			$this->assertSame($this->oFixture, $this->oFixture->processPageVars(), 'fluent interface failed.');
		} // function

		/**
		 * Checks if the custom vars are correctly fetched.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessVars() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall')
			);

			modConfig::getInstance()->setConfigParam($sConfig = uniqid(), array(
				2 => sprintf(
					'%s|%s|%s|%s|%s',
					$sName = uniqid(),
					$sValue = uniqid(),
					$sCheck = uniqid(),
					$sView = 'start',
					$sField = uniqid()
				)
			));

			$this->oFixture->setConditionsHelper(
				$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('checkCondition', 'getCallback'))
			);

			$oHelper
				->expects($this->once())
				->method('checkCondition')
				->with($sCheck, $sView, $sField)
				->will($this->returnValue(true));

			$oHelper
				->expects($this->once())
				->method('getCallback')
				->with($sValue)
				->will($this->returnValue($oCall = $this->getMock('WBL_Tracker_Conditions_Value_Interface')));
			unset($oHelper);

			$oCall->expects($this->once())->method('getValue')->will($this->returnValue( $sCallValue = uniqid()));
			unset($oCall);

			$this->oFixture
				->expects($this->once())
				->method('addCall')
				->with(array('setCustomVariable', 2, $sName, $sCallValue, WBL_Tracker_Piwik_Adapter::SCOPE_PAGE));

			$this->assertSame($this->oFixture, $this->oFixture->processVars($sConfig), 'fluent interface failed.');
		} // function

		/**
		 * Checks if nothing happens, when the config array is empty.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessVarsEmpty() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall')
			);

			modConfig::getInstance()->setConfigParam($sConfig = uniqid(), '');

			$this->oFixture
				->expects($this->never())
				->method('addCall');

			$this->oFixture->setConditionsHelper(
				$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('checkCondition'))
			);

			$oHelper
				->expects($this->never())
				->method('checkCondition');
			unset($oHelper);

			$this->assertSame($this->oFixture, $this->oFixture->processVars($sConfig), 'fluent interface failed.');
		} // function

/**
		 * Checks if the custom vars are correctly fetched.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessVarsWithCallback() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('addCall')
			);

			modConfig::getInstance()->setConfigParam($sConfig = uniqid(), array(
				2 => sprintf(
					'%s|%s|%s|%s|%s',
					$sName = uniqid(),
					$sValue = uniqid(),
					$sCheck = uniqid(),
					$sView = 'start',
					$sField = uniqid()
				)
			));

			$this->oFixture
				->expects($this->once())
				->method('addCall')
				->with(array('setCustomVariable', 2, $sName, $sValue, WBL_Tracker_Piwik_Adapter::SCOPE_PAGE));

			$this->oFixture->setConditionsHelper(
				$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('checkCondition'))
			);

			$oHelper
				->expects($this->once())
				->method('checkCondition')
				->with($sCheck, $sView, $sField)
				->will($this->returnValue(true));
			unset($oHelper);

			$this->assertSame($this->oFixture, $this->oFixture->processVars($sConfig), 'fluent interface failed.');
		} // function

		/**
		 * Checks if the custom vars are correctly fetched.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessVisitVars() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('processVars')
			);

			$this->oFixture
				->expects($this->once())
				->method('processVars')
				->with(WBL_Tracker_Piwik_Adapter::CONFIG_KEY_VARS_VISIT, WBL_Tracker_Piwik_Adapter::SCOPE_VISIT)
				->will($this->returnValue($this->oFixture));

			$this->assertSame($this->oFixture, $this->oFixture->processVisitVars(), 'fluent interface failed.');
		} // function

		/**
		 * Checks if the adapter is set even without a view.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testSetConditionsHelperNoView() {
			$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('setAdapter'));

			$oHelper
				->expects($this->once())
				->method('setAdapter')
				->with($this->oFixture);

			$this->oFixture->setConditionsHelper($oHelper);
			unset($oHelper);
		} // function

		/**
		 * Checks the full call.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testSetConditionsHelperWithView() {
			$oHelper = $this->getMock('WBL_Tracker_Conditions_Helper', array('setAdapter', 'setView'));

			$oHelper
				->expects($this->once())
				->method('setAdapter')
				->with($this->oFixture);

			$oHelper
				->expects($this->once())
				->method('setView')
				->with($oView = new Start());

			$this->oFixture->setView($oView);
			unset($oView);

			$this->oFixture->setConditionsHelper($oHelper);
			unset($oHelper);
		} // function

		/**
		 * Checks if the tracker settings are mirrored in the metadata.php.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testSettingsFromMetaData() {
			require_once getShopBasePath() . '/modules/WBL/Tracker/metadata.php';
			$aFound = array();

			if ($aModule['settings']) {
				foreach ($aModule['settings'] as $aConfig) {
					if ($aConfig['group'] === 'WBL_Tracker_Piwik') {
						$aFound[] = $aConfig;
					} // if
				} // foreach
			} // if

			$this->assertEquals(
				array(
					array('group' => 'WBL_Tracker_Piwik', 'name' => 'bIsWBLTrackerPiwikActive',    'type' => 'bool',  'value' => false),
					array('group' => 'WBL_Tracker_Piwik', 'name' => 'aWBLTrackerPiwikGoals',       'type' => 'aarr',  'value' => array()),
					array('group' => 'WBL_Tracker_Piwik', 'name' => 'sWBLTrackerPiwikTrackerURL',  'type' => 'str',   'value' => 'example.com'),
					array('group' => 'WBL_Tracker_Piwik', 'name' => 'sWBLTrackerPiwikSiteId',      'type' => 'str',   'value' => 0),
					array('group' => 'WBL_Tracker_Piwik', 'name' => 'aWBLTrackerPiwikPageVars',    'type' => 'aarr',  'value' => array(
						1 => 'searchhits|WBL_Tracker_Conditions_Value_Search_Hits||search',
						2 => 'searchquery|WBL_Tracker_Conditions_Value_Search_Query||search',
						4 => 'orderpayment|WBL_Tracker_Conditions_Value_OrderPayment||thankyou',
						5 => 'possiblepayments|WBL_Tracker_Conditions_Value_Search_PossiblePayments||payment'
					)),
					array('group' => 'WBL_Tracker_Piwik', 'name' => 'aWBLTrackerPiwikVisitVars',   'type' => 'aarr',  'value' => array(
						3 => 'userstatus|WBL_Tracker_Conditions_Value_UserStatus'
					)),
				),
				$aFound
			);
		} // function

		/**
		 * Checks the return values.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testWithDefaultArticle() {
			$this->assertFalse($this->oFixture->withDefaultArticle(), '1. Check failed.');
			$this->assertFalse($this->oFixture->withDefaultArticle(true), '2. Check failed.');
			$this->assertTrue($this->oFixture->withDefaultArticle(), '3. Check failed.');
			$this->assertTrue($this->oFixture->withDefaultArticle(false), '4. Check failed.');
			$this->assertFalse($this->oFixture->withDefaultArticle(), '5. Check failed.');
		} // function

		/**
		 * Checks the return values.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testWithDefaultBasket() {
			$this->assertFalse($this->oFixture->withDefaultBasket(), '1. Check failed.');
			$this->assertFalse($this->oFixture->withDefaultBasket(true), '2. Check failed.');
			$this->assertTrue($this->oFixture->withDefaultBasket(), '3. Check failed.');
			$this->assertTrue($this->oFixture->withDefaultBasket(false), '4. Check failed.');
			$this->assertFalse($this->oFixture->withDefaultBasket(), '5. Check failed.');
		} // function

		/**
		 * Checks the return values.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testWithDefaultCat() {
			$this->assertFalse($this->oFixture->withDefaultCat(), '1. Check failed.');
			$this->assertFalse($this->oFixture->withDefaultCat(true), '2. Check failed.');
			$this->assertTrue($this->oFixture->withDefaultCat(), '3. Check failed.');
			$this->assertTrue($this->oFixture->withDefaultCat(false), '4. Check failed.');
			$this->assertFalse($this->oFixture->withDefaultCat(), '5. Check failed.');
		} // function
	} // class