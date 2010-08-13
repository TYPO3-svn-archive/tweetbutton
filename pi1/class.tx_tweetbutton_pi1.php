<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Peter Proell <peter@alinbu.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Tweet button' for the 'tweetbutton' extension.
 *
 * @author	Peter Proell <peter@alinbu.net>
 * @package	TYPO3
 * @subpackage	tx_tweetbutton
 */
class tx_tweetbutton_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_tweetbutton_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_tweetbutton_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'tweetbutton';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

                // Check environment
                if (!isset($conf['data-count'])) {
                    return $this->pi_wrapInBaseClass($this->pi_getLL('no_ts_template'));
                }

                // Initialize the plugin configuration
                $this->init();

                // return html
                return $this->pi_wrapInBaseClass($this->showButton());
		
	}

        /**
     * Initializes the plugin configuration
     *
     */
    protected function init() {

        $this->pi_initPIflexForm();

        // Get values
        $this->conf['templateFile'] = (string) $this->fetchConfigurationValue('templateFile');
        $this->conf['data-count'] = (string) $this->fetchConfigurationValue('data-count');
        $this->conf['data-related'] = (string) $this->fetchConfigurationValue('data-related');
        $this->conf['data-via'] = (string) $this->fetchConfigurationValue('data-via');
        $this->conf['data-text'] = (string) $this->fetchConfigurationValue('data-text');
        $this->conf['data-url'] = (string) $this->fetchConfigurationValue('data-url');
        $this->conf['data-lang'] = (string) $this->fetchConfigurationValue('data-lang');

        // Set default values if necessary
        if (!$this->conf['templateFile']) {
            $this->conf['templateFile'] = 'EXT:' . $this->extKey . '/res/template.html';
        }
        if (!$this->conf['data-count']) {
            $this->conf['data-count'] = 'horizontal';
        }
        if (!$this->conf['data-url']) {
            $this->conf['data-url'] = 'http://' . $_ENV['SERVER_NAME'] . $_ENV['REQUEST_URI'];
        }
        if (!$this->conf['data-text']) {
            $this->conf['data-text'] = $GLOBALS['TSFE']->page['title'];
        }

        // Load template code
        $this->templateCode = $this->cObj->fileResource($this->conf['templateFile']);
    }

    /**
     * Fetches configuratin value given its name.
     * Merges flexform and TS configuration values.
     *
     * @param	string		$param Configuration value name
     * @return	string		HTML
     */
    protected function fetchConfigurationValue($param) {
        $value = trim($this->pi_getFFvalue($this->cObj->data['pi_flexform'], $param));
        return $value ? $value : $this->conf[$param];
    }

    /**
     * Generates the HTML output for the frontend
     *
     * @return	string		HTML output for FE
     */
    protected function showButton() {

        // Set mandatory parameters
        $markers['###DATA###'] .= ' data-count="' . $this->conf['data-count'] . '"';


        // Set optional parameters
        if ($this->conf['data-via']) {
            $markers['###DATA###'] .= ' data-via="' . $this->conf['data-via'] . '"';
        }
        if ($this->conf['data-text']) {
            $markers['###DATA###'] .= ' data-text="' . $this->conf['data-text'] . '"';
        }
        if ($this->conf['data-url']) {
            $markers['###DATA###'] .= ' data-url="' . $this->conf['data-url'] . '"';
        }
        if ($this->conf['data-lang']) {
            $markers['###DATA###'] .= ' data-lang="' . $this->conf['data-lang'] . '"';
        }
        // Set related accounts info
        if ($this->conf['data-related']){
            $markers['###DATA###'] .= ' data-related="' . $this->conf['data-related'] . '"';
        }


        // Get template for the button
        $template = $this->cObj->getSubpart($this->templateCode, '###BUTTON###');

        // Render output
        $content = $this->cObj->substituteMarkerArray($template, $markers);

        // Return HTML
        return $content;
    }




}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tweetbutton/pi1/class.tx_tweetbutton_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tweetbutton/pi1/class.tx_tweetbutton_pi1.php']);
}

?>