<?php
	/**
	 * ./unit/modules/WBL/Tracker/Conditions/Value/UserStatusTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Conditions_Value_UserStatus.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_Value_UserStatusTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Value_UserStatus
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Conditions_Value_UserStatus();
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
		 * The default return is the '-'.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueEmtpy() {
			$this->assertSame('-', $this->oFixture->getValue());
		} // function

		/**
		 * The guest value ist the "-1".
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueGuest() {
			$this->oFixture->setUser(new oxUser());

			$this->assertSame('-1', $this->oFixture->getValue());
		} // function

		/**
		 * Customers without an order are new customers.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueNewCustomer() {
			$this->oFixture->setUser($oUser = $this->getProxyClass('oxUser'));
			$oUser->oxuser__oxpassword = new oxField(uniqid());
			$oUser->setNonPublicVar('_oGroups', array('oxidnotyetordered' => true));
			unset($oUser);

			$this->assertSame('0', $this->oFixture->getValue());
		} // function

		/**
		 * Customers without an order are new customers.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueOldCustomer() {
			$this->oFixture->setUser($oUser = $this->getMock('oxUser', array('getOrderCount')));
			$oUser->oxuser__oxpassword = new oxField(uniqid());

			$this->assertSame('1', $this->oFixture->getValue());
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