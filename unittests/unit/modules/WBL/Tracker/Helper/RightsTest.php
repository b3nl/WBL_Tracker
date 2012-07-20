<?php
	/**
	 * ./unit/modules/WBL/Tracker/Helper/RightsTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Helper
	 * @version $id$
	 */

	require_once realpath(dirname(__FILE__) . '/../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Helper_Rights.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Helper
	 * @version $id$
	 */
	class WBL_Tracker_Helper_RightsTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Helper_Rights
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/modules/WBL/WBL_TestCase::getGetterAndGetterRules()
		 */
		public function getGetterAndGetterRules() {
			return array(
				array(
					'getCheckTemplate',
					'setCheckTemplate',
					realpath(getShopBasePath() . '/modules/WBL/Tracker/Helper/_files/rights.tpl'),
					array($sReturn = uniqid()),
					$sReturn
				)
			);
		} // function

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Helper_Rights();
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
		 * Checks if true is returned every time on a ce.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testHasRightsDefaultOnCE() {
			if (class_exists('oxRights')) {
				return $this->markTestSkipped('No CE.');
			} // if

			$this->assertTrue($this->oFixture->hasRight(uniqid()));
		} // function

		/**
		 * Checks the return type of the method.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testHasRightsReturn() {
			$this->assertType('bool', $this->oFixture->hasRight(uniqid()));
		} // function
	} // class