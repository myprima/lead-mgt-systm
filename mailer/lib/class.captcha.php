<?php
/**
 * @package captcha
 * Created on 30.10.2006
 * @author Dmytro Plechystyy <dep@ztu.edu.ua>
 * @copyright Copyright © 2006, Dmytro Plechystyy
 * @version 1.0
 */

define("DEFAULT_CAPTCHA_WIDTH", 80);
define("DEFAULT_CAPTCHA_HEIGHT", 20);
define("DEFAULT_CAPTCHA_CHARS", "0123456789");
define("DEFAULT_CAPTCHA_CHARLENGTH", 6);
define("DEFAULT_CAPTCHA_TTF_FONTSIZE", 16);
define("DEFAULT_CAPTCHA_FONTSIZE", 3);
define("DEFAULT_JITTER_STRENGTH", 20);

/**
 * function _getCharacterSize
 * correct calculation of bounding box for a ttf character
 *
* return width and height, offset [left, top] of a ttf character
* credit for this function goes to info at rainer-schuetze dot de,
* slight modification implemented by Dmytro Plechystyy
* @param string $font : the font file
* @param string $text : the character
* @param int $size : the font size
* @param int $angle : the angle
* @access private
* @return array of the width and height, left and top.
**/
function _getCharacterSize($font, $text, $size, $angle) {
	// Get the boundingbox from imagettfbbox(), which is correct when angle is 0
	$bbox = imagettfbbox($size, 0, $font, $text);
	// Rotate the boundingbox
	$angle = $angle / 180 * pi();
	for ($i = 0; $i < 4; $i++) {
		$x = $bbox[$i * 2];
		$y = $bbox[$i * 2 + 1];
		$bbox[$i * 2] = cos($angle) * $x -sin($angle) * $y; // X
		$bbox[$i * 2 + 1] = sin($angle) * $x +cos($angle) * $y; // Y
	}
	// Variables which tells the correct width and height
	$bbox["left"] = -min($bbox[0], $bbox[2], $bbox[4], $bbox[6]);
	$bbox["top"] = -min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);
	$bbox["width"] = $bbox["left"] + max($bbox[0], $bbox[2], $bbox[4], $bbox[6]) - min($bbox[0], $bbox[2], $bbox[4], $bbox[6]);
	$bbox["height"] = $bbox["top"] + max($bbox[1], $bbox[3], $bbox[5], $bbox[7]) - min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);

	return $bbox;
}

class CaptchaGenerator {
	var $_charsAllowed;
	var $_code;
	var $_codeLength;
	var $_fontSize;
	var $_fontFilename;
	var $_img;
	var $_imgWidth;
	var $_imgHeight;
	var $_bgColor;
	var $_fgColor;
	var $_options;

	function InitSrand() {
		$milliseconds = microtime();
		$timestring = explode(" ", $milliseconds);
		$sg = $timestring[1];
		$mlsg = substr($timestring[0], 2, 4);
		$timestamp = 0 + $mlsg . $sg;
		srand($timestamp);
	}
	function CaptchaGenerator($width = 0, $height = 0) {
		$this->InitSrand();
		if (isset ($this->_img))
			imagedestroy($this->_img);
		$this->_imgWidth = ($width > 0) ? $width : DEFAULT_CAPTCHA_WIDTH;
		$this->_imgHeight = ($height > 0) ? $height : DEFAULT_CAPTCHA_HEIGHT;
		$this->_img = imagecreatetruecolor($this->_imgWidth, $this->_imgHeight);

		$this->_charsAllowed = DEFAULT_CAPTCHA_CHARS;
		$this->_codeLength = DEFAULT_CAPTCHA_CHARLENGTH;
		$this->_fontSize = 3;
		$this->_options = array (
			"size_jitter" => 1,
			"angle_jitter" => 1,
			"color_jitter" => 1,
			"jitter_strength" => DEFAULT_JITTER_STRENGTH,
			"use_ttf" => 0,
		);
		srand();
		$this->_fgColor = array (
			"red" => rand(0, 192), "green" => rand(0, 192), "blue" => rand(0, 192));
		$this->_bgColor = imagecolorallocate($this->_img, 224, 224, 224);
		register_shutdown_function(array(&$this, '_CaptchaGenerator'));
	}
	function _CaptchaGenerator() {
		if (isset ($this->_img))
			imagedestroy($this->_img);
	}
	function SetAllowedCharacters($charStr) {
		$charStr = (string) $charStr;
		if (strlen($charStr) > 0)
			$this->_charsAllowed = $charStr;
	}
	function CodeSetLength($length) {
		$length = (int) $length;
		if ($length > 0)
			$this->_codeLength = $length;
	}
	function CodeGenerate() {
		$code = "";
		$len = strlen($this->_charsAllowed);
		for ($i = 0; $i < $this->_codeLength; $i++) {
		    srand();
		    $code .= $this->_charsAllowed[rand(0, $len -1)];
		}
		$this->_code = $code;
		return $code;
	}
	function CodeGet() {
		return $this->_code;
	}
	function CodeSet($code) {
		$code = (string) $code;
		$this->_code = (strlen($code) > 0) ? $code : $this->_code;
	}
	function SetOptions($options) {
		if (gettype($options) != "array")
			exit ();
		foreach ($options as $key => $val)
			if (isset ($this->_options[$key]))
				$this->_options[$key] = 0 + $val;
	}
	function FontUseTTF($useTTF, $ttfFilename = "") {
		if ($useTTF == true && file_exists($ttfFilename)) {
			$this->_fontFilename = $ttfFilename;
			$this->_options["use_ttf"] = 1;
		} else {
			$this->_options["use_ttf"] = 0;
			$this->_fontFilename = null;
		}
	}
	function FontSetSize($size) {
		$this->_fontSize = 0 + $size;
	}
	function ColorSet($fgColor = null, $bgColor = null) {
		if ($fgColor && is_array($fgColor)) {
			$this->_fgColor = $fgColor;
		}
		if ($bgColor && is_array($bgColor)) {
			imagecolordeallocate($this->_img, $this->_bgColor);
			$this->_bgColor = imagecolorallocate($this->_img, $bgColor["red"], $bgColor["green"], $bgColor["blue"]);
		}
	}
	function Render($output = "image/jpeg", $target = "browser") {
		$w = & $this->_imgWidth;
		$h = & $this->_imgHeight;
		$o = & $this->_options;
		$fg = & $this->_fgColor;
		$fs = & $this->_fontSize;
		$js = $this->_options["jitter_strength"] / 100;
		$delta = 2;
		$oldX = $delta;
		imagefilledrectangle($this->_img, 0, 0, $w -1, $h -1, $this->_bgColor);
		for ($i = 0; $i < strlen($this->_code); $i++) {
			$curFgColor = imagecolorallocate($this->_img, $o["color_jitter"] ? ($fg["red"] + rand(- $fg["red"], $fg["red"]) * $js) : $fg["red"], $o["color_jitter"] ? ($fg["green"] + rand(- $fg["green"], $fg["green"]) * $js) : $fg["green"], $o["color_jitter"] ? ($fg["blue"] + rand(- $fg["blue"], $fg["blue"]) * $js) : $fg["blue"]);
			if ($this->_options["use_ttf"]) {
				$size = $o["size_jitter"] ? $fs +rand(- $fs, $fs) * $js : $fs;
				$angle = $o["angle_jitter"] ? rand(0, 90) * $js : 0;
				$bbox = _getCharacterSize($this->_fontFilename, $this->_code[$i], $size, $angle);
				imagettftext($this->_img, $size, $angle, $oldX + $bbox["left"] + $delta, $h - $delta, $curFgColor, $this->_fontFilename, $this->_code[$i]);
				$oldX += $bbox["width"] + $delta;
				$captchaWidth = $oldX;
			} else {
				$xpos = $i * imagefontwidth($fs);
				if ($i)
					$captchaWidth = $xpos * ($i +1) / $i;
				$ypos = $delta+rand(-imagefontheight($fs), imagefontheight($fs)) * $js;
				imagestring($this->_img, $fs, $xpos, $ypos, $this->_code[$i], $curFgColor);
			}
			imagecolordeallocate($this->_img, $curFgColor);
		}
		$tmpImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($tmpImage, $this->_img, ($w - $captchaWidth) / 2, 1, 0, 1, $captchaWidth, $h -2, $captchaWidth, $h -2);
		imagefilledrectangle($this->_img, 0, 0, $w -1, $h -1, $this->_bgColor);
		$tmpColor = imagecolorallocate($this->_img, $fg["red"], $fg["green"], $fg["blue"]);
		imagerectangle($this->_img, 0, 0, $w -1, $h -1, $tmpColor);
		imagecolordeallocate($this->_img, $tmpColor);
		imagecopyresampled($this->_img, $tmpImage, ($w - $captchaWidth) / 2, 1, ($w - $captchaWidth) / 2, 1, $captchaWidth, $h -2, $captchaWidth, $h -2);
		imagedestroy($tmpImage);
		$renderFuncs = array (
			"image/jpeg" => "imagejpeg",
			"image/png" => "imagepng",
			"image/gif" => "imagegif"
		);
		if (!function_exists($renderFuncs[$output])) {
		    foreach($renderFuncs as $type=>$func) {
			if(function_exists($func))
			    $output2=$type;
		    }
		    if(!$output2)
			exit ();
		    else $output=$output2;
		}

		if ($target == "browser") {
		    header("Content-type:$output");
		    $renderFuncs[$output] ($this->_img);
		} else {
		    $renderFuncs[$output] ($this->_img, $target);
		}
	}
}
?>
