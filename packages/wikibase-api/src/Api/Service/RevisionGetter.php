<?php

namespace Addwiki\Wikibase\Api\Service;

use Deserializers\Deserializer;
use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Revision;
use RuntimeException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Addwiki\Wikibase\DataModel\ItemContent;
use Addwiki\Wikibase\DataModel\PropertyContent;
use Wikibase\DataModel\SiteLink;

/**
 * @access private
 *
 * @author Addshore
 */
class RevisionGetter {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @var Deserializer
	 */
	protected $entityDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param Deserializer $entityDeserializer
	 */
	public function __construct( MediawikiApi $api, Deserializer $entityDeserializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
	}

	/**
	 * @since 0.1
	 * @param string|EntityId $id
	 * @return Revision
	 */
	public function getFromId( $id ) {
		if ( $id instanceof EntityId ) {
			$id = $id->getSerialization();
		}

		$result = $this->api->getRequest( new SimpleRequest( 'wbgetentities', [ 'ids' => $id ] ) );
		return $this->newRevisionFromResult( array_shift( $result['entities'] ) );
	}

	/**
	 * @since 0.1
	 * @param SiteLink $siteLink
	 * @return Revision
	 */
	public function getFromSiteLink( SiteLink $siteLink ) {
		$result = $this->api->getRequest( new SimpleRequest(
			'wbgetentities',
			[ 'sites' => $siteLink->getSiteId(), 'titles' => $siteLink->getPageName() ]
		) );
		return $this->newRevisionFromResult( array_shift( $result['entities'] ) );
	}

	/**
	 * @since 0.1
	 * @param string $siteId
	 * @param string $title
	 * @return Revision
	 */
	public function getFromSiteAndTitle( $siteId, $title ) {
		$result = $this->api->getRequest( new SimpleRequest(
			'wbgetentities',
			[ 'sites' => $siteId, 'titles' => $title ]
		) );
		return $this->newRevisionFromResult( array_shift( $result['entities'] ) );
	}

	/**
	 * @param array $entityResult
	 * @return Revision
	 * @todo this could be factored into a different class?
	 */
	private function newRevisionFromResult( array $entityResult ) {
		if ( array_key_exists( 'missing', $entityResult ) ) {
			return false; // Throw an exception?
		}
		return new Revision(
			$this->getContentFromEntity( $this->entityDeserializer->deserialize( $entityResult ) ),
			new PageIdentifier( null, (int)$entityResult['pageid'] ),
			$entityResult['lastrevid'],
			null,
			null,
			$entityResult['modified']
		);
	}

	/**
	 * @param Item|Property $entity
	 *
	 * @throws RuntimeException
	 * @return ItemContent|PropertyContent
	 * @todo this could be factored into a different class?
	 */
	private function getContentFromEntity( $entity ) {
		switch ( $entity->getType() ) {
			case Item::ENTITY_TYPE:
				return new ItemContent( $entity );
			case Property::ENTITY_TYPE:
				return new PropertyContent( $entity );
			default:
				throw new RuntimeException( 'I cant get a content for this type of entity' );
		}
	}

}
