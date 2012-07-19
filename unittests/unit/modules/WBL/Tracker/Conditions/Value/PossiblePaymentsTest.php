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
	 * Testing of WBL_Tracker_Conditions_Value_PossiblePayments.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Conditions_Value
	 * @subpackage Piwik
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_Value_PossiblePaymentsTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Value_PossiblePayments
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Conditions_Value_PossiblePayments();
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
			$this->assertNull($this->oFixture->getValue());
		} // function

		/**
		 * Checks if the payment types are returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueFromViewAsArray() {
			$this->oFixture->setView($oView = $this->getMock('Payment', array('getPaymentList')));

			$oView
				->expects($this->once())
				->method('getPaymentList')
				->will($this->returnValue(array($sId = uniqid() => true)));
			unset($oView);

			$this->assertSame(array($sId), $this->oFixture->getValue());
		} // function

		/**
		 * Checks if the payment types are returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueFromViewListArray() {
			$this->oFixture->setView($oView = $this->getMock('Payment', array('getPaymentList')));

			$oView
				->expects($this->once())
				->method('getPaymentList')
				/* @var $oList oxList */
				->will($this->returnValue($oList = oxNew('oxlist')));
			unset($oView);

			$oList->assign(array($sId = uniqid() => true));
			unset($oList);

			$this->assertSame(array($sId), $this->oFixture->getValue());
		} // function

		/**
		 * Checks if an empty array is returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueFromViewNoArray() {
			$this->oFixture->setView($oView = $this->getMock('Payment', array('getPaymentList')));

			$oView
				->expects($this->once())
				->method('getPaymentList')
				->will($this->returnValue(array()));
			unset($oView);

			$this->assertSame(array(), $this->oFixture->getValue());
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