<?php

namespace nx\parts\log;

use nx\helpers\log\Escape;

trait cli{
	/**
	 * @param ...$data
	 * @return void
	 */
	public function log(...$data): void{
		$dbt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$start = 0;
		while('log' === $dbt[$start]['function']){
			$start += 1;
		}
		$d = $dbt[$start] ?? $dbt[$start - 1];//调用函数信息在前一次位置
		$f = $dbt[$start - 1];
		$label = (is_string($data[0]) && count($data) > 1) ? "{u}" . array_shift($data) . "{uN}\t" : "";
		$v = $this->var_dump(...$data);
		echo Escape::template("\n{gL}$label{w}{12}{$d['class']}{$d['type']}{$d['function']}()\t{$f['file']}:{$f['line']}\n{0}{bkB}$v{0}");
		//echo "\n\033[30m30\033[0m \033[31m31\033[0m \033[32m32\033[0m \033[33m33\033[0m \033[34m34\033[0m \033[35m35\033[0m \033[36m36\033[0m \033[37m37\033[0m \033[38m38\033[0m \033[39m39\033[0m \033[40m40\033[0m \033[41m41\033[0m \033[42m42\033[0m \033[43m43\033[0m \033[44m44\033[0m \033[45m45\033[0m \033[46m46\033[0m \033[47m47\033[0m \033[48m48\033[0m \033[49m 49 \033[0m";
	}
	protected function var_dump(...$var): string{
		if(count($var) === 0) return '';
		//var_dump(...$var);
		ob_start();
		var_dump(...$var);
		$t = ob_get_clean();
		$rs = [
			'#=>\n(\s+)#' => '=> ',
			'#\[(".+")((:\w+))?\]#' => Escape::template("{y}\\1{37}\\3{d} "),
			'#\[(\d+)\]#' => Escape::template("{c}\\1{d} "),
			'#string\((\d+)\) (".+")#' => Escape::template("{y}\\2{bk}\\1{d}"),
			'#array\((\d+)\) #' => Escape::template("{37}[\\1]{d}"),
			'#int\((\d+)\)#' => Escape::template("{c}\\1{d}"),
			'#object\((.+)\)\#(\d+) \((\d+)\) #' => Escape::template("{32}\\1{d}{36}#\\2{37}{\\3{37}}{d}"),
			'#"(\w+)"#' => Escape::template("{y}\\0{d}"),
			'#=>#' => Escape::template("{m}\\0{d}"),
			'#protected#' => Escape::template("{w}\\0{d}"),
			'#NULL|true|false#' => Escape::template("{r}\\0{d}"),
			'#\(|\)#' => Escape::template("{c}\\0{d}"),
		];
		foreach($rs as $s => $r){
			$t = preg_replace($s, $r, $t);
		}
		return $t;
	}
}