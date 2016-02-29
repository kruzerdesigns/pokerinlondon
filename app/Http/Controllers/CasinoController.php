<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tournaments;
use App\Cash;
use View;
use DB;
use App\Http\Requests;
use Carbon\Carbon;
use Thujohn\Twitter\Facades\Twitter;
use Carbon\Carbon;

class CasinoController extends Controller
{

	/*===========================================
	=            Todays tournaments             =
	===========================================*/
	
	
	public function getIndex()
	{

		

	}

	public function getTweets()
	{
		$theVic = Twitter::getUserTimeline(['screen_name' => 'ThePokerRoomUK', 'count' => 10, 'format' => 'array']);
		$theVic = $theVic[0]{'text'};
		$theVic = $this->checkTweet($theVic);

		if ($theVic) {

			$reg = '/[A-Z]+\s([\d-\(\)\s]+)/';
			preg_match_all($reg ,$theVic, $matches);

			$this->updateCash($matches[0]);
		}
	}

	private function checkTweet($tweet)
	{
		if(stripos($tweet, 'cash game') === false){
			return false;
		}

		$regex = "((\#\w+)|(https?:\/\/.*))";
		$tweet = preg_replace($regex, ' ', $tweet);
		$tweet = str_replace('.', ' ', $tweet);

		return $tweet;
	}

	private function updateCash($games)
	{
		$nlhGames = preg_split("/[\s,]+/",rtrim($games[0]));

		$count = count($nlhGames);

		$nlh = trim($nlhGames[0]);

		if ( $nlh !== 'NLH') {
			return;
		}

		if ( $nlh == 'NLH') {

			Cash::where('update', '=', 1)->update(['update' => 0]);

			for ($i=1; $i < $count; $i++) { 

				$nlhs = explode("(" , rtrim($nlhGames[$i], ")"));

				 Cash::create(array(
                        'casino' => 'The Vic',
                        'tables' => $nlhs[1],
                        'game' => $nlh,
                        'stakes' => $nlhs[0],
                        'update' => 1

                ));

			}

			if (empty($games[1])) {
				return;
			}

			
		}

		$ploGames = preg_split("/[\s,]+/",rtrim($games[1]));

		$countp = count($ploGames);

		$plo = trim($ploGames[0]);

		if ( $plo == 'PLO') {


			for ($i=1; $i < $countp; $i++) { 

				$plos = explode("(" , rtrim($ploGames[$i], ")"));

				 Cash::create(array(
                        'casino' => 'The Vic',
                        'tables' => $plos[1],
                        'game' => $plo,
                        'stakes' => $plos[0],
                        'update' => 1

                ));

			}
		}		

		return;

	}
}	
