<?php
	/**
	 * ./unit/modules/WBL/Tracker/BasketTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage oxBasket
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Basket.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage oxBasket
	 * @version $id$
	 */
	class WBL_Tracker_BasketTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Basket
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = $this->getOXIDModuleForWBL('oxbasket', 'WBL_Tracker_Basket');
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
		 * Checks if the module method is called.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testOnUpdate() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('isUpdatedForWBLTracker'));

			$this->oFixture
				->expects($this->once())
				->method('isUpdatedForWBLTracker')
				->with(true);

			$this->oFixture->onUpdate();
		} // function

		/**
		 * Checks the constants.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testConstants() {
			$this->assertSame('bIsWBLBasketUpdated', WBL_Tracker_Basket::SESSION_KEY_UPDATE_MARKER);
		} // function

		/**
		 * Checks the status handling of the method.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testIsUpdatedForWBLTracker() {
			$oSession = modSession::getInstance();
			$sKey     = WBL_Tracker_Basket::SESSION_KEY_UPDATE_MARKER;

			$oSession->setVar($sKey, false);
			$this->assertFalse($this->oFixture->isUpdatedForWBLTracker(), '1. check failed.');

			$oSession->setVar($sKey, true);
			$this->assertTrue($this->oFixture->isUpdatedForWBLTracker(), '2. check failed.');
			$this->assertNull($oSession->getVar($sKey), 'Value is not removed from the session (1).');
			$this->assertTrue($this->oFixture->isUpdatedForWBLTracker(false), '3. check failed.');
			$this->assertFalse($oSession->getVar($sKey), 'Value is not changed in the session (1).');
			$this->assertFalse($this->oFixture->isUpdatedForWBLTracker(), '3. check failed.');
			$this->assertNull($oSession->getVar($sKey), 'Value is not removed from the session (2).');
			$this->assertFalse($this->oFixture->isUpdatedForWBLTracker(true), '4. check failed.');
			$this->assertTrue($oSession->getVar($sKey), 'Value is not changed in the session (2).');
			$this->assertTrue($this->oFixture->isUpdatedForWBLTracker(false), '5. check failed.');
			$this->assertFalse($oSession->getVar($sKey), 'Value is not changed in the session (3).');
			unset($oSession);
		} // function

		/**
		 * Checks if the module is loaded.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testType() {
			$this->assertType('WBL_Tracker_Basket', $oClass = oxNew('oxbasket'));
			$this->assertType('WBL_Tracker_Basket_parent', $oClass);
			unset($oClass);
		} // function

		/**
		 * Checks if the class can wake up correctly.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testWakeUpWithSessionCheck() {
			$this->oFixture->isUpdatedForWBLTracker(true);
			$this->assertTrue(oxSession::getVar($sKey = WBL_Tracker_Basket::SESSION_KEY_UPDATE_MARKER), 'Session writing failed.');

			$oClass = unserialize(serialize($this->oFixture));
			$this->assertTrue($this->oFixture->isUpdatedForWBLTracker(), 'Session was not fixed in the class.');
			$this->assertNull(oxSession::getVar($sKey), 'Session value was not removed.');
		} // function
	} //class