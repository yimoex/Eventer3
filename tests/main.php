<?php
include 'core/autoload.php';

use Eventer\Core\Loader;
use Eventer\Core\Eventer;

Loader::run();

$ev = new Eventer();

$ev -> run();
