<?php

namespace Addwiki\Commands\Wikimedia\WikidataReferencer\MicroData;

use linclark\MicrodataPHP\MicrodataPhp;

/**
 * @author Addshore
 */
class MicroDataExtractor {

	/**
	 * @param string $html raw HTML
	 *
	 * @return MicroData[] array of microdata things
	 */
	public function extract( $html ) {
		$microDatas = [];
		$md = new MicrodataPhp( [ 'html' => $html ] );

		$data = $md->obj();
		foreach ( $data->items as $microData ) {
			$microDatas[] = new MicroData( $microData );
		}
		return $microDatas;
	}

}
