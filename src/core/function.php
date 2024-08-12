<?php
namespace Eventer\Core;

enum networkStatus {
    case STATUS_UNKOWN;
    case STATUS_CONNECTING;
    case STATUS_CONNECTED;
    case STATUS_CLOSE;
}
