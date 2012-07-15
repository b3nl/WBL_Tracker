<?php
	/**
	 * ./unit/modules/WBL/Tracker/Piwik/Adapter/AlistTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Piwik_Adapter
	 * @version $id$
	 */

	require_once realpath(dirname(__FILE__) . '/../../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Piwik_Adapter_Thankyou.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @version $id$
	 */
	class WBL_Tracker_Piwik_Adapter_ThankyouTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Piwik_Adapter_Thankyou
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = wblNew('WBL_Tracker_Piwik_Adapter_Thankyou');
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
		 * The whole order should be tracked.
		 * @author blange <code@wbl-konzept.de>
		 * @expectedException Exception
		 * @return void
		 */
		public function testInitFull() {
			modConfig::getInstance()->setConfigParam('sWBLTrackerPiwikSiteId', $iSiteId = mt_rand(1, 100));
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('addCall', 'loadBasket'));

			// this calls need to be made prior the order.
			$this->oFixture
				->expects($this->at(0))
				->method('addCall')
				->with(array('setSiteId', $iSiteId))
				->will($this->returnValue($this->oFixture));

			$this->oFixture
				->expects($this->at(1))
				->method('addCall')
				->with(array("'setTrackerUrl'", "u+'piwik.php'"), false)
				->will($this->returnValue($this->oFixture));

			$this->oFixture
				->expects($this->at(3))
				->method('addCall')
				->with(array(
					'trackEcommerceOrder',
					oxConfig::getInstance()->getShopId() . '_' . $iOrderNr = mt_rand(1, 1000),
					$iTotalBrut = mt_rand(1, 1000),
					$iTotalNet = mt_rand(1, 1000),
					115,
					$fCost = 50.99,
					true
				))
				->will($this->throwException(new Exception()));

			$this->oFixture
				->expects($this->once())
				->method('loadBasket')
				->with($oBasket = $this->getMock('WBL_Tracker_Basket', array('getProductVats')));

			$oBasket
				->expects($this->once())
				->method('getProductVats')
				->will($this->returnValue(array(12 => 100, 19 => 15)));

			$this->oFixture->setView($oMock = $this->getMock('Thankyou', array('getBasket', 'getOrder')));

			$oMock
				->expects($this->once())
				->method('getBasket')
				->will($this->returnValue($oBasket));

			$oMock
				->expects($this->once())
				->method('getOrder')
				->will($this->returnValue($oOrder = oxNew('oxorder')));

			$oOrder->oxorder__oxordernr         = new oxField($iOrderNr);
			$oOrder->oxorder__oxtotalordersum   = new oxField($iTotalBrut);
			$oOrder->oxorder__oxtotalnetsum     = new oxField($iTotalNet);
			$oOrder->oxorder__oxdelcost         = new oxField($fCost);
			$oOrder->oxorder__oxdiscount        = new oxField(0);
			$oOrder->oxorder__oxvoucherdiscount = new oxField(1);

			$this->assertSame($this->oFixture, $this->oFixture->init());
			unset($oMock, $oBasket, $oOrder);
		} // function

		/**
		 * There should be no tracking action, if there is a wrong view instance.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitWrongInstance() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('loadBasket'));

			$this->oFixture
				->expects($this->never())
				->method('loadBasket');

			$this->oFixture->setView(oxNew('oxubase'));
			$this->assertSame($this->oFixture, $this->oFixture->init());
		} // function

		/**
		 * Tests the instance.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInstance() {
			$this->assertType('WBL_Tracker_Piwik_Adapter', $this->oFixture);
		} // function
	} // class