<?php
/*
    Copyright 2011 Emerginov Team <admin@emerginov.org>
    
    This file is part of Emerginov Scripts.

    This script is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This script is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this script.  If not, see <http://www.gnu.org/licenses/>.

*/
/*
 * Parse the datetime string from DB to retrieve only 
 * inputs: 
 *      $date is the datetime to parse
 *      $what is the expecting value to extract
 * return
 *      the extracted value or false if $what not found
 */
function parseDatetime($date,$what){ 
    switch ($what)
    {
    case "year":
        return substr($date,0,4); // année 
        break;
    case "month":
        return substr($date,5,2);  // mois 
        break;
    case "day":
        return substr($date,8,2);        // jour 
        break;
    case "hour":
        return substr($date,11,2);     // heures 
        break;
    case "minute":
        return substr($date,14,2);     // minutes
        break;
    default:
        return false;
    }
}

/*
 * Compare the datetime from MySQL to current time
 * in order to know if it is still time to bet.
 * This function take the global constant _TIME_BEFORE_BET
 * into account.
 * inputs: 
 *      $date is the datetime to check with
 * return
 *      true or false
 */
function checkIfBetStillPossible($date)
{
    // Compute the last time for bets for this match
    $dateMatchForBet = strtotime($date) - _TIME_BEFORE_BET;
    
    // What time is it please?
    $now = time();
    
    // Is it still possible to do a bet?
    if ($now > $dateMatchForBet){
        return false;
    }
    else{
        return true;
    }
}

/*
 * Determine how many minutes are between server and client
 * Inputs:
 *      $clientTime and $serverTime are in seconds since Unix Epoch
 * Return:
 *      a number of secondes rounded to the 15 minutes because
 *      timezones in world are separate by 15 minutes minimum
 */
function getTimeDiffWithServer($clientTime, $serverTime = 0){
    if ($serverTime == 0) $serverTime = time();
    
    // Determine how many 900 sec (15 minutes) we can found in this diff
    return round(($clientTime - $serverTime) / 900) * 900;
}

?>
