<?php

namespace Addwiki\Wikibase\DataModel;

use Addwiki\Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Property;

/**
 * @author Addshore
 */
class PropertyContent extends Content {

	/**
	 * @var string
	 */
	public const MODEL = 'wikibase-property';

	/**
	 * @param Property $property
	 */
	public function __construct( Property $property ) {
		parent::__construct( $property, self::MODEL );
	}

	/**
	 * @required
	 * @see Content::getData
	 * @return Property
	 */
	public function getData() {
		return parent::getData();
	}

}
