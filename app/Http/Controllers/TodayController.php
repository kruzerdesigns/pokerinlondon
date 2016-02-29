<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Cash;
use App\Tournaments;
use View;
use DB;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;
use Carbon\Carbon;


class TodayController extends Controller
{
     


    public function getIndex()
    {

        // Todays date
        $date = Carbon::today();

        $cash = Cash::where('update', '=',1)->get();

        $tournaments = Tournaments::where('date','=',$date)
                                ->orderBy('start','asc')
                                ->get();

        return View::make('today')
                    ->with('tournaments',$tournaments)
                    ->with('games',$cash);
    }

    

    public function getScrape()
    {
        Cash::where('update', '=', 1)->update(['update' => 0]);

        // tart scrape
        $html = HtmlDomParser::file_get_html( 'http://localhost/poker/scrape-test.html' );

        // find table row
        $row = $html->find('table tr');

        // looping through all tags
        foreach ($row as $r) {

            // strip html tags and white space
            $casino = trim(strip_tags($r->children(0)));
            $info = trim(strip_tags($r->children(1)));



            if ($casino != 'Venue') {


                // Split the information column to get info seperated 
                $explode = explode(' ', $info);
                $tables = $explode[0];
                $stakes = $explode[2];
                $game = $explode[3];

                if ($casino == 'GrosvenorVictoria') {
                    
                    $casino = 'The Vic';
                }

                // Add fields into db
                Cash::create(array(
                        'casino' => $casino,
                        'tables' => $tables,
                        'game' => $game,
                        'stakes' => $stakes,
                        'update' => 1

                ));
               
            }

        }

    }


}



