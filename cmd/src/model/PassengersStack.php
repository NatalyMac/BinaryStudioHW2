<?php
namespace model;

use model\Passenger;

class PassengersStack
{   

	/**
	 * @param $stack array of Passenger objects
	 * @param $limit - max number Passengers in the lift
     */
	protected $stack;
	protected $limit;

	/**
	 * PassengersStack constructor.
	 * @param $limit
	 * @param $stack array of Passenger objects
     */
	public function __construct($limit) {
	    $this->stack = [];
	    $this->limit =$limit;
	}

	/**
	 * @return string
     */
	public function __toString(){
		return strval($this->countStack());
	}

	/**
	 * @param \model\Passenger $item
	 * @throws OverLimitException
     */
	public function push(Passenger $item) {
	    if (count($this->stack) < $this->limit) {
	        array_unshift($this->stack, $item);
	    } else {
	        throw new OverLimitException('Stack is full!');
	        }
	}

	/**
	 * @return mixed
	 * @throws OverLimitException
     */
	public function pop() {
	    if ($this->isEmpty()) {
	        throw new OverLimitException('Stack is empty!');
	    } else {
	        return array_shift($this->stack);
	        }
	}

	/**
	 * @return mixed
     */
	public function top() {
        return current($this->stack);
    }

	/**
	 * @return bool
     */
	public function isEmpty() {
	    return empty($this->stack);
	}

	/**
	 * @return mixed
     */
	public function countStack() {
        return count($this->stack);
    }
}