<?php
namespace CIC\Cicrss\Domain\Model;
use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
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
 * RSS Feed Article
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Article extends AbstractEntity {

	/**
	* headline
	* @var string
	*/
	protected $headline;

	/**
	* Setter for headline
	* @param string $headline the article's headline
	* @return void
	*/
	public function setHeadline($headline) {
		$this->headline = $headline;
	}

	/**
	* Getter for headline
	* @return string
	*/
	public function getHeadline() {
		return $this->headline;
	}

	/**
	* teaser
	* @var string
	*/
	protected $teaser;

	/**
	 * Minimum height for images, in article content.
	 * @var int
	 */
	protected $minimumImageHeight = 10;

	/**
	 * Minimum width for images, in article content.
	 * @var int
	 */
	protected $minimumImageWidth = 10;

	/**
	* Setter for teaser
	* @param string $teaser the article's teaser
	* @return void
	*/
	public function setTeaser($content) {
		$this->setImages($this->content);
		$this->removeImagesFromContent();

		$this->teaser = $this->content;
	}

	/**
	* Getter for teaser
	* @return string
	*/
	public function getTeaser() {
		return $this->teaser;
	}

	/**
	 * Date
	 *
	 * @var string
	 */
	protected $date;

	/**
	 * Set the date
	 *
	 * @param string $date the article's date
	 * @return void
	 * @author Gabe Blair
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * Get the date
	 *
	 * @return string
	 * @author Gabe Blair
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Remove html images from the post content
	 * @return null
	 */
	public function removeImagesFromContent() {
		$this->content = preg_replace('/<img[^>]*>/Ui', '', $this->content );
	}

	/**
	* permalink
	* @var string
	*/
	protected $permalink;

	/**
	* Setter for permalink
	* @param string $permalink the article's permalink
	* @return void
	*/
	public function setPermalink($permalink) {
		$this->permalink = $permalink;
	}

	/**
	* Getter for permalink
	* @return string
	*/
	public function getPermalink() {
		return $this->permalink;
	}

	/**
	* content
	* @var string
	*/
	protected $content;

	/**
	* Setter for content
	* @param string $content HTML content of the feed item.
	* @return void
	*/
	public function setContent($content) {
		$this->setTeaser($content);
		$this->content = $content;
	}

	/**
	* Getter for content
	* @return string
	*/
	public function getContent() {
		return $this->content;
	}


	/**
	* authorName
	* @var string
	*/
	protected $authorName;

	/**
	* Setter for authorName
	* @param string $authorName
	* @return void
	*/
	public function setAuthorName($authorName) {
		$this->authorName = $authorName;
	}

	/**
	* Getter for authorName
	* @return string
	*/
	public function getAuthorName() {
		return $this->authorName;
	}

	/**
	* images
	* @var array
	*/
	protected $images;

	/**
	* Setter for images
	* @param array $images The post's images
	* @return void
	*/
	public function setImages($content) {
		preg_match_all( '/<img[^>]*>/Ui', $content, $matches );
		if($matches[0][0]) {
			$this->images = $matches[0];
		}
	}

	/**
	* Getter for images
	* @return array
	*/
	public function getImages() {
		return $this->images;
	}

	/**
	 * Get image with dimensions closest to ration.
	 */
	public function getFirstImageUrl() {
		$dom = new \DOMDocument();
		$dom->loadHTML($this->images[0]);

        /** @var \DOMElement $image */
		$image = $dom->getElementsByTagName('img')->item(0);
		if (!$image) return null;

		// First, attempt to get the largest image height/width from the a srcset attribute
		$srcset = $image->getAttribute('srcset');
		if ($srcset) {
			$sizeUrls = explode(',', $srcset);
			if (count($sizeUrls)) {
				foreach (array_reverse($sizeUrls) as $sizeUrl) {
                    $sizeUrl = trim($sizeUrl);
				    list($url, $width) = explode(' ', $sizeUrl);
					$width = intval(preg_replace('/(\D+)/', '', $width));
					if ($width > $this->minimumImageWidth) return $url;
				}
			}
		} else {
			// Attempt to get image width and height from the img tag
			$src = $image->getAttribute('src');
			$width = intval($image->getAttribute('width'));
			$height = intval($image->getAttribute('height'));
			// If the img tag didn't have height & width attributes, look for a resize parameter
			if (!$width || !$height) {
				$parts = parse_url($src);
				parse_str($parts['query'], $query);
				if ($resize = $query['resize']) {
					list($width, $height) = explode(',', $resize);
				}
			}
			if ($width > $this->minimumImageWidth && $height > $this->minimumImageWidth) {
				return $src;
			}
		}
        return null;
    }

	/**
	 * Getter for single post content image, scaled appropriately.
	 * @return string
	 */

	public function getPostContentImage() {
        $content = '';
		$images = $this->images;
		if($images) {
			$firstImage = $images[0];
			preg_match( '/<img.*?(src\=[\'|"]{0,1}.*?[\'|"]{0,1})[\s|>]{1}/i', $firstImage, $src );
			/* Get the width and height */
			preg_match( '/<img.*?(height\=[\'|"]{0,1}.*?[\'|"]{0,1})[\s|>]{1}/i', $firstImage, $height );
			preg_match( '/<img.*?(width\=[\'|"]{0,1}.*?[\'|"]{0,1})[\s|>]{1}/i', $firstImage, $width );

			if ( !empty( $src ) ) {
				preg_match('/^src\="(.*)?"/', $src[1], $imgSrc);
				$src = $imgSrc[1];
				// $src = substr( substr( str_replace( 'src=', '', $src[1] ), 0, -1 ), 1 );
				$height = substr( substr( str_replace( 'height=', '', $height[1] ), 0, -1 ), 1 );
				$width = substr( substr( str_replace( 'width=', '', $width[1] ), 0, -1 ), 1 );

				if ( empty( $width ) || empty( $height ) ) {
					$width = 280;
					$height = 100;
				}
				// Thiss was modified from the original so that the width gets set to whatever the user has selected in the plugin.
				$ratio = (int)$height / (int)$width;
				$new_width = 280;
				$new_height = $new_width * $ratio;

				// If you wanted the image to be scaled to fit a height, you could use the following:
				// $ratio = (int)$width / (int)$height;
				// $new_height = $this->image_height;
				// $new_width = $new_height * $ratio;

				$content = '<img src="' . $src . '" width="' . $new_width . '" height="' . $new_height . '" class="align-left thumbnail" />';
			}
		}

		return $content;
	}

	/**
	 * Getter for image class
	 * @return string
	 */
	public function getImageClass() {
		if($this->images) {
			$out = "has-image";
		} else {
			$out = '';
		}
		return $out;
	}

}
