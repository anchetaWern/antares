<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

App::before(function($request)
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
    header('Access-Control-Allow-Credentials: true');
});

Route::pattern('tag', '[a-z0-9-]+');

Route::match(array('GET', 'POST'), '/news/{tags}', function($tags){

    $offset = 0;
    $limit = 10;
    if(Input::has('page')){
        $page = Input::get('page');
        $offset = ($page - 1) * $limit;
    }


    $date = date('Y-m-d');
    $date_lasttwoweeks = date('Y-m-d', strtotime($date . ' - 2 week'));

    $top_stories = DB::table('news')
        ->where('tags', '=', $tags)
        ->whereRaw("DATE(timestamp) BETWEEN '$date_lasttwoweeks' AND '$date'")
        ->select('title', 'url', 'timestamp', 'tags')
        ->orderBy('timestamp', 'DESC')
        ->take($limit)
        ->skip($offset)
        ->get();

    if(empty($top_stories)){
        $date_lastfourweeks = date('Y-m-d', strtotime($date . ' - 4 week'));

        $top_stories = DB::table('news')
            ->where('tags', '=', $tags)
            ->whereRaw("DATE(timestamp) BETWEEN '$date_lastfourweeks' AND '$date'")
            ->select('title', 'url', 'timestamp', 'tags')
            ->orderBy('timestamp', 'DESC')
            ->take($limit)
            ->skip($offset)
            ->get();

    }

    return $top_stories;
});

Route::get('/{tag?}', 'HomeController@index');

Route::get('/hn/update', 'NewsUpdaterController@hackernews');

Route::get('/echojs/update', 'NewsUpdaterController@echojs');

Route::get('/nextdraft/update', 'NewsUpdaterController@nextdraft');

Route::get('/versioning/update', 'NewsUpdaterController@versioning');

Route::get('/html5weekly/update', 'NewsUpdaterController@html5weekly');

Route::get('/jsweekly/update', 'NewsUpdaterController@javascriptweekly');

Route::get('/rubyweekly/update', 'NewsUpdaterController@rubyweekly');

Route::get('/dbweekly/update', 'NewsUpdaterController@dbweekly');

Route::get('/postgresweekly/update', 'NewsUpdaterController@postgresweekly');

Route::get('/statuscode/update', 'NewsUpdaterController@statuscode');

Route::get('/nodeweekly/update', 'NewsUpdaterController@nodeweekly');

Route::get('/phpweekly/update', 'NewsUpdaterController@phpweekly');

Route::get('/rdweekly/update', 'NewsUpdaterController@rdweekly');

Route::get('/nosqlweekly/update', 'NewsUpdaterController@nosqlweekly');

Route::get('/pythonweekly/update', 'NewsUpdaterController@pythonweekly');

Route::get('/webtoolsweekly/update', 'NewsUpdaterController@webtoolsweekly');

Route::get('/wdrl/update', 'NewsUpdaterController@wdrl');

Route::get('/wdweekly/update', 'NewsUpdaterController@wdweekly');

Route::get('/mobilewebweekly/update', 'NewsUpdaterController@mobilewebweekly');

Route::get('/heydesigner/update', 'NewsUpdaterController@heydesigner');

Route::get('/cssweekly/update', 'NewsUpdaterController@cssweekly');

Route::get('/gamedevjs/update', 'NewsUpdaterController@gamedevjs');

Route::get('/emberweekly/update', 'NewsUpdaterController@emberweekly');

Route::get('/wpmailme/update', 'NewsUpdaterController@wpmailme');

Route::get('/beyonddesktop/update', 'NewsUpdaterController@beyonddesktop');

Route::get('/pycoders/update', 'NewsUpdaterController@pycoders');

Route::get('/perlweekly/update', 'NewsUpdaterController@perlweekly');

Route::get('/devopsweekly/update', 'NewsUpdaterController@devopsweekly');

Route::get('/golangweekly/update', 'NewsUpdaterController@golangweekly');

Route::get('/iosdevweekly/update', 'NewsUpdaterController@iosdevweekly'); 

Route::get('/sidebario/update', 'NewsUpdaterController@sidebario'); 

Route::get('/androidweekly/update', 'NewsUpdaterController@androidweekly');

Route::get('/medium/update', 'NewsUpdaterController@medium');

Route::get('/readability/update', 'NewsUpdaterController@readability'); 

Route::get('/slashdot/update', 'NewsUpdaterController@slashdot');

Route::get('/producthunt/update', 'NewsUpdaterController@producthunt');

Route::get('/designernews/update', 'NewsUpdaterController@designernews');

Route::get('/github/update', 'NewsUpdaterController@github');

Route::get('/webops/update', 'NewsUpdaterController@weboperationsweekly');

Route::get('/webperformancenews/update', 'NewsUpdaterController@webperformancenews');

Route::get('/reset/{pass}', function($pass){
    //runs once a month
    if($pass == 'nitoryolai225(@'){    
        DB::table('news')->truncate();
        return 'ok';
    }
    return 'failed';
});