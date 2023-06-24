<?php
namespace nx\helpers\log;
/**
 * @see  https://zhuanlan.zhihu.com/p/390666800
 */
enum Escape: int{
	case Reset = 0;
	case Bold = 1;
	case Faint = 2;
	case Italic = 3;
	case Underline = 4;
	case blinkSlow = 5;
	case blinkRapid = 6;
	case Reverse = 7;
	case Hide = 8;
	case Strike = 9;
	case fontDefault = 10;
	case font1 = 11;
	case font2 = 12;
	case font3 = 13;
	case font4 = 14;
	case font5 = 15;
	case font6 = 16;
	case font7 = 17;
	case font8 = 18;
	case font9 = 19;
	case fontBlackLetter = 20;
	case UnderlineDouble = 21;
	case Normal = 22;
	case notItalic = 23;
	case notUnderline = 24;
	case notBlink = 25;
	case notReversed = 27;
	case Reveal = 28;
	case notStrike = 29;
	case colorBlack = 30;
	case colorRed = 31;
	case colorGreen = 32;
	case colorYellow = 33;
	case colorBlue = 34;
	case colorMagenta = 35;
	case colorCyan = 36;
	case colorWhite = 37;
	case colorCustom = 38;
	case colorDefault = 39;
	case Frame = 51;
	case Encircle = 52;
	case Overline = 53;
	case notBorder = 54;
	case notOverline = 55;
	case setUnderlineColor = 58;
	case clearUnderlineColor = 59;
	public static function template(string $string): string{
		return preg_replace_callback("#{(\w+)}#", function($matches){
			$match = $matches[1];
			if(is_numeric($match)){
				return "\033[{$match}m";
			}else{
				$r = $matches[0];
				//$c = -1;
				match ($match) {
					"reset" => $c = 0, //$r = (Escape::Reset)(),//0
					"B", "bold" => $c = 1, //$r = (Escape::Bold)(),//1
					"f", "faint" => $c = 2, //$r = (Escape::Faint)(),//2
					"i", "italic" => $c = 3, //$r = (Escape::Italic)(),//3
					"u", "under" => $c = 4, //$r = (Escape::Underline)(),//4
					"bS", "blinkS" => $c = 5, //$r = (Escape::blinkSlow)(),//5
					"bR", "blinkR" => $c = 6, //$r = (Escape::blinkRapid)(),//6
					"R", "reverse" => $c = 7, //$r = (Escape::Reverse)(),//7
					"h", "hide" => $c = 8, //$r = (Escape::Hide)(),//8
					"s", "strike" => $c = 9, //$r = (Escape::Strike)(),//9
					"U", "underD" => $c = 21, //$r = (Escape::UnderlineDouble)(),//21
					"n", "normal" => $c = 22, //$r = (Escape::Normal)(),//22
					"iN", "italicN" => $c = 23, //$r = (Escape::notItalic)(),//23
					"uN", "underN" => $c = 24, //$r = (Escape::notUnderline)(),//24
					"bN", "blinkN" => $c = 25, //$r = (Escape::notBlink)(),//25
					"rN", "reverseN" => $c = 27, //$r = (Escape::notReversed)(),//27
					"Re", "reveal" => $c = 28, //$r = (Escape::Reveal)(),//28
					"sN", "strikeN" => $c = 29, //$r = (Escape::notStrike)(),//29
					"bk", 'black' => $c = 30, //$r = (Escape::colorBlack)(),//30
					"r", 'red' => $c = 31, //$r = (Escape::colorRed)(),
					"g", 'green' => $c = 32, //$r = (Escape::colorGreen)(),
					"y", 'yellow' => $c = 33, //$r = (Escape::colorYellow)(),
					"b", "blue" => $c = 34, //$r = (Escape::colorBlue)(),
					"m", "magenta" => $c = 35, //$r = (Escape::colorMagenta)(),
					"c", "cyan" => $c = 36, //$r = (Escape::colorCyan)(),
					"w", "white" => $c = 37, //$r = (Escape::colorWhite)(),
					"d" => $c = 39, //$r = (Escape::colorDefault)(),
					"bkB", "blackB" => $c = 40, //$r = (Escape::colorBlack)(background: true),//40
					"rB", "redB" => $c = 41, //$r = (Escape::colorRed)(background: true),
					"gB", "greenB" => $c = 42, //$r = (Escape::colorGreen)(background: true),
					"yB", "yellowB" => $c = 43, //$r = (Escape::colorYellow)(background: true),
					"bB", "blueB" => $c = 44, //$r = (Escape::colorBlue)(background: true),
					"mB", "magentaB" => $c = 45, //$r = (Escape::colorMagenta)(background: true),
					"cB", "cyanB" => $c = 46, //$r = (Escape::colorCyan)(background: true),
					"wB", "whiteB" => $c = 47, //$r = (Escape::colorWhite)(background: true),
					"dB" => $c = 49, //$r = (Escape::colorDefault)(background: true),
					"boF", "borderF" => $c = 51, //$r = (Escape::Frame)(),//51
					"boE", "borderE" => $c = 52, //$r = (Escape::Encircle)(),//52
					"o", "overline" => $c = 53, //$r = (Escape::Overline)(),//53
					"boN", "borderN" => $c = 54, //$r = (Escape::notBorder)(),//54
					"oN", "overlineN" => $c = 55, //$r = (Escape::notOverline)(),//55
					"bkL", "blackL" => $c = 90, //$r = (Escape::colorBlack)(bright: true),//90
					"rL", "redL" => $c = 91, //$r = (Escape::colorRed)(bright: true),
					"gL", "greenL" => $c = 92, //$r = (Escape::colorGreen)(bright: true),
					"yL", "yellowL" => $c = 93, //$r = (Escape::colorYellow)(bright: true),
					"bL", "blueL" => $c = 94, //$r = (Escape::colorBlue)(bright: true),
					"mL", "magentaL" => $c = 95, //$r = (Escape::colorMagenta)(bright: true),
					"cL", "cyanL" => $c = 96, //$r = (Escape::colorCyan)(bright: true),
					"wL", "whiteL" => $c = 97, //$r = (Escape::colorWhite)(bright: true),
					"dL" => $c = 99, //$r = (Escape::colorDefault)(bright: true),
					default => $c = -1,// var_dump($match),
				};
				if($c > -1) return "\033[{$c}m";
				return $r;
			}
		}, $string);
	}
	public function __invoke(bool $bright = false, bool $background = false, array $colorRGB = [], int $colorN = 0): string{
		$code = $this->value + ($bright ? 60 : 0) + ($background ? 10 : 0);
		$color = "";
		if(Escape::colorCustom === $this || Escape::setUnderlineColor === $this){
			if(count($colorRGB) === 3){
				$color = ";2;" . implode(";", $colorRGB);
			}else if($colorN > 0) $color = ";5;" . $colorN;
		}
		return "\033[$code{$color}m";
	}
	public function quote(string $string, bool $bright = false, bool $background = false, bool $withReset = true, array $colorRGB = [], int $colorN = 0): string{
		$color = $this($bright, $background, $colorRGB, $colorN);
		$reset = $withReset ? (Escape::Reset)() : "";
		return "$color$string$reset";
	}
}
