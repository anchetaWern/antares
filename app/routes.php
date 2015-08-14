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


Route::get('/{tag?}', 'HomeController@index');

Route::get('/admin/makestatic', 'AdminController@makeStatic');

Route::get('/admin/{tag?}', 'AdminController@index');

Route::post('/admin/disablenewsitem', 'AdminController@disableNewsItem');



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

Route::get('/longreads-tech/update', 'NewsUpdaterController@longreadsTech');

Route::get('/javascriptlive/update', 'NewsUpdaterController@javascriptlive');

Route::get('/cancelbubble/update', 'NewsUpdaterController@cancelBubble');

Route::get('/reddit-programming/update', 'NewsUpdaterController@redditProgramming');

Route::get('/reddit-webdesign/update', 'NewsUpdaterController@redditWebDesign');

Route::get('/reddit-webdev/update', 'NewsUpdaterController@redditWebDev');

Route::get('/pocket/update', 'NewsUpdaterController@updatePocket');

Route::get('/uxdesignweekly/update', 'NewsUpdaterController@updateUXDesignWeekly');

Route::get('/uxweekly/update', 'NewsUpdaterController@updateUXWeekly');

Route::get('/json/update', 'NewsUpdaterController@updateJSON');