<?php
	/**
	 * ./unit/modules/WBL/Tracker/Conditions/Value/OrderPaymentTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Conditions_Value_OrderPayment.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Conditions_Value
	 * @subpackage Piwik
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_Value_OrderPaymentTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Value_OrderPayment
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Conditions_Value_OrderPayment();
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
		 * Checks the default return
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueDefault() {
			$this->assertSame('', $this->oFixture->getValue());
		} // function

		/**
		 * Checks if the payment type is used.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueFromOrder() {
			$this->oFixture->setView($oView = $this->getMock('thankyou', array('getOrder')));

			$oView
				->expects($this->once())
				->method('getOrder')
				->will($this->returnValue($oOrder = oxNew('oxorder')));
			unset($oView);

			$oOrder->oxorder__oxpaymenttype = new oxField($sType = uniqid());
			unset($oOrder);

			$this->assertSame($sType, $this->oFixture->getValue());
		} // function

		/**
		 * Checks the type.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testType() {
			$this->assertType('WBL_Tracker_Conditions_Interface', $this->oFixture);
			$this->assertType('WBL_Tracker_Conditions_Value_Interface', $this->oFixture);
		} // function
	} // class