<?php

namespace nx\parts\log;

use nx\helpers\log\Escape;
use nx\parts\log;

/**
 * @property string $log_cli_name     logger的名称，允许覆盖（只保留最后一个），或并存（不同名称）
 * @property bool   $log_cli_deferred 是否为延迟输出 true 为结束时输出 false 立刻
 */
trait cli{
	use log;

	protected function nx_parts_log_cli(): void{
		if(PHP_SAPI !== 'cli') return;
		if(null === $this->log) $this->log = new \nx\helpers\log();
		$this->log->addWriter($this->log_cli_writer(...),
			$this->log_cli_name ?? 'default',
			$this->log_cli_deferred ?? false
		);
	}
	protected function log_cli_writer($log): void{
		if($this->log_cli_deferred ?? false){
			foreach($log as $item){
				var_dump($item);
			}
		}
		else{
			if('!runtime'===$log['level']){
				var_dump($log['message']);
			} else{
				$dbt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
				$start = 0;//$this->log()
				while(!('log' === $dbt[$start]['function'] && __CLASS__ ===$dbt[$start]['class'])){
					$start += 1;
					if($start >count($dbt)-1) break;
				}
				if($start >count($dbt)-1){//$this->log->debug()
					$start =0;
					while("Psr\Log\AbstractLogger" !==$dbt[$start]['class']){
						$start += 1;
						if($start >count($dbt)-1) break;
					}
					if($start >count($dbt)-1){//$this->log->log()
						$start =0;
						while("nx\helpers\log" !==$dbt[$start]['class']){
							$start += 1;
							if($start >count($dbt)-1) break;
						}
					}
				}
				$d = $dbt[$start+1];// ?? $dbt[$start - 1];//调用函数信息在前一次位置
				$f = $dbt[$start];
				match ($log['level']){
					'emergency', 'alert', 'critical', 'error' =>$color ='{rB}',
					'warning'=>$color ='{yB}',
					'notice'=>$color ='{mB}',
					'info'=>$color ='{bB}',
					'debug'=>$color ='{cB}',
					default=>$color ='{gB}',
				};
				$label = "{$color}{bk} " . $log['level'] . " {n}{d}{dB}".str_repeat(" ", 9-strlen($log['level']));
				if(!empty($log['context'])){
					$v = \nx\helpers\log::interpolate($log['message'], $log['context']);
				}
				else {
					if('runtime' ===$log['level']){
						$log['message'] =str_replace("runtime: \n", "", $log['message']);
					}
					$v = $this->log_cli_var_dump($log['message'], !('runtime' === $log['level']));
				}
				echo Escape::template("\n$label\t{12}{bkL}{$d['class']}{$d['type']}{$d['function']}(){n}\t{$f['file']}:{$f['line']}\n{0}{bkB}$v{0}");
				//echo "\n\033[30m30\033[0m \033[31m31\033[0m \033[32m32\033[0m \033[33m33\033[0m \033[34m34\033[0m \033[35m35\033[0m \033[36m36\033[0m \033[37m37\033[0m \033[38m38\033[0m \033[39m39\033[0m \033[40m40\033[0m \033[41m41\033[0m \033[42m42\033[0m \033[43m43\033[0m \033[44m44\033[0m \033[45m45\033[0m \033[46m46\033[0m \033[47m47\033[0m \033[48m48\033[0m \033[49m 49 \033[0m";
			}
		}
	}
	protected function log_cli_var_dump($var, $dump =true): string{
		ob_start();
		if(!$dump && is_string($var)) echo $var;
		else var_dump($var);
		$t = ob_get_clean();
		$rs = [
			'#=>\n(\s+)#' => '=> ',
			'#\[(".+")((:\w+))?\]#' => Escape::template("{y}\\1{37}\\3{d} "),
			'#\[(\d+)\]#' => Escape::template("{c}\\1{d} "),
			'#\[([\d\.]+)\]#' => Escape::template("{c}\\1{d}{bkL}ms{d}"),
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