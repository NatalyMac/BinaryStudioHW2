<?php
namespace model;

use model\Passenger;

class PassengersStack
{
	protected $stack;
	protected $limit;
	 
	public function __construct($limit) {
	    $this->stack = [];
	    $this->limit =$limit;
	}

	public function __toString(){
		return strval($this->countStack());
	}
	 
	public function push(Passenger $item) {
	    if (count($this->stack) < $this->limit) {
	        array_unshift($this->stack, $item);
	    } else {
	        throw new OverLimitException('Stack is full!');
	        }
	}
	 
	public function pop() {
	    if ($this->isEmpty()) {
	        throw new OverLimitException('Stack is empty!');
	    } else {
	        return array_shift($this->stack);
	        }
	}
	 
    public function top() {
        return current($this->stack);
    }
	 
	public function isEmpty() {
	    return empty($this->stack);
	}

	public function countStack() {
        return count($this->stack);
    }
}