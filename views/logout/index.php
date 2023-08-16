<?php

namespace Rpurinton\qi;

require_once(__DIR__ . "/../src/Session.php");
(new Session(true))->logout();
