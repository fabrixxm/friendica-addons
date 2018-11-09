<?php

class Attach {
	public $attachs = [];

	public function __construct($string="") {
		$this->parse($string);
	}

	private function parse($string="") {
		if ($string=="") return;
		foreach (explode(', ', $string) as $attachstr) {
			$attach = [];
			$attachstr = preg_replace("|\[/?attach\]|", "", $attachstr);
			$attachstr = trim($attachstr, '"');		
			$attachdata = explode('" ', $attachstr);
			foreach($attachdata as $data) {
				list($k, $v) = explode('="', $data);
				$attach[$k] = $v;
			}
			$this->attachs[] = $attach;
		}
	}

	/**
	 * @brief Set one and only one attach by type.
	 * 
	 * Add an attach replacing existing attachs of same type.
	 * 
	 * @param string $type Attachment mimetype
	 * @param string $href URL
	 * @param string $length Size in bytes
	 * @param string $title Attachment title, optional
	 */
	public function setByType($type, $href, $length, $title="") {
		$a = [
			'href' => $href,
			'length' => $length,
			'type' => $type,
			'title' => $title
		];
		foreach($this->attachs as $i=>$b) {
			if ($b['type']==$type) array_splice($this->attachs, $i, 1);
		}
		$this->attachs[] = $a;
	}

	public function toString() {
		$elms = [];
		foreach($this->attachs as $a) {
			$elms[] = sprintf('[attach]href="%s" length="%s" type="%s" title="%s"[/attach]',
				$a['href'], $a['length'], $a['type'], (isset($a['title'])?$a['title']:"")
			);
		}
		return implode(",", $elms);
	}
}


/**
 *  Tests
 *


// test empty data
$attach = new Attach();
assert(count($attach->attachs)==0, "No elements parsed");


$data = '[attach]href="http://text" length="42" type="text/plain" title="test.txt"[/attach]';
$attach = new Attach($data);

// parse string test
assert(count($attach->attachs)==1, "One element parsed");
assert($attach->attachs[0] == ['href'=>"http://text", 'length'=>"42", 'type'=>"text/plain", 'title'=>"test.txt"], "Parsed array");

// test toString
assert($attach->toString() == $data, "Attachs to string");

// test setByType
$attach->setByType("audio/mpeg", "http://mp3", 123, "music.mp3");
assert(count($attach->attachs)==2, "Append element");

$attach->setByType("text/plain", "http://txt", 8, "pippo.txt");
assert(count($attach->attachs)==2, "Replace element");
assert($attach->attachs[1] == ['href'=>"http://txt", 'length'=>"8", 'type'=>"text/plain", 'title'=>"pippo.txt"], "Replaced element");

*/