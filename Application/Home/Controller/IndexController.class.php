<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
	
    public function index(){
        $this->show('
        23
        23
        2
        ','utf-8');
    }
	
	
	function test(){
		
		$this->show('$content');
		
	}
	
	
	
}