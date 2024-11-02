<?php
namespace Eventer\Libs\Base;

use Eventer\Core\promiseStatus;

class Promise {

    protected $state = promiseStatus::PROMISE_WAITER;
    protected $onAccepts = [];
    protected $onRejects = [];
    protected $value;

    public function __construct(callable $caller){
        $resolve = function($value){
            return $this -> resolve($value);
        };
        $reject = function($value){
            return $this -> reject($value);
        };
        $caller($resolve, $reject);
    }

    public function resolve($value){
        if($this -> state !== promiseStatus::PROMISE_WAITER) return false;
        $this -> state = promiseStatus::PROMISE_ACCEPT;
        $this -> value = $value;
        foreach($this -> onAccepts as $accept){
            $accept($value);
        }
    }

    public function reject($value){
        if($this -> state !== promiseStatus::PROMISE_WAITER) return false;
        $this -> state = promiseStatus::PROMISE_REJECT;
        $this -> value = $value;
        foreach($this -> onRejects as $reject){
            $reject($value);
        }
    }

    public function then(callable $resolve, callable $reject = NULL){
        if($this -> state === promiseStatus::PROMISE_ACCEPT){
            $this -> value = $resolve($this -> value);
            return $this;
        }
        if($this -> state === promiseStatus::PROMISE_REJECT && is_callable($reject)){
            $this -> value = $reject($this -> value);
            return $this;
        }
        $this -> onAccepts[] = $resolve;
        if(is_callable($reject)) $this -> onRejects[] = $reject;
        return $this;
    }

}
