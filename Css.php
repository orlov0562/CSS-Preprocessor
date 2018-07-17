<?php
/**
 * Simple CSS Preprocessor
 * Author: Vitaliy Orlov
 * Version: 1.0
 * Homepage: https://github.com/orlov0562/CSS-Preprocessor
 * Usage examples: https://github.com/orlov0562/CSS-Preprocessor/blob/master/README.md
 */

class Css {

    public static function begin() {
        ob_start();
    }

    public static function end($vars=[], $minify=true, $output=true){
        $css = self::compile(ob_get_clean(), $vars, $minify);
        if ($output) echo $css; else return $css;
    }

    public static function compile($css, $vars=[], $minify=true) {
        if (self::hasFoldedBlocks($css)) {
            $css = self::unfoldBlocks($css);
        }
        if ($vars && self::hasVars($css)) {
            $css = self::addVars($css, $vars);
        }
        if ($minify) {
            $css = self::minify($css);
        }
        return $css;
    }

    protected static function unfoldBlocks($css) {
    	while (preg_match('~(?<=^|}|\*/) (\s*) ([^*{}\[]+)  \[  ([^\[\]]+)  \]~msx', $css, $regs)) {
    		$basePathes = explode(',',$regs[2]);
    		$cssBlock = trim($regs[3]);
    		$unfoldedCss = '';
    		if (preg_match_all('~(?<=^|}) ([^{]+)? (\{[^}]+\})~Umx', $cssBlock, $sregs)) {
    			for ($i=0; $i<count($sregs[0]); $i++) {
    				$subPathes = $sregs[1][$i]
    				             ? explode(',', $sregs[1][$i])
    				             : []
    				;
    				$subCss = $sregs[2][$i];

    				if (!$subPathes) {
    					$unfoldedCss .= implode(',', $basePathes).' '.$subCss.PHP_EOL;
    				} else {
    				    $combinedPathes = implode(',', array_map(function($subPath) use ($basePathes){
    				        $ret = '';
    				        if(preg_match('~^\s*:~',$subPath)) {
            				    $ret = implode(', ', array_map(function($basePath) use ($subPath){
            				            return rtrim($basePath).trim($subPath);
            				    }, $basePathes));
    				        } else {
            				    $ret = implode(', ', array_map(function($basePath) use ($subPath){
            				            return rtrim($basePath).' '.trim($subPath);
            				    }, $basePathes));
    				        }
    				        return $ret;
    				    }, $subPathes));

    				    $unfoldedCss .= $combinedPathes.' '.$subCss.PHP_EOL;
    				}
    			}
    		}

    		$css = str_replace(trim($regs[0]), $unfoldedCss, $css);
    	}
    	return $css;
    }

    protected static function hasFoldedBlocks($css){
        return strpos($css, '[')!==false && strpos($css, ']')!==false;
    }

    protected static function hasVars($css){
        return strpos($css, '$') !== false;
    }

    protected static function addVars($css, $vars) {
        krsort($vars);
        $keys = array_keys($vars);
        $keys = array_map(function($key){return '$'.$key;}, $keys);
        $css = str_replace($keys, $vars, $css);
        return $css;
    }

    // Method borrowed from here:
    // https://github.com/maybeworks/yii2-minify
    protected static function minify($input)
    {
        if (trim($input) === "") {
            return $input;
        }
        return preg_replace(
            [
                // Remove comment(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                // Remove unused white-space(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                // Replace `:0 0 0 0` with `:0`
                '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                // Replace `background-position:0` with `background-position:0 0`
                '#(background-position):0(?=[;\}])#si',
                // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
                '#(?<=[\s:,\-])0+\.(\d+)#s',
                // Minify string value
                '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
                '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                // Minify HEX color code
                '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                // Replace `(border|outline):none` with `(border|outline):0`
                '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                // Remove empty selector(s)
                '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
            ],
            [
                '$1',
                '$1$2$3$4$5$6$7',
                '$1',
                ':0',
                '$1:0 0',
                '.$1',
                '$1$3',
                '$1$2$4$5',
                '$1$2$3',
                '$1:0',
                '$1$2'
            ],
            $input);
    }
}
