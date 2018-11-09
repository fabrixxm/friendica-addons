<?php
/**
 * Name: Podcast
 * Description: Ease pubblication of audio podcast via Friendica
 * Version: 0.1
 * Author: Fabio <https://kirgroup.com/~fabrixxm>
 */

use Friendica\Core\Hook;
use Friendica\Core\L10n;
use Friendica\Core\Renderer;

require_once "Attach.php";

function podcast_install() {
	Hook::register('jot_tool', __file__, 'podcast_jot_tool', 100); // this must be the last tool in jot
	Hook::register('post_local', __file__, 'podcast_post_local');
}

function podcast_uninstall() {
	Hook::unregister('post_local', __file__, 'podcast_post_local');
	Hook::unregister('jot_networks', __file__, 'podcast_jot_nets');
}


function podcast_jot_tool(&$a,&$html) {
	if(! local_user())
		return;

	// @TODO: formats from settings
	$formats = [
		['label'=>'MP3', 'mime'=>'audio/mpeg'],
		['label'=>'Ogg/Theora', 'mime'=>'audio/ogg'],
	];

	// add css and js
	$baseurl = $a->getBaseURL()."/addon/podcast";
	$a->page['htmlhead'] .= '<link rel="stylesheet"  type="text/css" href="'. $baseurl .'/podcast.css" media="all" />' . "\r\n";
	/*$a->page['htmlhead'] .= '<script src="'. $baseurl .'/stimulus.umd.js"></script>'. "\r\n";
	$a->page['htmlhead'] .= '<script src="'. $baseurl .'/podcast.js"></script>'. "\r\n";*/

	$tpl = Renderer::getMarkupTemplate('jot_form.tpl', 'addon/podcast/');

	$html .=  Renderer::replaceMacros($tpl, array(
		'title' => L10n::t('Podcast'),
		'formats' => $formats,
	));
	
}


function podcast_post_local(&$a, &$item) {
	// @TODO: formats from settings
	$formats = [
		['label'=>'MP3', 'mime'=>'audio/mpeg'],
		['label'=>'Ogg/Theora', 'mime'=>'audio/ogg'],
	];
	$label = [];
	foreach($formats as $f) {
		$label[$f['mime']] = $f['label'];
	}

	$attachs = new Attach($item['attach']);

	for($k=0; $k<count($formats); $k++) {
		$mime = notags(trim(defaults($_REQUEST, "podcast-{$k}-mime" , '')));
		$url = notags(trim(defaults($_REQUEST, "podcast-{$k}-url" , '')));
		$length = notags(trim(defaults($_REQUEST, "podcast-{$k}-length" , '')));
		$title = "{$item['title']} ({$label[$mime]})";

		$attachs->setByType($mime, $url, $length, $title);
	}

	$item['attach'] = $attachs->toString();
}