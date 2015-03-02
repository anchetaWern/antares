<?php
class NewsUpdaterController extends BaseController {

	public function hackernews(){

	    $client = new GuzzleHttp\Client();

	    $top_stories = array();

	    $date = date('Y-m-d');

	    $topstories_res = $client->get('https://hacker-news.firebaseio.com/v0/topstories.json');
	    $topstories_ids = $topstories_res->json();

	    foreach($topstories_ids as $id){
	        $item_res = $client->get("https://hacker-news.firebaseio.com/v0/item/" . $id . ".json");
	        $item_data = $item_res->json();

	        $text = html_entity_decode($item_data['title'], ENT_QUOTES);

	        $url = $item_data['url'];
	        $time = date('Y-m-d H:i:s', $item_data['time']);
	    
	        
	        $db_item = DB::table('news')
	            ->where('url', '=', $url)
	            ->first();

	        if(empty($db_item)){

	            DB::table('news')->insert(array(
	                'title' => $text,
	                'url' => $url,
	                'tags' => 'hn',
	                'timestamp' => $time,
	                'source' => 'hn'
	            ));
	        }else{
	            DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	        }
	        
	        //echo "<li>" . $text . " - " . $url . "</li>";

	    }

	    return 'success';

	}


	public function echojs(){

	    $client = new GuzzleHttp\Client();
	    $response = $client->get('http://www.echojs.com/rss');

	    $date = date('Y-m-d');

	    $data = json_decode(json_encode($response->xml()), true);
	    if(!empty($data['channel'])){
	        foreach($data['channel']['item'] as $item){

	            $time = date('Y-m-d H:i:s');

	            $text = html_entity_decode(trim($item['title']), ENT_QUOTES);
	            $url = $item['guid'];
	            
	            $db_item = DB::table('news')
	                ->where('url', '=', $url)
	                ->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => $text,
	                    'url' => $url,
	                    'tags' => 'javascript',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'echojs'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            

	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	    }

	    return 'success';


	}


	public function nextdraft(){

	    $html = new simple_html_dom();

	    $html->load_file('http://us2.campaign-archive1.com/home/?u=ed102783e87fee61c1a534a9d&id=dd6d48f649');

	    $excluded_urls = array(
	        'https://twitter.com/intent/tweet',
	        'http://www.facebook.com/sharer/sharer.php'
	    );

	    $latest_url = $html->find('a', 2)->href;
	   
	    if(!empty($latest_url)){

	        $html->load_file($latest_url);

	        $date = date('Y-m-d');

	        foreach($html->find('.newsletter_section a') as $link){

	            $url = $link->href;
	            $text = trim($link->plaintext);
	            if(!empty($url) && !empty($text) && !in_array($url, $excluded_urls)){
	                
	                $time = date('Y-m-d H:i:s');
	                

	                $db_item = DB::table('news')->where('url', '=', $url)->first();

	                if(empty($db_item)){
	                    DB::table('news')->insert(array(
	                        'title' => html_entity_decode($text, ENT_QUOTES),
	                        'url' => $url,
	                        'tags' => 'nondev',
	                        'timestamp' => $time,
	                        'score' => 0,
	                        'source' => 'nextdraft'
	                    ));
	                }else{
	                    DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	                }
	                
	                //echo "<li>" .  $text . " - " . $url . "</li>";


	            }
	        }
	    }

	    return 'success';

	}


	public function versioning(){
	    //every day
	    $html = new simple_html_dom();

	    $html->load_file('http://sitepointdotcom.createsend.com/t/y/p/fvw/0/1/0/1/0/');

	    $latest_url = $html->find('a', 0)->href;

	    if(!empty($latest_url)){
	        $excluded_urls = array(
	            'http://sitepoint-versioning.createsend1.com/t/y-l-idyhkjy-l-jl/',
	            'http://sitepoint-versioning.createsend1.com/t/y-l-idyhkjy-l-jr/',
	            'http://sitepoint-versioning.createsend1.com/t/y-l-idyhkjy-l-jj/',
	            'http://sitepoint-versioning.createsend1.com/t/y-l-idyhkjy-l-jj/',
	            'http://sitepointdotcom.createsend.com/functionalityDisabled.html',
	            'http://sitepoint-versioning.createsend1.com/t/y-tw-idyhkjy-l-jd/',
	            'http://sitepoint-versioning.forwardtomyfriend.com/y-l-2AD73FFF-idyhkjy-l-jh',
	            'http://sitepoint-versioning.createsend1.com/t/y-l-idyhkjy-l-jy/'
	        );

	        $excluded_text = array(
	            'Twitter',
	            'Facebook',
	            'Google+',
	            'the archive',
	            'Tweet',
	            'Forward'
	        );

	        $date = date('Y-m-d');

	        $html->load_file($latest_url);

	        foreach($html->find('a') as $link){
	            $text = trim($link->plaintext);
	            $url = $link->href;

	            if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	                
	                $time = date('Y-m-d H:i:s');

	                $db_item = DB::table('news')->where('url', '=', $url)->first();

	                if(empty($db_item)){
	                    DB::table('news')->insert(array(
	                        'title' => html_entity_decode($text, ENT_QUOTES),
	                        'url' => $url,
	                        'tags' => 'webdev',
	                        'timestamp' => $time,
	                        'score' => 0,
	                        'source' => 'versioning'
	                    ));
	                }else{
	                    DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	                }
	                
	                //echo "<li>" .  $text . " - " . $url . "</li>";


	            }
	        }
	    }


	    return 'success';

	}


	public function html5weekly(){

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://html5weekly.com/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/jobs',
	        'http://html5weekly.com/issues/*%7CUNSUB%7C*',
	        'http://html5weekly.com/issues/*%7CUPDATE_PROFILE%7C*',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'Web Operations Weekly: Our New Weekly for Web Engineers'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'html5',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'html5weekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";


	        }
	    }

	    return 'success';

	}


	public function javascriptweekly(){

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://javascriptweekly.com/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/jobs',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'Stop getting JavaScript Weekly',
	        'Web Operations Weekly: Our New Weekly for Web Engineers'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();
	                            
	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'javascript',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'jsweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";


	        }
	    }

	    return 'success';


	}


	public function rubyweekly(){

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://rubyweekly.com/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/jobs',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'click here',
	        'Web Operations Weekly: Our New Weekly for Web Engineers'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'ruby',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'rubyweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";




	        }
	    }

	    return 'success';

	}



	public function dbweekly(){

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://dbweekly.com/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/mediakit2014q2.pdf',
	        'https://cooperpress.com/jobs',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'Update your email address',
	        'unsubscribe here',
	        'Web Operations Weekly: Our New Weekly for Web Engineers'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'db',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'dbweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";


	        }
	    }

	    return 'success';

	}


	public function postgresweekly(){

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://postgresweekly.com/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/mediakit2014q2.pdf',
	        'https://cooperpress.com/jobs',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html',
	        'http://postgresweekly.com/issues'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'Update your email address',
	        'unsubscribe here',
	        'Stop getting Postgres Weekly',
	        'Change your email address',
	        'Craig Kerstiens',
	        'Read this on the Web',
	        'Postgres Weekly'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();
	                            
	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'db',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'postgresweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	    }

	    return 'success';

	}


	public function statuscode(){

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://statuscode.org/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/jobs',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'unsubscribe here',
	        'media kit.',
	        'Update your email address'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'programmer',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'statuscode'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	    }

	    return 'success';

	}


	public function nodeweekly(){

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://nodeweekly.com/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/jobs',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'unsubscribe here',
	        'media kit.',
	        'Update your email address'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'javascript',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'nodeweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	    }

	    return 'success';

	}


	public function phpweekly(){

	    $html = new simple_html_dom();

	    $date = date('Y-m-d');

	    $html->load_file('http://www.phpweekly.com/archive.html');

	    $latest_link = $html->find('a', 1)->href;

	    $excluded_urls = array(
	        'https://www.facebook.com/pages/PHPWeekly/388110517964770',
	        'https://twitter.com/PHPWeeklyNews'
	    );

	    $excluded_text = array(
	        'unsubscribe from this list',
	        'update subscription preferences',
	        'SourceGuardian',
	        'Sponsor this newsletter',
	        'Monitoring without alerts',
	        'katie@phpweekly.com',
	        'adrian.teasdale@gmail.com',
	        'Try JIRA for Free Today >'
	    );

	    $html->load_file('http://www.phpweekly.com' . $latest_link);

	    foreach($html->find('.contentBlock a') as $link){

	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();
	                                    
	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'php',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'phpweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";



	        }

	    }

	    return 'success';

	}


	public function rdweekly(){

	    $html = new simple_html_dom();

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'update subscription preferences',
	        'unsubscribe from this list',
	        'View in browser.'
	    );

	    $excluded_urls = array(
	        'http://responsivedesignweekly.com/advertise',
	        'http://responsivedesign.is/ask-me'
	    );


	    $html->load_file('http://us4.campaign-archive2.com/home/?u=559bc631fe5294fc66f5f7f89&id=df65b6d7c8');

	    $latest_link = $html->find('a', 2)->href;

	    $html->load_file($latest_link);


	    foreach($html->find('.templateContainer a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;


	        if(!empty($url) && !empty($text) && !in_array($text, $excluded_text) && !in_array($url, $excluded_urls)){
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();
	                                    
	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'css',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'rdweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }

	    }

	    return 'success';

	}


	public function nosqlweekly(){

	    $html = new simple_html_dom();

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'Unsubscribe',
	        'Forward to a friend',
	        'Update your profile',
	        'Add us to your address book'
	    );

	    $html->load_file('http://us2.campaign-archive1.com/home/?u=72f68dcee17c92724bc7822fb&id=2f0470315b');

	    $latest_link = $html->find('a', 1)->href;

	    $html->load_file($latest_link);


	    foreach($html->find('#contentTable a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;


	        if(!empty($url) && !empty($text) && !in_array($text, $excluded_text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'db',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'nosqlweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }

	    }

	    return 'success';
	}



	public function pythonweekly(){

	    $html = new simple_html_dom();

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'Unsubscribe',
	        'Forward to a friend',
	        'Update your profile',
	        'Add us to your address book'
	    );

	    $html->load_file('http://us2.campaign-archive2.com/home/?u=e2e180baf855ac797ef407fc7&id=9e26887fc5');

	    $latest_link = $html->find('a', 1)->href;

	    $html->load_file($latest_link);


	    foreach($html->find('#contentTable a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!empty($url) && !empty($text) && !in_array($text, $excluded_text)){
	        
	            
	            $time = date('Y-m-d H:i:s');
	            $db_item = DB::table('news')->where('url', '=', $url)->first();
	                                    

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'python',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'pythonweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }

	    }

	    return 'success';
	}


	public function webtoolsweekly(){

	    $html = new simple_html_dom();

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'Skip to the tools section below',
	        'update subscription preferences',
	        'unsubscribe from this list',
	        '@WebToolsWeekly',
	        'webtoolsweekly.com',
	        'details here',
	        'View this email in your browser',
	        'Monitoring without alerts',
	        'Sponsor this newsletter'
	    );

	    $html->load_file('http://us5.campaign-archive1.com/home/?u=ea228d7061e8bbfa8639666ad&id=104d6bcc2d');

	    $latest_link = $html->find('a', 3)->href;

	    $html->load_file($latest_link);

	    foreach($html->find('#templateContainer a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!empty($url) && !empty($text) && !in_array($text, $excluded_text)){
	            
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();
	                                             

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'tools',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'webtoolsweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }

	    }

	    return 'success';
	}


	public function wdrl(){

	    $html = new simple_html_dom();

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'Flattr',
	        'Christian Gloddy',
	        'Read the Letter online',
	        'gratipay',
	        'about the costs of the project here'
	    );

	    $excluded_urls = array(
	        'http://wdrl.info/costs/',
	        'https://wdrl.info/costs/',
	        'http://goo.gl/cnqtOc'
	    );

	    $html->load_file('http://wdrl.info/archive/');

	    $latest_link = $html->find('.archive__list a', 0)->href;

	    $html->load_file('http://wdrl.info/' . $latest_link);

	    foreach($html->find('.archive__issue a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!empty($url) && !empty($text) && !in_array($url, $excluded_urls) && !in_array($text, $excluded_text)){

	            
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();
	                                    
	                                    

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'webdev',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'wdrl'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";


	        }

	    }

	    return 'success';
	}


	public function wdweekly(){

	    $html = new simple_html_dom();

	    $date = date('Y-m-d');

	    $excluded_urls = array(
	        'https://twitter.com/share',
	        'http://plus.google.com/url'
	    );

	    $html->load_file('http://web-design-weekly.com/category/newsletter/');

	    $latest_link = $html->find('#main a', 0)->href;

	    $html->load_file($latest_link);

	    foreach($html->find('.entry-content a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!empty($url) && !empty($text) && !in_array($url, $excluded_urls)){

	        

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();       

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'webdev',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'wdweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }

	    }

	    return 'success';
	}


	public function mobilewebweekly(){
   		//every wednesday
	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://mobilewebweekly.co/latest');

	    $excluded_urls = array(
	        'http://twitter.com/peterc',
	        'https://cooperpress.com/',
	        'https://cooperpress.com/mediakit2014q4.pdf',
	        'https://cooperpress.com/jobs',
	        'https://cooperpress.com/spam.html',
	        'https://cooperpress.com/privacy.html',
	        'http://mobilewebweekly.co'
	    );

	    $excluded_text = array(
	        'Unsubscribe',
	        'Change email address',
	        'Read this e-mail on the Web',
	        'Read this issue on the Web',
	        'unsubscribe here',
	        'media kit.',
	        'Update your email address',
	        'stop receiving MWW here',
	        'Holly Schinsky',
	        'Brian Rinaldi',
	        'Read this on the Web',
	        'Mobile Web Weekly'
	    );

	    foreach($html->find('.issue-html a') as $link){
	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!in_array($url, $excluded_urls) && !in_array($text, $excluded_text) && !empty($url) && !empty($text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'webdev',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'mobilewebweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	           
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	    }

	    return 'success';

	}


	public function heydesigner(){
	    //every monday
	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://heydesigner.com/');

	    foreach($html->find('.articles article a[rel=nofollow]') as $link){

	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!empty($text) && !empty($url)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();       

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'webdev',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'heydesigner'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";


	        }

	        
	    }

	    return 'success';

	}


	public function cssweekly(){
	    //every monday
	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file('http://css-weekly.com/archives/');
	    
	    $parent = $html->find('article', 0);    

	    foreach($parent->find('ul a') as $link){

	        $text = trim($link->plaintext);
	        $url = $link->href;

	        if(!empty($text) && !empty($url)){

	           
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();       

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'css',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'cssweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	        
	    }
	  
	    return 'success';

	}


	public function gamedevjs(){
	    //every saturday
	    $base_url = 'http://us3.campaign-archive1.com/home/?u=4ad274b490aa6da8c2d29b775&id=bacab0c8ca';

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'contact@gamedevjsweekly.com',
	        'andrzej.mazur@end3r.com'
	    );

	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('.display_archive a', 0)->href;

	    $html->load_file($latest_url);

	    foreach($html->find('.bodyContent a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     

	        if(!empty($text) && !empty($url) && !in_array($text, $excluded_text)){
	           
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'gamedev',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'gamedevjsweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }            
	           
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	    }

	    return 'success';
	}


	public function emberweekly(){
	    //every monday
	    $base_url = 'http://us4.campaign-archive1.com/home/?u=ac25c8565ec37f9299ac75ca0&id=e96229d21d';

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('#archive-list a', 0)->href;

	    $html->load_file($latest_url);

	    foreach($html->find('.templateContainer a.headline-link') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     

	       
	        if(!empty($text) && !empty($url)){

	           
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'javascript',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'emberweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }     
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	        }
	       
	    }

	    return 'success';
	}


	public function wpmailme(){
	    //every friday
	    $base_url = 'http://us2.campaign-archive1.com/home/?u=51dd0bd2831ba102ab61a6401&id=76fc580ee3';

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('#archive-list a', 0)->href;

	    $html->load_file($latest_url);

	    foreach($html->find('.bodyContent a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     

	       
	        if(!empty($text) && !empty($url)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'wordpress',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'wpmail.me'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }  
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	        }
	       
	    }

	    return 'success';
	}


	public function beyonddesktop(){
	    //every monday
	    $base_url = 'http://us1.campaign-archive2.com/home/?u=304ef1419c812109265422add&id=e5791e58de';

	    $date = date('Y-m-d');

	    $excluded_urls = array(
	        'https://twitter.com/bdconf',
	        'http://bdconf.com',
	        'mailto:info@unmatchedstyle.com',
	        'http://lanyrd.com/series/bdconf/',
	        'https://plus.google.com/+Bdconf/posts',
	        'https://www.facebook.com/breakingdc',
	        'http://bdconf.com/videos/',
	        'http://bdconf.com/articles/',
	        'https://bdconf.com'
	    );

	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('#archive-list a', 0)->href;

	    $html->load_file($latest_url);

	    foreach($html->find('#templateBody a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     

	       
	        if(!empty($text) && !empty($url) && !in_array($url, $excluded_urls)){
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'webdev',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'beyonddesktop'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            }            
	          
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	       
	    }

	    return 'success';
	}


	public function pycoders(){
	    //every saturday
	    $base_url = 'http://us4.campaign-archive2.com/home/?u=9735795484d2e4c204da82a29&id=64134e0a27';

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'View this email in your browser',
	        'article',
	        'Gratipay',
	        'twitter',
	        'Level Up Your Dev Skills',
	        'Sponsor this newsletter',
	        '@mgrouchy',
	        'Join today!',
	        'Hired'
	    );

	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('#archive-list a', 0)->href;

	    $html->load_file($latest_url);

	    foreach($html->find('.templateContainer a[target=_blank]') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     

	       
	        if(!empty($text) && !empty($url) && !in_array($text, $excluded_text)){
	            
	            $time = date('Y-m-d H:i:s');

	         
	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'python',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'pycoders'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";	           
	        }
	       
	    }

	    return 'success';
	}


	public function perlweekly(){
	    //every monday
	    $base_url = 'http://perlweekly.com/archive/';

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'SZABGAB',
	        'Gabor Szabo'
	    );

	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('table a', -1)->href;

	    $html->load_file('http://perlweekly.com' . $latest_url);

	    foreach($html->find('table tbody tr td table td div p > a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     
	        $style = str_replace(' ', '', $link->style);

	       
	        if(!empty($text) && !empty($url) && !in_array($text, $excluded_text)){

	            
	            $time = date('Y-m-d H:i:s');
	            if($style == 'font-size:18px;font-weight:bold;'){
	                
	                $db_item = DB::table('news')->where('url', '=', $url)->first();

	                if(empty($db_item)){
	                    DB::table('news')->insert(array(
	                        'title' => html_entity_decode($text, ENT_QUOTES),
	                        'url' => $url,
	                        'tags' => 'perl',
	                        'timestamp' => $time,
	                        'score' => 0,
	                        'source' => 'perlweekly'
	                    ));
	                }else{
	                    DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	                } 
	                
	                //echo "<li>" .  $text . " - " . $url . "</li>";
	            }

	        }
	       
	    }

	    return 'success';
	}


	public function devopsweekly(){
	    //every sunday
	    $base_url = 'http://www.devopsweekly.com/archive';

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'Home',
	        'Archive',
	        'Mailchimp'
	    );

	    $excluded_urls = array(
	        'http://brightbox.com/devopsweekly'
	    );

	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('#archive a', 0)->href;

	    $html->load_file('http://www.devopsweekly.com' . $latest_url);

	    foreach($html->find('#main a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     

	       
	        if(!empty($text) && !empty($url) && !in_array($text, $excluded_text) && !in_array($url, $excluded_urls)){

	            $time = date('Y-m-d H:i:s');

	           
	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'devops',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'devopsweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	       
	    }

	    return 'success';
	}


	public function golangweekly(){
	    //every tuesday
	    $base_url = 'http://www.golangweekly.com/archive/';

	    $date = date('Y-m-d');

	    $excluded_text = array(
	        'mattcottingham',
	        'Matt Cottingham',
	        'Kelsey Hightower',
	        'Share on Twitter',
	        'follow @golangweekly on twitter',
	        'Change your email address',
	        'Stop getting Go Newsletter',
	        'Cooper Press',
	        'See our archives',
	        'Read this issue on the Web',
	        'Peter Cooper'
	    );


	    $html = new simple_html_dom();
	    $html->load_file($base_url);

	    $latest_url = $html->find('li a', 0)->href;
	   

	    $html->load_file('http://www.golangweekly.com/' . $latest_url);

	    foreach($html->find('.container a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     

	       
	        if(!empty($text) && !empty($url) && !in_array($text, $excluded_text)){

	            $time = date('Y-m-d H:i:s');

	            
	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'go',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'golangweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";

	        }
	       
	    }

	    return 'success';
	}


	public function iosdevweekly(){
	    //every friday
	    $base_url = 'http://iosdevweekly.com/';

	    $date = date('Y-m-d');

	    $html = new simple_html_dom();

	    $html->load_file($base_url);

	    foreach($html->find('.content h2 a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     
	       
	        if(!empty($text) && !empty($url)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'ios',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'iosdevweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>"; 
	        }
	       
	    }

	    return 'success';
	}


	public function sidebario(){
	    //every saturday
	    
	    $base_url = 'http://feeds.sidebar.io/SidebarFeed';

	    $html = file_get_contents($base_url);
	    $xml = simplexml_load_string($html, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
	    $data = json_decode(json_encode($xml), true);


	   
	    foreach($data['channel']['item'] as $item){
	        
	        $text = $item['title'];
	        $url = $item['link'];
	        $datetime = date('Y-m-d H:i:s', strtotime($item['pubDate']));
	       
	     
	        if(!empty($text) && !empty($url)){

	           
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'design',
	                    'timestamp' => $datetime,
	                    'score' => 0,
	                    'source' => 'sidebario'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	           
	        }

	       
	    }

	    return 'success';
	}


	public function androidweekly(){
	    //every sunday
	    
	    $base_url = 'http://androidweekly.net/';

	    $excluded_text = array(
	        'Tweet'
	    );

	    $html = new simple_html_dom();

	    $html->load_file($base_url);
	 
	    foreach($html->find('.latest-issue a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     
	       
	        if(!empty($text) && !empty($url) && !in_array($text, $excluded_text)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'android',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'androidweekly'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	       
	        }
	       
	    }

	    return 'success';
	}


	public function medium(){
	    //everyday
	    
	    $base_url = 'https://medium.com/top-stories';

	    $html = new simple_html_dom();

	    $html->load_file($base_url);
	 
	    foreach($html->find('h3.block-title a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = 'https://medium.com' . $link->href;     
	       
	        if(!empty($text) && !empty($url)){

	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'medium',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'medium'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	            
	        }
	       
	    }

	    return 'success';
	}


	public function readability(){
	    //everyday
	    
	    $base_url = 'https://readability.com/topreads/';

	    $html = new simple_html_dom();

	    $html->load_file($base_url);


	    foreach($html->find('h1.entry-title a') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = $link->href;     
	       
	        if(!empty($text) && !empty($url)){
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'readability',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'readability'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	           
	        }
	       
	    }

	    return 'success';
	}


	public function slashdot(){
	    //everyday
	    
	    $base_url = 'http://slashdot.org/popular';

	    $html = new simple_html_dom();

	    $html->load_file($base_url);

	    $excluded_text = array(
	        '-&gt;',
	        'Journal:'
	    );


	    foreach($html->find('span[id^=title] a') as $link){
	        
	        $url = str_replace('//slashdot.org', 'http://slashdot.org', $link->href);
	        $text = trim($link->plaintext);
	        
	        if(!empty($url) && !empty($text) && !in_array($text, $excluded_text)){
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'slashdot',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'slashdot'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	        }
	  
	    }

	    return 'success';
	}


	public function producthunt(){
	    //everyday
	    
	    $base_url = 'http://www.producthunt.com/';

	    $html = new simple_html_dom();

	    $html->load_file($base_url);


	    foreach($html->find('.post-url') as $link){
	        
	        $text = trim($link->plaintext);
	        $url = 'http://www.producthunt.com' . $link->href; 
	        
	        if(!empty($url) && !empty($text)){
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'producthunt',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'producthunt'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	            
	        }
	        
	  
	    }

	    return 'success';
	}


	public function designernews(){
	    //everyday
	    
	    $base_url = 'https://news.layervault.com/';

	    $html = new simple_html_dom();

	    $html->load_file($base_url);


	    foreach($html->find('.StoryUrl') as $link){
	        
	        $text = trim($link->story_title);
	        $url = $link->href; 
	        
	        if(!empty($url) && !empty($text)){
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'dn',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'designernews'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	        }
	        
	  
	    }

	    return 'success';
	}


	public function github(){
	    //everyday
	    
	    $base_url = 'https://github.com/trending';

	    $html = new simple_html_dom();

	    $html->load_file($base_url);


	    foreach($html->find('.repo-list-name a') as $link){
	        
	        $url = $link->href; 
	        $exploded_url = explode('/', $url);
	        $text = $exploded_url[2];
	        $url = 'https://github.com' . $url;

	       
	        if(!empty($url) && !empty($text)){
	            
	            $time = date('Y-m-d H:i:s');

	            $db_item = DB::table('news')->where('url', '=', $url)->first();

	            if(empty($db_item)){
	                DB::table('news')->insert(array(
	                    'title' => html_entity_decode($text, ENT_QUOTES),
	                    'url' => $url,
	                    'tags' => 'github',
	                    'timestamp' => $time,
	                    'score' => 0,
	                    'source' => 'github'
	                ));
	            }else{
	                DB::table('news')->where('id', $db_item->id)->update(array('timestamp' => $time));
	            } 
	            
	            //echo "<li>" .  $text . " - " . $url . "</li>";
	        }
	       
	    }

	    return 'success';
	}

}