<?php
	/**
	 * ./unit/modules/WBL/Tracker/Conditions/Value/Search/QueryTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Conditions_Value_Search_Query.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Conditions_Value
	 * @subpackage Piwik
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_Value_Search_QueryTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Value_Search_Query
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Conditions_Value_Search_Query();
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
		 * Is an empty string returned?
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueEmtpy() {
			$this->oFixture->setView(new Search());

			modConfig::setParameter('searchparam', '');

			$this->assertSame('', $this->oFixture->getValue());
		} // function

		/**
		 * Is the search parameter returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueFull() {
			$this->oFixture->setView(new Search());

			modConfig::setParameter('cl', 'search');
			modConfig::setParameter('searchparam', $sValue = uniqid());

			$this->assertSame($sValue, $this->oFixture->getValue());
		} // function

		/**
		 * Is an empty string returned?
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueNoView() {
			$this->assertSame('', $this->oFixture->getValue());
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