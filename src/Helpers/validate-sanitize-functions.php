<?php

//the numbers in the in-line-comments display the characters' Unicode code-points (CP).
function strtolower_utf8_extended( $utf8_string )
{
    $additional_replacements    = array
        ( "ǅ"    => "ǆ"        //   453 ->   454
        , "ǈ"    => "ǉ"        //   456 ->   457
        , "ǋ"    => "ǌ"        //   459 ->   460
        , "ǲ"    => "ǳ"        //   498 ->   499
        , "Ϸ"    => "ϸ"        //  1015 ->  1016
        , "Ϲ"    => "ϲ"        //  1017 ->  1010
        , "Ϻ"    => "ϻ"        //  1018 ->  1019
        , "ᾈ"    => "ᾀ"        //  8072 ->  8064
        , "ᾉ"    => "ᾁ"        //  8073 ->  8065
        , "ᾊ"    => "ᾂ"        //  8074 ->  8066
        , "ᾋ"    => "ᾃ"        //  8075 ->  8067
        , "ᾌ"    => "ᾄ"        //  8076 ->  8068
        , "ᾍ"    => "ᾅ"        //  8077 ->  8069
        , "ᾎ"    => "ᾆ"        //  8078 ->  8070
        , "ᾏ"    => "ᾇ"        //  8079 ->  8071
        , "ᾘ"    => "ᾐ"        //  8088 ->  8080
        , "ᾙ"    => "ᾑ"        //  8089 ->  8081
        , "ᾚ"    => "ᾒ"        //  8090 ->  8082
        , "ᾛ"    => "ᾓ"        //  8091 ->  8083
        , "ᾜ"    => "ᾔ"        //  8092 ->  8084
        , "ᾝ"    => "ᾕ"        //  8093 ->  8085
        , "ᾞ"    => "ᾖ"        //  8094 ->  8086
        , "ᾟ"    => "ᾗ"        //  8095 ->  8087
        , "ᾨ"    => "ᾠ"        //  8104 ->  8096
        , "ᾩ"    => "ᾡ"        //  8105 ->  8097
        , "ᾪ"    => "ᾢ"        //  8106 ->  8098
        , "ᾫ"    => "ᾣ"        //  8107 ->  8099
        , "ᾬ"    => "ᾤ"        //  8108 ->  8100
        , "ᾭ"    => "ᾥ"        //  8109 ->  8101
        , "ᾮ"    => "ᾦ"        //  8110 ->  8102
        , "ᾯ"    => "ᾧ"        //  8111 ->  8103
        , "ᾼ"    => "ᾳ"        //  8124 ->  8115
        , "ῌ"    => "ῃ"        //  8140 ->  8131
        , "ῼ"    => "ῳ"        //  8188 ->  8179
        , "Ⅰ"    => "ⅰ"        //  8544 ->  8560
        , "Ⅱ"    => "ⅱ"        //  8545 ->  8561
        , "Ⅲ"    => "ⅲ"        //  8546 ->  8562
        , "Ⅳ"    => "ⅳ"        //  8547 ->  8563
        , "Ⅴ"    => "ⅴ"        //  8548 ->  8564
        , "Ⅵ"    => "ⅵ"        //  8549 ->  8565
        , "Ⅶ"    => "ⅶ"        //  8550 ->  8566
        , "Ⅷ"    => "ⅷ"        //  8551 ->  8567
        , "Ⅸ"    => "ⅸ"        //  8552 ->  8568
        , "Ⅹ"    => "ⅹ"        //  8553 ->  8569
        , "Ⅺ"    => "ⅺ"        //  8554 ->  8570
        , "Ⅻ"    => "ⅻ"        //  8555 ->  8571
        , "Ⅼ"    => "ⅼ"        //  8556 ->  8572
        , "Ⅽ"    => "ⅽ"        //  8557 ->  8573
        , "Ⅾ"    => "ⅾ"        //  8558 ->  8574
        , "Ⅿ"    => "ⅿ"        //  8559 ->  8575
        , "Ⓐ"    => "ⓐ"        //  9398 ->  9424
        , "Ⓑ"    => "ⓑ"        //  9399 ->  9425
        , "Ⓒ"    => "ⓒ"        //  9400 ->  9426
        , "Ⓓ"    => "ⓓ"        //  9401 ->  9427
        , "Ⓔ"    => "ⓔ"        //  9402 ->  9428
        , "Ⓕ"    => "ⓕ"        //  9403 ->  9429
        , "Ⓖ"    => "ⓖ"        //  9404 ->  9430
        , "Ⓗ"    => "ⓗ"        //  9405 ->  9431
        , "Ⓘ"    => "ⓘ"        //  9406 ->  9432
        , "Ⓙ"    => "ⓙ"        //  9407 ->  9433
        , "Ⓚ"    => "ⓚ"        //  9408 ->  9434
        , "Ⓛ"    => "ⓛ"        //  9409 ->  9435
        , "Ⓜ"    => "ⓜ"        //  9410 ->  9436
        , "Ⓝ"    => "ⓝ"        //  9411 ->  9437
        , "Ⓞ"    => "ⓞ"        //  9412 ->  9438
        , "Ⓟ"    => "ⓟ"        //  9413 ->  9439
        , "Ⓠ"    => "ⓠ"        //  9414 ->  9440
        , "Ⓡ"    => "ⓡ"        //  9415 ->  9441
        , "Ⓢ"    => "ⓢ"        //  9416 ->  9442
        , "Ⓣ"    => "ⓣ"        //  9417 ->  9443
        , "Ⓤ"    => "ⓤ"        //  9418 ->  9444
        , "Ⓥ"    => "ⓥ"        //  9419 ->  9445
        , "Ⓦ"    => "ⓦ"        //  9420 ->  9446
        , "Ⓧ"    => "ⓧ"        //  9421 ->  9447
        , "Ⓨ"    => "ⓨ"        //  9422 ->  9448
        , "Ⓩ"    => "ⓩ"        //  9423 ->  9449
        , "𐐦"    => "𐑎"        // 66598 -> 66638
        , "𐐧"    => "𐑏"        // 66599 -> 66639
        );
   
    $utf8_string    = mb_strtolower( $utf8_string, "UTF-8");
   
    $utf8_string    = strtr( $utf8_string, $additional_replacements );
   
    return $utf8_string;
} //strtolower_utf8_extended() 

function create_seo_name($normal_name)
	{
	 
		$symbol_tobe_converted1  = str_ireplace("'", "", $normal_name);
		$symbol_tobe_converted2  = str_ireplace("@", "", $symbol_tobe_converted1);
		$symbol_tobe_converted3  = str_ireplace("(", "-", $symbol_tobe_converted2);
		$symbol_tobe_converted4  = str_ireplace(")", "", $symbol_tobe_converted3);
        $symbol_tobe_converted5  = str_ireplace("&", "", $symbol_tobe_converted4);
        $symbol_tobe_converted6  = str_ireplace("  ", "-", $symbol_tobe_converted5);
        $symbol_tobe_converted7  = str_ireplace("/", "-", $symbol_tobe_converted6);
        $symbol_tobe_converted8  = str_ireplace(" ", "-", $symbol_tobe_converted7);
        $symbol_tobe_converted9  = str_ireplace("+", "", $symbol_tobe_converted8);
        $symbol_tobe_converted10 = str_ireplace("#", "", $symbol_tobe_converted9);
        $symbol_tobe_converted11 = str_ireplace(",", "", $symbol_tobe_converted10);
        $symbol_tobe_converted12 = str_ireplace("!", "", $symbol_tobe_converted11);
		$symbol_tobe_converted13 = str_ireplace("$", "", $symbol_tobe_converted12);
		$symbol_tobe_converted14 = str_ireplace("^", "", $symbol_tobe_converted13);
		$symbol_tobe_converted15 = str_ireplace(":", "", $symbol_tobe_converted14);
		$symbol_tobe_converted16 = str_ireplace(";", "", $symbol_tobe_converted15);
		$symbol_tobe_converted17 = str_ireplace("[", "-", $symbol_tobe_converted16);
		$symbol_tobe_converted18 = str_ireplace("]", "", $symbol_tobe_converted17);
		$symbol_tobe_converted19 = str_ireplace("{", "-", $symbol_tobe_converted18);
		$symbol_tobe_converted20 = str_ireplace("}", "", $symbol_tobe_converted19);
		$symbol_tobe_converted21 = str_ireplace("|", "-", $symbol_tobe_converted20);
		$symbol_tobe_converted22 = str_ireplace("=", "-", $symbol_tobe_converted21);
		$symbol_tobe_converted23 = str_ireplace("*", "-", $symbol_tobe_converted22);
		$symbol_tobe_converted24 = str_ireplace("%", "-", $symbol_tobe_converted23);
		$symbol_tobe_converted25 = str_ireplace("~", "", $symbol_tobe_converted24);
		$symbol_tobe_converted26 = str_ireplace("`", "", $symbol_tobe_converted25);
		$symbol_tobe_converted27 = str_ireplace(".", "", $symbol_tobe_converted26);
        $symbol_tobe_converted28 = str_ireplace("_", "-", $symbol_tobe_converted27);
		$symbol_tobe_converted29 = str_ireplace("?", "", $symbol_tobe_converted28);
		$symbol_tobe_converted30 = str_ireplace("---", "-", $symbol_tobe_converted29);
		$symbol_tobe_converted31 = str_ireplace("--", "-", $symbol_tobe_converted30);
	 
			return $symbol_tobe_converted31;
    }

function create_seo_name_with_dots($normal_name)
	{
	 
		 $symbol_tobe_converted1  = str_ireplace("'", "", $normal_name);
		 $symbol_tobe_converted2  = str_ireplace("@", "", $symbol_tobe_converted1);
		 $symbol_tobe_converted3  = str_ireplace("(", "-", $symbol_tobe_converted2);
		 $symbol_tobe_converted4  = str_ireplace(")", "", $symbol_tobe_converted3);
         $symbol_tobe_converted5  = str_ireplace("&", "", $symbol_tobe_converted4);
         $symbol_tobe_converted6  = str_ireplace("  ", "-", $symbol_tobe_converted5);
         $symbol_tobe_converted7  = str_ireplace("/", "", $symbol_tobe_converted6);
         $symbol_tobe_converted8  = str_ireplace(" ", "-", $symbol_tobe_converted7);
         $symbol_tobe_converted9  = str_ireplace("+", "", $symbol_tobe_converted8);
         $symbol_tobe_converted10 = str_ireplace("#", "", $symbol_tobe_converted9);
         $symbol_tobe_converted11 = str_ireplace(",", "", $symbol_tobe_converted10);
         $symbol_tobe_converted12 = str_ireplace("---", "-", $symbol_tobe_converted11);
		 $symbol_tobe_converted13 = str_ireplace("--", "-", $symbol_tobe_converted12);
		 $symbol_tobe_converted14 = str_ireplace("!", "", $symbol_tobe_converted13);
		 $symbol_tobe_converted15 = str_ireplace("$", "", $symbol_tobe_converted14);
		 $symbol_tobe_converted16 = str_ireplace("^", "", $symbol_tobe_converted15);
		 $symbol_tobe_converted17 = str_ireplace(":", "", $symbol_tobe_converted16);
		 $symbol_tobe_converted18 = str_ireplace(";", "", $symbol_tobe_converted17);
		 $symbol_tobe_converted19 = str_ireplace("[", "-", $symbol_tobe_converted18);
		 $symbol_tobe_converted20 = str_ireplace("]", "", $symbol_tobe_converted19);
		 $symbol_tobe_converted21 = str_ireplace("{", "-", $symbol_tobe_converted20);
		 $symbol_tobe_converted22 = str_ireplace("}", "", $symbol_tobe_converted21);
		 $symbol_tobe_converted23 = str_ireplace("|", "-", $symbol_tobe_converted22);
		 $symbol_tobe_converted24 = str_ireplace("=", "-", $symbol_tobe_converted23);
		 $symbol_tobe_converted25 = str_ireplace("*", "-", $symbol_tobe_converted24);
		 $symbol_tobe_converted26 = str_ireplace("%", "-", $symbol_tobe_converted25);
		 $symbol_tobe_converted27 = str_ireplace("~", "", $symbol_tobe_converted26);
		 $symbol_tobe_converted28 = str_ireplace("`", "", $symbol_tobe_converted27);

	 
			return $symbol_tobe_converted28;
    }

function ampersand_to_and($normal_name)
	{
	 
		 $symbol_tobe_converted1  = str_ireplace("&", "and", $normal_name);

	 
			return $symbol_tobe_converted1;
    }
        
function html_escaped_output($output_value)
{
	$escaped_output = htmlspecialchars($output_value, ENT_QUOTES, "UTF-8");
	
	return $escaped_output;

}
     
?>
