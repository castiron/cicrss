<?php
namespace CIC\Cicrss\Controller;
use CIC\Cicrss\Domain\Model\Feed;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class FeedController
 * @package CIC\Cicrss\Controller
 */
class FeedController extends ActionController {

	/**
	 * @var \CIC\Cicrss\Domain\Repository\FeedRepository
     * @inject
	 */
	protected $feedRepository;

    /**
     * @var \CIC\Cicrss\Domain\Repository\ArticleRepository
     * @inject
     */
    protected $articleRepository;

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

        $articles = array();

		// iterate over the feeds, get articles, pass to view
        /**
         * @var string $feedKey
         * @var Feed $feed
         */
        foreach($feeds as $feedKey => $feed) {
			if(!is_object($feed)) {
				break;
			}
			if($this->settings['clearCache']) {
				$this->updateInterval = 0;
			} else {
				$this->updateInterval = $feed->getUpdateInterval();
			}
			$feedDefaultUpdateInterval = $feed->getUpdateInterval();
			$length = $this->settings[$feedKey.'Length'];
            $articles = $this->articleRepository->getArticlesFromFeedService(NULL, $length, $this->updateInterval, $feedDefaultUpdateInterval, $feed->getAddress());
			$this->view->assign($feedKey,$feed);
			$this->view->assign($feedKey.'Articles',$articles);
		}

		// render the selected view, but only if we have articles to render
		if($this->settings['template'] && count($articles) > 0) {
            $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $path = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']) . '/Feed/' . ucfirst($this->settings['template']) . '.html';
			if(file_exists($path)) {
				$this->view->setTemplatePathAndFilename($path);
			} else {
				// TODO: Consider throwing an exception here. This would happen if a user set a view on a type but the file didn't exist.
			}
			return $this->view->render();
		} else {
			return '';
		}
	}
}
