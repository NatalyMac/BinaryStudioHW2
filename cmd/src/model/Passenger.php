<?php
namespace model;

use model\Lift;


class Passenger{
	public $currentFloor;
	public $destFloor;
    
    public function __construct($currentFloor, $destFloor){
    	$maxFloor = Lift::$maxFloor;
    	if (($currentFloor > $maxFloor and $currentFloor < 0) or ($destFloor > $maxFloor and $destFloor <0)) 
    		throw new LiftException("Incorrect ini value: currentFloor  = ".$currentFloor.", destination floor = ".$destFloor, 0); 
    	$this->currentFloor = $currentFloor;
    	$this->destFloor = $destFloor;
    }
    
    public function __toString(){
		return "I am passenger. I am from current floor $this->currentFloor to destFloor $this->destFloor";
	}
	
	public function setFloor(){
		$passengers = Lift::$callStack[$this->destFloor][1];
		Lift::$callStack[$this->destFloor] = [1, $passengers + 1];

	}

	public function  callLift(){
		Lift::$callStack[$this->currentFloor][0] = 1;
	}
}