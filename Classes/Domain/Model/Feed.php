<?php
namespace CIC\Cicrss\Domain\Model;
use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Feed
 * @package CIC\Cicrss\Domain\Model
 */
class Feed extends AbstractEntity {

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
