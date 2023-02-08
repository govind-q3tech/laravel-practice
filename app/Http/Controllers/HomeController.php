<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Storage;
use GuzzleHttp\Client;

class HomeController extends Controller
{

  /**
   * Show the home page data.
   *
   */
  public function home()
  {
    return view('welcome');
  }


  public function checkGuzzle() 
  {
    try {
      // $client = new Client();
      //   // $res = $client->request('GET', 'https://www.google.com/');
      // $client = new \GuzzleHttp\Client([
      //     'base_uri' => 'http://127.0.0.1:8001',
      //     'defaults' => [
      //       'exceptions' => false
      //     ]
      // ]);
      // // $response = $client->request('GET', 'http://127.0.0.1:8001/api/users/register');
      // $response = $client->request('POST', '/api/users/check', [
      //   'headers' => [
      //     'Accept'     => 'application/json',
      //   ],
      //   'name' => 'Govind',
      //   // 'allow_redirects' => false
      // ]);
      $targetUrl = 'http://127.0.0.1:8000/api/users/check/';
      // $targetUrl = 'https://www.google.com/';

      $client = new Client();
      $request = $client->get($targetUrl);
      $response = $request->getBody()->getContents();
      // print_r($response);


      // $ch = curl_init();
      // curl_setopt($ch, CURLOPT_URL,$targetUrl);
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      // // curl_setopt($ch, CURLOPT_POST,1);
      // // curl_setopt($ch,CURLOPT_POSTFIELDS,['name'=>'Govind']);
      // $response = curl_exec ($ch);
      // curl_close ($ch);
      dd($response);die;
    } catch(\Exception $e) {
      dd($e->getMessage());
    }
  }
}
