<?php
namespace Eventer\Core;

enum networkStatus {
    case STATUS_UNKOWN;
    case STATUS_CONNECTING;
    case STATUS_CONNECTED;
    case STATUS_CLOSE;
}

enum promiseStatus {
    case PROMISE_WAITER;
    case PROMISE_ACCEPT;
    case PROMISE_REJECT;
}
