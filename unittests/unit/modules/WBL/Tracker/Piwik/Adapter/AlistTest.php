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
	 * Testing of WBL_Tracker_Piwik_Adapter_Alist.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Piwik
	 * @version $id$
	 */
	class WBL_Tracker_Piwik_Adapter_AlistTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Piwik_Adapter_Alist
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = wblNew('WBL_Tracker_Piwik_Adapter_Alist');
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
		 * Tests the instance.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInstance() {
			$this->assertType('WBL_Tracker_Piwik_Adapter', $this->oFixture);
		} // function

		/**
		 * Checks the return values.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testWithDefaultCat() {
			$this->assertTrue($this->oFixture->withDefaultCat());
		} // function
	} // class