<?php

//===========================================================
// wiki markup parser (regexp version)
// Łukasz Korczewski <lukasz.korczewski@gmail.com>
//===========================================================

function convert($in){
	
	/*	
	// parser
	$input_lines = explode("\r\n", $out);
	
	$block = '';
	$output_lines = array();
	
	forech($input_lines as $line){
		
		if($line = '') $block = ''; continue;
		
		$old_block = $block;
		if(substr($line,0,1)=='|') { $block = 'table'; $output_lines[] = '<table>'; }
		
		if($block != $old_block){
			switch($old_block){
				case 'table': $block = ''; $output_lines[] = '</table>'; break;
				default: $block = ''; break; 
			}
		}
		
		switch($block){
			case 'table':
				$line_split = explode('|', $line);
				unset($line_split[0]);
				unset($line_split[count($line_split)-1]);
				$output_lines = '<tr>'.implode
		}
	}
	
	$out = implode("\r\n", $output_lines);
	*/
	
	return $out;
}

class Wiki_Parser {
	private $search;
	private $replace;
	private $link_prefix;
	private $link_postfix;
		
	public function __construct(){
	}
	
	// setting internal link pattern
	
	public function set_link($prefix, $postfix=''){
		$this->link_prefix = $prefix;
		$this->link_postfix = $postfix;
	}
	
	// getting title from page content
	
	public function get_title($in){
		$matches = array();
		preg_match('/^! (.*)\r\n/', $in, $matches);
		return $matches[1];
	}
	
	// parsing markup into HTML
	
	public function parse($in) {
		$this->search = array();
		$this->replace = array();
		
		// inline modifications	
		$this->search = array_merge($this->search, array(
			'/\/\/(.+?)\/\//',
			'/\*\*(.+?)\*\*/',
			'/\$\$(.+?)\$\$/'
		));
		$this->replace = array_merge($this->replace, array(
			'<i>\1</i>',
			'<b>\1</b>',
			'<code>\1</code>'
		));
		
		// links
		$this->search = array_merge($this->search, array(
			'/\[\[([^\]]+)\|(http[s]?:\/\/[^\]]+)\]\]/', // konflikt z pochyleniem
			'/\[\[([^\]]+)\|([^\]]+)\]\]/',
			'/\[\[([^\]]+)\]\]/'
		));
		/*
		$this->replace = array_merge($this->replace, array(
			'<a href="\2">\1</a>',
			"<a href=\"{$this->link_prefix}$2{$this->link_postfix}\">$1</a>",
			"<a href=\"{$this->link_prefix}$1{$this->link_postfix}\">$1</a>"
		));
		*/
		$this->replace = array_merge($this->replace, array(
			'<a href="\2">\1</a>',
			"<a href=\"?page=$2\">$1</a>",
			"<a href=\"?page=$1\">$1</a>"
		));
		
		// headers and paragraphs
		$this->search = array_merge($this->search, array(
			'/\n! (.*)\r\n\r/m',
			'/\n!! (.*)\r\n\r/m',
			'/\n!!! (.*)\r\n\r/m',
			'/\n!!!! (.*)\r\n\r/m',
			'/\n([^\*#|!].*(\r\n.+)*)\r\n\r/m'
		));
		$this->replace = array_merge($this->replace, array(
			"\n<h1>$1</h1>\r\n\r", // co jeśli ostatnia linia?
			"\n<h2>$1</h2>\r\n\r",
			"\n<h3>$1</h3>\r\n\r",
			"\n<h4>$1</h4>\r\n\r",
			"\n<p>$1</p>\r\n\r"
		));
		
		// lists
		$this->search = array_merge($this->search, array(
			'/\n((\*+ .+\r\n)+)\r/m',
			'/\n\*{1} (.+)\r\n((\*{2}) .+\r\n(?:\3\** .+\r\n)*)/m',
			'/\n\*{2} (.+)\r\n((\*{3}) .+\r\n(?:\3\** .+\r\n)*)/m',
			'/\n\*{3} (.+)\r\n((\*{4}) .+\r\n(?:\3\** .+\r\n)*)/m',
			'/\n\*+ (.+)\r/m',
			'/\n((#+ .+\r\n)+)\r/m',
			'/\n#{1} (.+)\r\n((#{2}) .+\r\n(?:\3#* .+\r\n)*)/m',
			'/\n#{2} (.+)\r\n((#{3}) .+\r\n(?:\3#* .+\r\n)*)/m',
			'/\n#{3} (.+)\r\n((#{4}) .+\r\n(?:\3#* .+\r\n)*)/m',
			'/\n#+ (.+)\r/m'
		));
		$this->replace = array_merge($this->replace, array(
			"\n<ul>\r\n$1</ul>\r\n\r",
			"\n<li>$1\r\n<ul>\r\n$2</ul>\r\n</li>\r\n",
			"\n<li>$1\r\n<ul>\r\n$2</ul>\r\n</li>\r\n",
			"\n<li>$1\r\n<ul>\r\n$2</ul>\r\n</li>\r\n",
			"\n<li>$1</li>\r",
			"\n<ol>\r\n$1</ol>\r\n\r",
			"\n<li>$1\r\n<ol>\r\n$2</ol>\r\n</li>\r\n",
			"\n<li>$1\r\n<ol>\r\n$2</ol>\r\n</li>\r\n",
			"\n<li>$1\r\n<ol>\r\n$2</ol>\r\n</li>\r\n",
			"\n<li>$1</li>\r"
		));
		
		// tables
		$this->search = array_merge($this->search, array(
			'/\n((\|!? .* \|+\r\n)+)\r/m',
			'/\n\|(!? .*) \|+\r/',
			'/\| (.*?)(?: +(?=\| )| *(?=<\/tr>\r))/',
			'/\|! (.*?)(?: +(?=\|!? )| *(?=<\/tr>\r))/',
			'/\| (.*?)(?: +|(?=\| )| *(?=<\/tr>\r))/'/*,
			'/(?<=<tr>|<\/td>)\| (.*) \|\|(?=\|)/'*/
		));
		$this->replace = array_merge($this->replace, array(
			"\n<table>\r\n$1</table>\r\n\r",
			"\n<tr>|$1</tr>\r",
			"<td>$1</td>",
			"<th>$1</th>",
			"<td colspan=\"2\">$1</td>"/*,
			"\n<td colspan=\"3\">$1</td>\r"*/
		));
		
		return trim(preg_replace($this->search, $this->replace, "\r\n".$in."\r\n"))."\r\n";
	}
	
	// cleaning before saving
	
	public function clean($in){
		return trim($in)."\r\n";
	}
}

?>
