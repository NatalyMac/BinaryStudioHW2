<?php
namespace model;

use model\Passenger;
use model\PassengerStack;
use model\OverLimitException;
use model\LiftException;


class Lift{
	/**
     * @param  $maxPassengers int
     * @param  $callStack array of int
     * @param  $maxFloor int    
     * @param  $currentFloor int 
     * @param  $currentPassengers array of Passenger objects
     * @param  $blockOverload boolean
     * @param  $stateMoving = 'up', 'down';
     */
	public        $maxPassengers;
    public static $callStack = [0,0,0,0,0,0,0,0,0];  
    public static $maxFloor;                   
    protected     $currentFloor;
    protected     $currentPassengers;
    protected     $blockOverload = false;
    protected     $stateMoving = 'up';


    /**
     * Lift constructor.
     * @param $maxFloor int
     * @param $maxPassengers int
     */
    public function __construct($maxFloor, $maxPassengers){
    	
        if ($maxFloor < 0 or $maxPassengers < 0) 
    		throw new LiftException("Incorrect init value: passengers = ".$maxPassengers.", floors = ".$maxFloor."\n", 0); 
    	$this->maxFloor = $maxFloor;
    	$this->maxPassengers = $maxPassengers;
    	$this->currentFloor = 0;
    	$this->currentPassengers = new PassengersStack($maxPassengers);
        print("inithialization \n");
        print("Lift maxFloor from 0 to ".$this->maxFloor."\n");
        print("Lift maxPassengers ".$this->maxPassengers."\n");
        $this->printStateLift();
    }

    /**
     * print Lift Call Stack
     */
    public function printCallStack(){
        
        $callStack = self::$callStack;
        print("---------Call Stack------------\n");
        for ($i=0; $i<= $this->maxFloor; $i++)
        { 
            print("Floor num $i,  ");
            print($callStack[$i]);
            print("\n");
        }
        print("-------------------------------\n");
    }

    /**
     * print Lift State
     */
    public function printStateLift(){
        
        print("---------Lift State------------\n");
        print("Lift currentFloor ".$this->currentFloor."\n");
        print("Current quantity of passengers ".$this->currentPassengers."\n");
        print("Lift stateMoving ".$this->stateMoving."\n");
        print("blockOverload  ");
        var_dump($this->blockOverload);
        print("----------------------------------\n");

    }

    /**
     * @throws \model\LiftException
     * make decision about destination floor and moving direction lift according Call Stack
     */
    public function moveDestFloor(){
       
        if ($this->blockOverload) {
            throw new LiftException("Overload, lift moving is blocked. Check overload \n", 1);}
        print("From current floor ".$this->currentFloor."\n");
        $destFloor = $this->currentFloor; 
        $callStack = Lift::$callStack;
        
        if ($this->stateMoving == 'up'){ 
            for ($i = $this->currentFloor; $i <= $this->maxFloor; $i++){ 
                if ($callStack[$i] == 1) {
                    $destFloor = $i;
                    break;
                }
            }
            if ($destFloor == $this->currentFloor){
                $this->stateMoving == 'down';
                    for ($i = $this->currentFloor; $i >=0 ; $i--){ 
                        if ($callStack[$i] == 1){
                            $destFloor = $i;
                            break;
                        }
                    } 
                print("Moving to ".$destFloor."\n");
                while ($this->currentFloor > $destFloor) {$this->moveDown();}
            }
            else {  
                print("Moving to ".$destFloor."\n"); 
                while ($this->currentFloor < $destFloor) {$this->moveUp();}
            }
        }
        if  ($this->stateMoving == 'down') {
            for ($i = $this->currentFloor; $i >=0 ; $i--){ 
                if ($callStack[$i] == 1){
                        $destFloor = $i;
                        break;
                } 
            }
            if ($destFloor == $this->currentFloor){
                $this->stateMoving == 'up';
                for ($i = $this->currentFloor; $i <= $this->maxFloor; $i++){ 
                    if ($callStack[$i] == 1) {
                        $destFloor = $i;
                        break;
                    }
                }
                print("Moving to ".$destFloor."\n");
                while ($this->currentFloor < $destFloor) {$this->moveUp();}
            }
            else {
                print("Moving to ".$destFloor."\n");
                while ($this->currentFloor > $destFloor) {$this->moveDown();}
            }
        } 
        print("Lift got destination floor ".$this->currentFloor."\n");
        self::$callStack[$destFloor] = 0;    
    }

    /**
     * trigger for property blockOverLoad
     */
    protected function checkOverload(){
       
        if ($this->currentPassengers->countStack() <= $this->maxPassengers){
            $this->blockOverload = false;
        } else $this->blockOverload = true;
    }

    /**
     * @param array $passengers of objects Passenger
     */
    protected function getInLift(array $passengers){
        
        try { 
                foreach ($passengers as $passenger) {
                    $this->currentPassengers->push($passenger);
                    print("Get into the Lift ".$passenger);
                }
            }
        catch (OverLimitException $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            $this->blockOverload = true;
        }
    }

    /**
     * @param array $passengers of objects Passenger
     */
    protected function getOutLift(array $passengers){
        
        try {
            foreach ($passengers as $passenger) {
                $this->currentPassengers->pop($passenger);
                print("Get out the Lift ".$passenger);
            }
        }
        catch (OverLimitException $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }

    /**
     * just lift moving till get destination up
     */
    protected function moveUp(){
        
        $this->stateMoving = 'up';
    	$this->currentFloor = $this->currentFloor + 1;
        if ($this->currentFloor == $this->maxFloor) $this->stateMoving = 'down';
    }

    /**
     * just lift moving till get destination down
     */
    protected function moveDown(){
        
        $this->stateMoving = 'down';
    	$this->currentFloor = $this->currentFloor - 1;
        if ($this->currentFloor == 0) $this->stateMoving = 'up';
    }


    /**
     * @throws \model\LiftException
     * test tascks 
     */
    public function run(){
        
        $pass1 = new Passenger(0,5);
        $pass2 = new Passenger(0,7);
        $pass3 = new Passenger(0,6);
        $pass4 = new Passenger(0,8);
        $pass5 = new Passenger(0,6); 
        $this->getInLift([$pass1, $pass2, $pass3, $pass4, $pass5]);
        $pass1->setFloor();
        $pass2->setFloor();
        $pass3->setFloor();
        $pass4->setFloor();
        $pass5->setFloor();
        $this->printStateLift();
        try {
            $this->moveDestFloor();}
        catch (LiftException $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
                $this->checkOverload();
                $this->printStateLift();
                $this->printCallStack();
            }
        $this->moveDestFloor();
        $this->getOutLift([$pass1, $pass2]);
        $this->printStateLift();
        
        $pass6 = new Passenger(0,8);
        $pass6->callLift();
        $this->printStateLift();
        $this->printCallStack();
        
        $this->moveDestFloor();
        $this->getOutLift([$pass3]);
        $this->printStateLift();
        $this->printCallStack();

        $this->moveDestFloor();
        $this->getOutLift([$pass2]);
        $this->printStateLift();
        $this->printCallStack();

        $this->moveDestFloor();
        $this->getOutLift([$pass4]);
        $this->printStateLift();
        $this->printCallStack();
        
        $this->moveDestFloor();
        $this->printStateLift();
        $this->printCallStack();
        
        $this->moveDestFloor();
        $this->printStateLift();
        $this->printCallStack();

        $this->getInLift([$pass6]);
        $pass6->setFloor();
        $this->printStateLift();
        $this->printCallStack();
        
        $pass7 = new Passenger(5,3);
        $pass7->callLift();
        $this->printStateLift();
        $this->printCallStack();
        
        $this->moveDestFloor();
        $this->getInLift([$pass7]);
        $pass7->setFloor();
        $this->printStateLift();
        $this->printCallStack();
        
        $this->moveDestFloor();
        $this->printStateLift();
        $this->printCallStack();
        
        $this->getOutLift([$pass6]);
        $this->printStateLift();
        $this->printCallStack();
        
        $this->moveDestFloor();
        $this->printStateLift();
        $this->printCallStack();
    }

}