<?php

// $ip = "203.0.113.44";
// $ip = "192.0.2.131";
$ip = "198.51.100.164";

$dec = explode('.', $ip);

$bin = "";
foreach ($dec as $num) {
	$bin .= sprintf('%08b', intval($num));
}

print "\n$bin\n\n";

$poses = [0, 7, 14, 21, 28 ];

foreach($poses as $pos) {
	// evaluate current position
	print "\nPOS: $pos\n";
	// one octet
	$one_oct_bin = "0".substr($bin, $pos, 7);
	if (strlen($one_oct_bin) != 8)
		print "PADDING REQUIRED\n";
	if (bindec($one_oct_bin) < hexdec('0x80')) {
		print "1: YAY:     $one_oct_bin / U+00".dechex(bindec($one_oct_bin))."\n";
	} else {
		print "1: NAY:     $one_oct_bin / U+00".dechex(bindec($one_oct_bin))."\n";
	}
	// two octets
	$two_oct_bin = "00000".substr($bin, $pos, 11);
	if (strlen($two_oct_bin) != 16)
		print "PADDING REQUIRED\n";
	if (bindec($two_oct_bin) > hexdec('0x80')) {
		print "2: YAY: $two_oct_bin / U+0".dechex(bindec($two_oct_bin))."\n";
	} else {
		print "2: NAY: $two_oct_bin / U+0".dechex(bindec($two_oct_bin))."\n";
	}
	// three octets
	$three_oct_bin = substr($bin, $pos, 16);
	if (strlen($three_oct_bin) != 16)
		print "PADDING REQUIRED\n";
	if (bindec($three_oct_bin) > hexdec('0x800')) {
		print "3: YAY:      $three_oct_bin / U+".dechex(bindec($three_oct_bin))."\n";
	} else {
		print "3: NAY:      $three_oct_bin / U+".dechex(bindec($three_oct_bin))."\n";
	}
	// four octets
	$four_oct_bin = "000".substr($bin, $pos, 21);
	if (strlen($four_oct_bin) != 24)
		print "PADDING REQUIRED\n";
	if (bindec($four_oct_bin) > hexdec('0x010000') && bindec($four_oct_bin) < hexdec('0x10ffff')) {
		print "4: YAY:   $four_oct_bin / U+00".dechex(bindec($four_oct_bin))."\n";
	} else {
		print "4: NAY:   $four_oct_bin / U+00".dechex(bindec($four_oct_bin))."\n";
	}
	
}

			

// print dechex(bindec('0101'));



?>
