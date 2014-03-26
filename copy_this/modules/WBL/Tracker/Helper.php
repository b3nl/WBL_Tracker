<?php
    /**
     * ./modules/WBL/Tracker/Helper.php
     * @author     Bjoern Simon Lange <code@wbl-konzept.de>
     * @category   modules
     * @package    WBL_Tracker
     * @subpackage Helper
     * @version    SVN: $Id$
     */

    /**
     * class_container.
     * @author     Bjoern Simon Lange <code@wbl-konzept.de>
     * @category   modules
     * @package    WBL_Tracker
     * @subpackage Helper
     * @version    SVN: $Id$
     */
    class WBL_Tracker_Helper extends oxSuperCfg
    {
        /**
         * Returns an array of the category itself and all its parents after that.
         * @author blange <code@wbl-konzept.de>
         * @param oxCategory $oActCategory
         * @return oxCategory[]
         */
        public function getCategoryTree(oxCategory $oActCategory)
        {
            $aCats = array();

            if ($oActCategory->getId()) {
                do {
                    $aCats[$oActCategory->getId()] = $oActCategory;
                } while ($oActCategory = $oActCategory->getParentCategory());
            } // if

            return $aCats;
        } // function

        /**
         * Returns the php value as the javascript clone.
         * @author blange <code@wbl-konzept.de>
         * @param mixed $mValue
         * @return string
         */
        public function parseValueToJSPart($mValue)
        {
            if (is_null($mValue)) {
                return 'null';
            } // if

            if (is_string($mValue)) {
                return "'" . str_replace("'", "\'", $mValue) . "'";
            } // if

            if (is_numeric($mValue)) {
                return (float) $mValue;
            } // if

            if (is_bool($mValue)) {
                return $mValue ? 'true' : 'false';
            } // if

            if ((is_array($mValue)) && (is_numeric(key($mValue)))) { // TODO Arrays with string keys!
                return '[' . implode(',', array_map(array($this, 'parseValueToJSPart'), $mValue)) . ']';
            } // if

            return json_encode($mValue);
        } // function
    } // class