<?php
error_reporting(E_ALL);
include_once '../vendor/autoload.php';
const AGREE_LICENSE = true;

class demo extends \nx\app{
	use \nx\parts\log\cli, \nx\parts\runtime;
	public function main(){
		$this->log('dada~');
		$this->log->emergency('emergency');
		$this->log->alert('alert');
		$this->log->critical('critical');
		$this->log->error('error');
		$this->log->warning('warning');
		$this->log->notice('notice');
		$this->log->info('info');
		$this->log->debug('debug');
		$this->log($this);
		$this->runtime('this\'s runtime');
	}
}
(new demo())->run();