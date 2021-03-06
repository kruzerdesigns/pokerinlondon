@extends('layouts/main')

{{-- Page title --}}
@section('title')
Poker Today
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Start -->
    <div class="container">
        <!--Content Section Start -->

        <div class="row">

            <div class="col-lg-2">
                
            </div>
        
            <div class="col-lg-8">
                <h2 class="title">
                    This Weeks Tournaments
                </h2>

                @if($tournaments)
                

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Casino</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>Event</th>
                                <th>Buy In</th>
                                <th>Starting Stack</th>
                                <th>Clock</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tournaments as $tournament)

                                <tr>
                                    <td>{{ $tournament->casino }}</td>
                                    <td><?=date('D j M',strtotime($tournament->date))?></td>
                                    <td>{{ $tournament->start }}</td>
                                    <td>{{ $tournament->event }}</td>
                                    <td>{{ $tournament->buyin }}</td>
                                    <td>{{ $tournament->stack }}</td>
                                    <td>{{ $tournament->clock }}</td>
                                </tr>
                        
                            @endforeach
                        </tbody>
                            
                    </table>
                    <!-- End tournaments table -->

                @endif   
            </div>  
        </div>
        <!-- End Row -->

    </div>
    <!-- //Content Section End -->

    
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
