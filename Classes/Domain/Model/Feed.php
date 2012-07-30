<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Zach Davis <zach@castironcoding.com>, Cast Iron Coding, Inc
*
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
 * RSS Feed
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Cicrss_Domain_Model_Feed extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * Feed Address
	 * @var string
	 * @validate NotEmpty
	 */
	protected $address;

	/**
	 * Feed Title
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * Update Interval
	 * @var integer
	 */
	protected $updateInterval;

	/**
	 * Setter for address
	 *
	 * @param string $address Feed Address
	 * @return void
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * Setter for title
	 *
	 * @param string $address Feed Address
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Getter for address
	 *
	 * @return string Feed Address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Getter for title
	 *
	 * @return string Feed Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Setter for updateInterval
	 *
	 * @param integer $updateInterval Update Interval
	 * @return void
	 */
	public function setUpdateInterval($updateInterval) {
		$this->updateInterval = $updateInterval;
	}

	/**
	 * Getter for updateInterval
	 *
	 * @return integer Update Interval
	 */
	public function getUpdateInterval() {
		return $this->updateInterval;
	}

}
?>