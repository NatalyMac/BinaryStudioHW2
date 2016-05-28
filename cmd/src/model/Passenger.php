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
    	print($this);
    }
    
    public function __toString(){
		
		return "I am passenger from floor ".$this->currentFloor." to floor ".$this->destFloor."\n";
	}
	
	public function setFloor(){
		
		Lift::$callStack[$this->destFloor] = 1;
		print("Press button ".$this->destFloor."\n");

	}

	public function  callLift(){
		
		Lift::$callStack[$this->currentFloor] = 1;
		print("Call lift from floor ".$this->currentFloor."\n");
	}
}