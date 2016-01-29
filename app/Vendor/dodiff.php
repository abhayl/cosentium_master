<?php

Class doDiff {

	function doHtmlDiff($doc1, $doc2) {
		require_once('php-htmldiff-master/HtmlDiff.php');
		$diff = new HtmlDiff( $doc1, $doc2 );
		$comp = $diff->build();
		return $comp;
	}
}