<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use TwitterAPIExchange;

/**
 * Twitter component
 */
class TwitterComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public $settingsTwitter = [
        'oauth_access_token' => '3517023912-KGWVbjoOBsz25wYk5fnLe59dtlsmVV1pOpcmrwI',
        'oauth_access_token_secret' => 'Pm1YHAkmmtYoxGcaJDSysvP4pX2yMM3SumqUmXrGCIrJQ',
        'consumer_key' => 'Wt34i2M5mY3Kf3KnlVGciFJ86',
        'consumer_secret' => 'KSQROhO6H7rw6niibW95z8mSEw7TaKNs2HzWKtvsClSTFh6oGH'
    ];

    /**
     * getTweets: searches twitter for tweets
     * @param array $parameters
     * @return array
     * @example: $this->Twitter->getTweets([ ['text'=>'san francisco'],['text'=>'san jose'] ]);
     */
    public function getTweets($parameters)
    {

        $Twitter = new TwitterAPIExchange($this->settingsTwitter);

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name=aansubarkah&count=2';
        $requestMethod = 'GET';

        $response = $Twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return json_decode($response);
    }

    /*
     * @todo add tweet_id on markers column
     * @todo add tweet_photo_url on markers column
     * @todo add virtual column is_place_exists, is_tweet_exists, is_tweet_photo_url_exists on markerviews view
     * */

    public function getMention(){
        $Twitter = new TwitterAPIExchange($this->settingsTwitter);

        $url = 'https://api.twitter.com/1.1/statuses/mentions_timeline.json';
        $getfield = '?count=2';
        $requestMethod = 'GET';
        //$twitter = new TwitterAPIExchange($settings);
        $response = $Twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return json_decode($response);
    }

    public function getSearch($screen_name){
        $Twitter = new TwitterAPIExchange($this->settingsTwitter);

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield = '?q=%3A' . $screen_name;
        $requestMethod = 'GET';
        //$twitter = new TwitterAPIExchange($settings);
        $response = $Twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return json_decode($response);
    }
}
