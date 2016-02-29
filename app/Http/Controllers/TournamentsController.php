<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tournaments;
use View;
use DB;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;
use Carbon\Carbon;

class TournamentsController extends Controller
{

	/*===========================================
	=            Todays tournaments             =
	===========================================*/
	
	
	public function getIndex()
	{


		$data = Tournaments::orderBy('start','asc')->get();


         return View::make('tournaments.index')
         					->with('tournaments',$data);
	}

	/*===================================================
	=            Casino specific tournaments            =
	===================================================*/
	
	
	
	public function getCasinosTournamets($casino)
	{
		
		echo Carbon::parse('2016-02-12');
	}


	/*===========================================
	=            Get Vic Tournaments            =
	===========================================*/
	

	public function getVicScrape()
	{
		/*
			Get Vic tournaments 
		*/
			

			$html = HtmlDomParser::file_get_html( 'http://localhost/poker/vic.html' );

			$row = $html->find('table.tournament-table tr');

			foreach ($row as $r) {
				
	
				$date = strip_tags($r->children(0));
				$event = strip_tags($r->children(2));
				$start = strip_tags($r->children(5));

				//make sure only this months dates are shown
				if (strlen($date) > 3 && strlen($date) < 9 && $this->thisMonth($date)) {

					$game = array(
						'casino' => 'The Vic',
						'date' => $this->addMonth($date),
						'buyin' => strip_tags($r->children(1)),
						'event' => $event,
						'clock' => strip_tags($r->children(3)),
						'stack' => strip_tags($r->children(4)),
						'start' => date("H:i", strtotime($start))
						);

					$check = Tournaments::where('casino','=','The Vic')
												->where('event', '=', $event)
												->where('date', '=', $this->addMonth($date))->count();

					if ($check) {
						echo $event . 'Event has already been inserted <br>';
					}else{
						
						Tournaments::create($game);
						echo $event . 'Successfully added <br>';
					}
					
				}

			}
		
	}

		/*=============================================
		=            Get Aspers Tournaments           =
		=============================================*/
	
		public function getAspersScrape()
		{
		
			
		// Scrape vic website
		$html = HtmlDomParser::file_get_html( 'http://localhost/poker/aspers.html' );

		$row = $html->find('table#table-zebra tr');

		foreach ($row as $r) {


			// check if theres a day in the first row
			$ch = trim(strip_tags($r->children(0)));

			if (strlen($ch) <= 3) {
				
				$date = trim(strip_tags($r->children(0)));
				$date = $date . ' ' . date('j M');
				$date = Carbon::parse($date);
				$start = trim(strip_tags($r->children(1)));
				$event = trim(strip_tags($r->children(2)));
				$buy = trim(strip_tags($r->children(3)));
				$stack = trim(strip_tags($r->children(4)));
				$clock = $this->aspersClock($event);

			}else{
				// declare rows
				$start = strip_tags($r->children(0));
				$event = strip_tags($r->children(1));
				$buy = strip_tags($r->children(2));
				$stack = strip_tags($r->children(3));
				$date = Tournaments::all()->last()->date;
				$clock = $this->aspersClock($event);
			}

			$game = array(
						'casino' => 'Aspers',
						'date' => $date,
						'buyin' => $buy,
						'event' => trim($event),
						'clock' => $clock,
						'stack' => $stack,
						'start' => $start,
						);

			// Check if theres already that field
			$check = Tournaments::where('casino','=','Aspers')
										->where('event', '=', $event)
										->where('date', '=', $date)->count();

			if ($check) {
				echo $event . 'Event has already been inserted <br>';
			}else{
				
				Tournaments::create($game);
				echo $event . 'Successfully added <br>';
			}


		}


	}


	/***********************************
	add the month to the end of the date
	***********************************/
	private function addMonth($date)
	{

		$tdate = substr($date, 4, -2);
		$today = date('j');
		$add_month = strtotime( "+1 month", strtotime( date("y-m-j") ) );
		$month = date("M",$add_month);
		$date = substr($date, 0, -2);


		if ($tdate >= $today) {
			$r = $date . ' ' . date('M');

			return Carbon::parse($r);
		}else{
			$r = $date . ' ' . $month;

			return Carbon::parse($r);
		}
	}


	/***********************************
	check if its this month
	***********************************/
	private function thisMonth($date)
	{
		$tdate = substr($date, 4, -2);
		$today = date('j');

		if ($tdate >= $today) {
			return true;
		}


	}

	/***********************************
	Get clock times
	***********************************/
	private function aspersClock($event)
	{
		$daily = '6×20/25';
		$sat = '3x30/15';
		$super = '4x30/20';

		if ($event == 'Afternoon Annihilator' || $event == 'Multi Madness' || $event == 'Big Bounty ' || $event == 'Aspers Triple Chance') 
		{
			return $daily;

		}elseif ($event == 'Satellite' || $event == 'Stamp Card Satellite') 
		{
			return $sat;

		}elseif ($event == 'Friday Fifty Five' || $event == 'Super 60') 
		{
			return $super;

		}else
		{
			return '';
		}
	}	

    
}