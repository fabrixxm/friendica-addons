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


function podcast_install() {
	Hook::register('jot_tool', __file__, 'podcast_jot_tool', 100); // this must be the last one
}
function podcast_uninstall() {
	Hook::unregister('jot_networks', __file__, 'podcast_jot_nets');

	Hook::unregister('jot_tool', __file__, 'podcast_jot_tool');
	Hook::unregister('jot_nets', __file__, 'podcast_jot_nets');

}


function podcast_jot_tool(&$a,&$b) {
	if(! local_user())
		return;

	$formats = [
		['label'=>'MP3', 'mime'=>'audio/mpeg'],
		['label'=>'Ogg/Theora', 'mime'=>'audio/ogg'],
	];


	$tpl = Renderer::getMarkupTemplate('jot_form.tpl', 'addon/podcast/');

	$b .=  Renderer::replaceMacros($tpl, array(
		'title' => L10n::t('Podcast'),
		'formats' => $formats,
	));
	
}