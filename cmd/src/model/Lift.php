<?php
namespace model;

use model\Passenger;
use model\PassengerStack;
use model\OverLimitException;

class Lift{
	public static $maxFloor;
	public $maxPassengersMove;
    public $maxPassengersIn;
    protected $currentFloor;
    protected $currentPassengers;
    protected $blockOverload = false;
    // wait, up, down
    protected $stateMoving; 
    public static $callStack = [ [0, 0], 
                                 [0, 0], 
                                 [0, 0], 
                                 [0, 0], 
                                 [0, 0], 
                                 [0, 0], 
                                 [0, 0], 
                                 [0, 0], 
                                 [0, 0], 
                                ];

    public function __construct($maxFloor, $maxPassengersMove, $maxPassengersIn, $stateMoving = 'up'){
    	if (!($maxFloor > 0 and $maxPassengersMove > 0 and $maxPassengersIn > 0)) 
    		throw new LiftException("Incorrect init value: passengers = ".$maxPassengers.", floors = ".$maxFloor.
                ", passengers in lift = ".$maxPassengersIn, 0); 
    	$this->maxFloor = $maxFloor;
    	$this->maxPassengersMove = $maxPassengersMove;
        $this->maxPassengersIn = $maxPassengersIn;
    	$this->currentFloor = 0;
    	$this->currentPassengers = new PassengersStack($maxPassengersIn);
        $this->stateMoving = $stateMoving;
    }
    
    public function checkOverload(){
        if ($this->currentPassengers->countStack() > $this->maxPassengersMove){
            $this->blockOverload = true;
        } else $this->blockOverload = false;
    }

    public function moveDestFloor(){
        if ($this->blockOverload) {
            $numOut = $this->currentPassengers->countStack() - $this->maxPassengersMove; 
            throw new OverLimitException("Overload, lift moving is blocked. $numOut passengers should get out \n", 1);
        }

        $destFloor = $this->currentFloor; 
        $callStack = Lift::$callStack;
        
        if ($this->stateMoving == 'up'){ 
            for ($i = $this->currentFloor; $i <= $this->maxFloor; $i++){ 
                if ($callStack[$i][0] == 1) {
                    $destFloor = $i;
                    break;
                }
            }
            if ($destFloor == $this->currentFloor){
                $this->stateMoving == 'down';
                    for ($i = $this->currentFloor; $i >=0 ; $i--){ 
                        if ($callStack[$i][0] == 1){
                            $destFloor = $i;
                            //print_r("!!!!!!!! \n");
                            //print_r("destination, $i \n");
                            break;
                        }
                    } 
                while ($this->currentFloor > $destFloor) {$this->moveDown();}
            }
            else while ($this->currentFloor < $destFloor) {$this->moveUp();}
        }
       
        if  ($this->stateMoving == 'down') {
          //  print_r("dowwwwwwwwwwwwwww\n");
            for ($i = $this->currentFloor; $i >=0 ; $i--){ 
                if ($callStack[$i][0] == 1){
                        $destFloor = $i;
                     //   print_r("!!!!!!!! \n");
                      //  print_r("destination, $i \n");
                        break;
                } 
            }
            if ($destFloor == $this->currentFloor){
                $this->stateMoving == 'up';
                //print_r("ifffffff\n");
                for ($i = $this->currentFloor; $i <= $this->maxFloor; $i++){ 
                    if ($callStack[$i][0] == 1) {
                        $destFloor = $i;
                        break;
                    }
                }
                while ($this->currentFloor < $destFloor) {$this->moveUp();}
            }
            else {
                //print_r("movingggggggggg down \n");
                while ($this->currentFloor > $destFloor) {$this->moveDown();}}
        } 
        self::$callStack[$destFloor][0] = 0;
        
       
    }
 
    protected function getInLift(array $passengers){
        foreach ($passengers as $passenger) {
            $this->currentPassengers->push($passenger);
        $numOut = $this->checkOverload();
      //  if ($this->blockOverload) 
      //    throw new OverLimitException("Overload, lift moving is blocked. ".$numOut." passenger(s) should get out");
        }
    
    }
    
    protected function getOutLift(array $passengers){
        $passengersOut =  self::$callStack[$this->currentFloor][1];
        if (count($passengers) < $passengersOut)
            throw new OverLimitException("Should get out".$passengersOut."passengers \n", 2);
        foreach ($passengers as $passenger) {
            $this->currentPassengers->pop($passenger);
            self::$callStack[$this->currentFloor][1] = self::$callStack[$this->currentFloor][1] -1;
        }
        
    }

    protected function moveUp(){
        $this->stateMoving = 'up';
    	$this->currentFloor = $this->currentFloor + 1;
        if ($this->currentFloor == $this->maxFloor) $this->stateMoving = 'down';
    }
    
    protected function moveDown(){
        $this->stateMoving = 'down';
    	$this->currentFloor = $this->currentFloor - 1;
        if ($this->currentFloor == 0) $this->stateMoving = 'up';
    }
 
    public function run(){
        print_r("inithialization \n");
        print_r("lift maxFloor $this->maxFloor \n");
        print_r("lift maxPassengersMove $this->maxPassengersMove \n");
        print_r("lift maxPassengersIn $this->maxPassengersIn \n");
        print_r("lift currentFloor $this->currentFloor \n");
    	print_r("lift stateMoving $this->stateMoving \n");
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("\n");

        print_r("load peoples \n");
        $pass1 = new Passenger(0,5);
        $pass2 = new Passenger(0,7);
        $pass3 = new Passenger(0,6);
        $pass4 = new Passenger(0,8);
        $pass5 = new Passenger(1,6); 
        $this->getInLift([$pass1, $pass2, $pass3, $pass4, $pass5]);
        $pass1->setFloor();
        $pass2->setFloor();
        $pass3->setFloor();
        $pass4->setFloor();

        print_r("Lift state before start moving \n");
        print_r("lift currentFloor $this->currentFloor \n");
        //print_r("call Stack \n");
        //var_dump(self::$callStack);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        print_r("lift stateMoving $this->stateMoving \n");
        print_r("\n");
       
        print_r("moving \n");
        print_r("\n");
        
        try {
            $this->moveDestFloor();}
        catch (OverLimitException $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
                print_r("Extra peoples is getting out \n");
                $this->getOutLift([$pass5]);
                $this->checkOverload();
                $numpass = $this->currentPassengers->countStack();
                print_r("current passengers $numpass \n");
                print_r("blockOverload  ");
                var_dump($this->blockOverload);
            }
        $this->moveDestFloor();
        print_r("\n");
        print_r("destination floor $this->currentFloor \n");
        print_r("lift stateMoving $this->stateMoving \n");
        print_r("lift currentFloor $this->currentFloor \n");
        print_r("people get out $pass1, $pass2 \n");
        $this->getOutLift([$pass1,$pass2]);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
       // print_r("call Stack \n");
       // var_dump(self::$callStack);

        print_r("\n");
        $pass6 = new Passenger(0,8);
        $pass6->callLift();
        print_r("new call $pass6 \n");
        print_r("lift currentFloor $this->currentFloor \n");
        print_r("lift stateMoving $this->stateMoving \n");
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");

        //print_r("call Stack \n");
        //var_dump(self::$callStack);
    
        print_r("\n");
        print_r("moving");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("lift stateMoving $this->stateMoving \n");

        $this->moveDestFloor();
        print_r("lift stateMoving $this->stateMoving \n");

        print_r("lift currentFloor $this->currentFloor \n");
        print_r("people get out $pass3 \n");
        $this->getOutLift([$pass3]);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        //print_r("call Stack \n");
        //var_dump(self::$callStack);

        //8
        print_r("\n");
        print_r("moving 8\n");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("lift stateMoving $this->stateMoving \n");

        $this->moveDestFloor();
        print_r("lift stateMoving $this->stateMoving \n");

        print_r("lift currentFloor $this->currentFloor \n");
        print_r("people get out $pass4 \n");
        $this->getOutLift([$pass4]);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        //print_r("call Stack \n");
        //var_dump(self::$callStack);

        //0
        print_r("\n");
        print_r("moving 0 no passengers\n");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("lift stateMoving $this->stateMoving \n");

        $this->moveDestFloor();
        print_r("lift stateMoving $this->stateMoving \n");

        print_r("lift currentFloor $this->currentFloor \n");
        // print_r("people get out $pass4 \n");
        // $this->getOutLift([$pass4]);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        //print_r("call Stack \n");
        //var_dump(self::$callStack);


        //empty stack
        print_r("\n");
        print_r("moving  call stack empty\n");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("0000lift stateMoving $this->stateMoving \n");

        $this->moveDestFloor();
        print_r("lift stateMoving $this->stateMoving \n");

        print_r("lift currentFloor $this->currentFloor \n");
       // print_r("call Stack \n");
       // var_dump(self::$callStack);
        print_r("\n");
        print_r("load peoples \n");
        print_r("lift currentFloor $this->currentFloor \n");
        $pass6 = new Passenger(0,5);
        $this->getInLift([$pass6]);
        $pass6->setFloor();
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("lift stateMoving $this->stateMoving \n");

        //print_r("lift currentFloor $this->currentFloor \n");
       // print_r("call Stack \n");
        //var_dump(self::$callStack);
        
        //5
        print_r("\n");
        print_r("moving 5\n");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("lift stateMoving $this->stateMoving \n");

        $this->moveDestFloor();
        print_r("lift stateMoving $this->stateMoving \n");

        print_r("lift currentFloor $this->currentFloor \n");
        print_r("people get out $pass6 \n");
        $this->getOutLift([$pass6]);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        //print_r("call Stack \n");
        //var_dump(self::$callStack);
        print_r("\n");
        print_r("load peoples \n");
        print_r("lift currentFloor $this->currentFloor \n");
        $pass7 = new Passenger(5,3);
        $this->getInLift([$pass7]);
        $pass7->setFloor();
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("lift currentFloor $this->currentFloor \n");
        //print_r("call Stack \n");
        //var_dump(self::$callStack);
        
        //3
        print_r("\n");
        print_r("moving 3\n");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("lift stateMoving $this->stateMoving \n");
        //print_r("lift stateMoving $this->stateMoving \n");

        $this->moveDestFloor();
        print_r("lift stateMoving $this->stateMoving \n");
        print_r("lift currentFloor $this->currentFloor \n");
        print_r("people get out $pass7 \n");
        $this->getOutLift([$pass7]);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        //print_r("call Stack \n");
        //var_dump(self::$callStack);
       
        //empty stack
        print_r("\n");
        print_r("moving  call stack empty\n");
        print_r("blockOverload  ");
        var_dump($this->blockOverload);
        $numpass = $this->currentPassengers->countStack();
        print_r("current passengers $numpass \n");
        print_r("0000lift stateMoving $this->stateMoving \n");

        $this->moveDestFloor();
        print_r("lift stateMoving $this->stateMoving \n");

        print_r("lift currentFloor $this->currentFloor \n");
       // print_r("call Stack \n");
       // var_dump(self::$callStack);

    }

}