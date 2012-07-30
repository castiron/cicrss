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
 * Controller for the Feed object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Cicrss_Controller_FeedController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_Cicrss_Domain_Repository_FeedRepository
	 */
	protected $feedRepository;

	/**
	 * @var int The amount of time, in seconds, that passes between cache refreshes.
	 */
	protected $updateInterval = 3600;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->feedRepository = t3lib_div::makeInstance('Tx_Cicrss_Domain_Repository_FeedRepository');
		$this->articleRepository = t3lib_div::makeInstance('Tx_Cicrss_Domain_Repository_ArticleRepository');

		if(!$this->settings['moreText']) {
			$this->settings['moreText'] = $this->settings['defaults']['moreText'];
		}
	}

	/**
	 * Default action.
	 *
	 * @return string The rendered default action
	 */
	public function defaultAction() {

		// instantiate each feed and add to array
		$feeds = array();
		$feeds['feed'] = $this->feedRepository->findByUid($this->settings['feedRec']);
		if($this->settings['secondaryFeedRec']) {
			$feeds['secondaryFeed'] = $this->feedRepository->findByUid($this->settings['secondaryFeedRec']);
		}

		// iterate over the feeds, get articles, pass to view
		foreach($feeds as $feedKey => $feed) {
			if($this->settings['clearCache']) {
				$this->updateInterval = 0;
			} else {
				$this->updateInterval = $feed->getUpdateInterval();
			}
			$feedDefaultUpdateInterval = $feed->getUpdateInterval();
			$length = $this->settings[$feedKey.'Length'];
			$articles = $this->articleRepository->getArticlesFromFeedService(NULL,$length,$this->updateInterval,$feedDefaultUpdateInterval, $feed->getAddress());
			$this->view->assign($feedKey,$feed);
			$this->view->assign($feedKey.'Articles',$articles);
		}

		// render the selected view, but only if we have articles to render
		if($this->settings['template'] && count($articles) > 0) {
			return $this->view->render($this->settings['template']);
		} else {
			return '';
		}
	}

}
?>