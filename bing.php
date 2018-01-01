<?php
require_once 'includes/phpQuery.php';
$get = req('http://www.bing.com/images/discover?form=Z9LH1');
if($get){
	$result = array();

	$doc = \phpQuery::newDocument($get);
	foreach ($doc['.iusc'] as $row){
		$result[] = $row->getAttribute('m');
	
	}

	print_r($result);die();
}

function req($url){
    ini_set('max_execution_time', 60 * 2);
    set_time_limit(60 * 2);
	$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, 0 );
		curl_setopt( $ch, CURLOPT_REFERER, $url );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:50.0) Gecko/20100101 Firefox/50.0' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );

		$result = curl_exec( $ch );
		curl_close( $ch );

		return $result;
}

function format_bytes($size){
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; ($size >= 1024) && ($i < 5); $i++)
        $size /= 1024;
    return round($size, 2) . $units[$i];
}

function title($str, $delimiter = ' ', $options = array()) {
    $defaults = array(
        'delimiter' => $delimiter,
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
        );
// Merge options
    $options = array_merge($defaults, $options);
    $char_map = array(
// Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', '?' => 'O', 
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', '?' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
        'ß' => 'ss', 
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', '?' => 'o', 
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', '?' => 'u', 'ý' => 'y', 'þ' => 'th', 
        'ÿ' => 'y',
// Latin symbols
        '©' => '(c)',
// Greek
        '?' => 'A', '?' => 'B', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Z', '?' => 'H', '?' => '8',
        '?' => 'I', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => '3', '?' => 'O', '?' => 'P',
        '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'Y', '?' => 'F', '?' => 'X', '?' => 'PS', '?' => 'W',
        '?' => 'A', '?' => 'E', '?' => 'I', '?' => 'O', '?' => 'Y', '?' => 'H', '?' => 'W', '?' => 'I',
        '?' => 'Y',
        '?' => 'a', '?' => 'b', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'z', '?' => 'h', '?' => '8',
        '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => '3', '?' => 'o', '?' => 'p',
        '?' => 'r', '?' => 's', '?' => 't', '?' => 'y', '?' => 'f', '?' => 'x', '?' => 'ps', '?' => 'w',
        '?' => 'a', '?' => 'e', '?' => 'i', '?' => 'o', '?' => 'y', '?' => 'h', '?' => 'w', '?' => 's',
        '?' => 'i', '?' => 'y', '?' => 'y', '?' => 'i',
// Turkish
        '?' => 'S', '?' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', '?' => 'G',
        '?' => 's', '?' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', '?' => 'g', 
// Russian
        '?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Yo', '?' => 'Zh',
        '?' => 'Z', '?' => 'I', '?' => 'J', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
        '?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F', '?' => 'H', '?' => 'C',
        '?' => 'Ch', '?' => 'Sh', '?' => 'Sh', '?' => '', '?' => 'Y', '?' => '', '?' => 'E', '?' => 'Yu',
        '?' => 'Ya',
        '?' => 'a', '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo', '?' => 'zh',
        '?' => 'z', '?' => 'i', '?' => 'j', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => 'o',
        '?' => 'p', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c',
        '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => '', '?' => 'y', '?' => '', '?' => 'e', '?' => 'yu',
        '?' => 'ya',
// Ukrainian
        '?' => 'Ye', '?' => 'I', '?' => 'Yi', '?' => 'G',
        '?' => 'ye', '?' => 'i', '?' => 'yi', '?' => 'g',
// Czech
        '?' => 'C', '?' => 'D', '?' => 'E', '?' => 'N', '?' => 'R', 'Š' => 'S', '?' => 'T', '?' => 'U', 
        'Ž' => 'Z', 
        '?' => 'c', '?' => 'd', '?' => 'e', '?' => 'n', '?' => 'r', 'š' => 's', '?' => 't', '?' => 'u',
        'ž' => 'z', 
// Polish
        '?' => 'A', '?' => 'C', '?' => 'e', '?' => 'L', '?' => 'N', 'Ó' => 'o', '?' => 'S', '?' => 'Z', 
        '?' => 'Z', 
        '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'l', '?' => 'n', 'ó' => 'o', '?' => 's', '?' => 'z',
        '?' => 'z',
// Latvian
        '?' => 'A', '?' => 'C', '?' => 'E', '?' => 'G', '?' => 'i', '?' => 'k', '?' => 'L', '?' => 'N', 
        'Š' => 'S', '?' => 'u', 'Ž' => 'Z',
        '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'g', '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'n',
        'š' => 's', '?' => 'u', 'ž' => 'z'
        );
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    $str = substr($str, 0, ($options['limit'] ? $options['limit'] : strlen($str)));
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? strtolower($str) : $str;
}