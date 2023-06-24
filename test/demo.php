<?php
error_reporting(E_ALL);
include_once '../vendor/autoload.php';
const AGREE_LICENSE = true;

class demo extends \nx\app{
	use \nx\parts\log\cli;
	public function main(){
		$this->log('dada~', 'work');
	}
}
(new demo())->run();