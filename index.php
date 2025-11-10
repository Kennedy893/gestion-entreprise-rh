<?php

/*
 * FlightPHP Framework
 * @copyright   Copyright (c) 2024, Mike Cao <mike@mikecao.com>, n0nag0n <n0nag0n@sky-9.com>
 * @license     MIT, http://flightphp.com/license
                                                                  .____   __ _
     __o__   _______ _ _  _                                     /     /
     \    ~\                                                  /      /
       \     '\                                         ..../      .'
        . ' ' . ~\                                      ' /       /
       .  _    .  ~ \  .+~\~ ~ ' ' " " ' ' ~ - - - - - -''_      /
      .  <#  .  - - -/' . ' \  __                          '~ - \
       .. -           ~-.._ / |__|  ( )  ( )  ( )  0  o    _ _    ~ .
     .-'                                               .- ~    '-.    -.
    <                      . ~ ' ' .             . - ~             ~ -.__~_. _ _
      ~- .       N121PP  .          . . . . ,- ~
            ' ~ - - - - =.   <#>    .         \.._
                        .     ~      ____ _ .. ..  .- .
                         .         '        ~ -.        ~ -.
                           ' . . '               ~ - .       ~-.
                                                       ~ - .      ~ .
                                                              ~ -...0..~. ____
   Cessna 402  (Wings)
   by Dick Williams, rjw1@tyrell.net
*/
$ds = DIRECTORY_SEPARATOR;
require(__DIR__ . $ds . 'app' . $ds . 'config' . $ds . 'bootstrap.php');


// <?php
// require '../vendor/autoload.php';
// require '../models/DolibarrModel.php';

// Flight::route('GET /status', function () {
//     $dolibarr = new DolibarrModel();
//     $result = $dolibarr->getStatus();

//     if (isset($result['error'])) {
//         Flight::render('table', ['error' => $result['error']]);
//     } else {
//         Flight::render('table', ['data' => $result['data']['success']]);
//     }
// });

// Flight::start();
