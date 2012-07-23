<?php
	/**
	 * ./unit/modules/WBL/Tracker/Conditions/Value/User/IdTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Conditions_Value_User_Id.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_Value_User_IdTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Value_User_Id
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Conditions_Value_User_Id();
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
		 * Checks if the user id is returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueFilled() {
			$this->oFixture->setUser($oUser = new oxUser());

			$oUser->setId($sId = uniqid());

			$this->assertSame($sId, $this->oFixture->getValue());
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