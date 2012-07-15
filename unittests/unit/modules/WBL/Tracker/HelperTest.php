<?php
	/**
	 * ./unit/modules/WBL/Tracker/HelperTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Helper.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @version $id$
	 */
	class WBL_Tracker_HelperTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Helper
		 */
		protected $oFixture = null;

		/**
		 * Checks if the categories are returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetCategoryTree() {
			/* @var $oCat oxCategory */
			$oCat = oxNew('oxcategory');

			$oCat->setId(uniqid());
			$oCat->oxcategories__oxparentid = new oxField(uniqid());
			$oCat->setParentCategory($oParent1 = oxNew('oxcategory'));

			$oParent1->setId(uniqid());
			$oParent1->oxcategories__oxparentid = new oxField(uniqid());
			$oParent1->setParentCategory($oParent2 = oxNew('oxcategory'));

			$oParent2->setId(uniqid());
			$oParent2->oxcategories__oxparentid = new oxField('oxrootid');

			$aData = $this->oFixture->getCategoryTree($oCat);
			$this->assertTrue(is_array($aData), 'No category array is returned.');
			$this->assertSame($oCat, reset($aData), 'The array did not contain the cat itself.');
			$this->assertSame($oParent1, next($aData), 'The array did not contain the first parent.');
			$this->assertSame($oParent2, next($aData), 'The array did not contain the second parent.');
			unset($oCat, $oParent1, $oParent2);
		} // function

		/**
		 * Returns some rules to test the parser.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 */
		public function getParserAsserts() {
			$oObject = new stdClass();
			$oObject->test = uniqid();

			return array(
				array(1.23, 1.23),
				array(1.00, (float) 1),
				array(null, 'null'),
				array('test\'"string"', '\'test\\\'"string"\''),
				array(false, 'false'),
				array($oObject, json_encode($oObject)),
				array(
					array('test', array(1, 2, 3, 4.00, null)),
					'[\'test\',[1,2,3,4,null]]'
				)
			);
		} // function

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = wblNew('WBL_Tracker_Helper');
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
		 * Tests the parser method.
		 * @author blange <code@wbl-konzept.de>
		 * @dataProvider getParserAsserts
		 * @param string $sUnparsed The unparsed value.
		 * @param string $sParsed   The parsed value to check.
		 * @return void
		 */
		public function testParseValueToJSPart($sUnparsed, $sParsed) {
			$this->assertSame($sParsed, $this->oFixture->parseValueToJSPart($sUnparsed));
		} // function
	} // class