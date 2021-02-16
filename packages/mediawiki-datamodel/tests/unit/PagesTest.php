<?php

namespace Mediawiki\DataModel\Test;

use Mediawiki\DataModel\Title;
use Mediawiki\DataModel\Revisions;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Pages;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Mediawiki\DataModel\Pages
 * @author Addshore
 */
class PagesTest extends TestCase {

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testValidConstruction( $input, $expected ) {
		$pages = new Pages( $input );
		$this->assertEquals( $expected, $pages->toArray() );
	}

	public function provideValidConstruction() {
		$mockTitle = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
		$mockRevisions = $this->getMockBuilder( Revisions::class )
			->disableOriginalConstructor()
			->getMock();

		// todo mock these
		$page1 = new Page( new PageIdentifier( $mockTitle, 1 ), $mockRevisions );
		$page2 = new Page( new PageIdentifier( $mockTitle, 2 ), $mockRevisions );
		$page4 = new Page( new PageIdentifier( $mockTitle, 4 ), $mockRevisions );

		return [
		[ [ $page1 ], [ 1 => $page1 ] ],
		[ [ $page2, $page1 ], [ 1 => $page1, 2 => $page2 ] ],
		[ [ $page4, $page1 ], [ 1 => $page1, 4 => $page4 ] ],
		[ new Pages( [ $page4, $page1 ] ), [ 1 => $page1, 4 => $page4 ] ],
		];
	}

}
