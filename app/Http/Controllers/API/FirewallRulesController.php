<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


use UniFi_API\Client as Unifi_Client;

class FirewallRulesController extends Controller
{
    protected $unifi_connection;

    public function __construct()
    {
        // flush the cache before we start
        Cache::flush();

        // Create a connection that can be reused
        $this->unifi_connection =
            new Unifi_Client(
                env("UNIFI_USER"),
                env("UNIFI_PASS"),
                env("UNIFI_URI"),
                env("UNIFI_SITE"),
                env("UNIFI_VERSION"),
                false
            );

        // set debug if needed
        //$this->unifi_connection->set_debug(true);

        // check if we have a cookie from a previous API call, if so, set it on the 
        // client and in the global PHP session
        if(Cache::has('unificookie'))
        {             
            Log::debug("Cookie from cache: " . Cache::get('unificookie'));
            Log::debug("Setting cookie in client from cache in constructor");

            $this->unifi_connection->set_cookies(Cache::get('unificookie'));

            // This is the all-important line, without this it will not 
            // use the cookie
            $_SESSION['unificookie'] = Cache::get('unificookie');
        }
        else
        {
            Log::debug("Cookie was not found in cache");
        }

        // prime the client by calling login
        if($this->unifi_connection->login())
        { // have a valid login
                        
            Log::debug("Valid login in controller constructor");
            Log::debug($this->unifi_connection->get_cookies());

            Log::debug("Store cookie in cache in constructor");
            Cache::put('unificookie', $this->unifi_connection->get_cookies(), 5);
        }
    }

    /**
    * Display a listing of firewall rules
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        return $this->unifi_connection->list_firewallrules();
    }
    
    /**
     * Update the specified firewall rule
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payload = array('enabled' => ($request->json()->get('enabled')));

        $method = 'PUT';

        $results = $this->
            unifi_connection->
                custom_api_request
                (
                    '/api/s/' . env("UNIFI_SITE") . '/rest/firewallrule/' . $id, 
                    $method, 
                    $payload
                );

        return $results;
    }

    /**
     * Get an individual firewall rule
     */
    private function getrule($id)
    {
        $method = "GET";

        $results = $this->unifi_connection->custom_api_request('/api/s/' . env("UNIFI_SITE") . '/rest/firewallrule/' . $id, $method);

        return $results;
    }

}
