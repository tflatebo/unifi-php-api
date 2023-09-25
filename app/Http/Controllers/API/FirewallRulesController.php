<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use UniFi_API\Client as Unifi_Client;

class FirewallRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // login, if this returns false, we 
        // hit the rate limit and should return an error
        $connection = $this->login();

        if ($connection) {
            $result = $connection->list_firewallrules();

            if ($result) { // only return response if not empty, second API call could have had an error
                return response()->json($result);
            }
        }

        // if we got here, that means we didn't return a useful result, so send an error
        return response()->json(['error' => 500, 'message' => 'Unifi API client returned an error (see https://github.com/Art-of-WiFi/UniFi-API-client/blob/master/src/Client.php#L205'], 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rule_id)
    {
        $payload = array('enabled' => ($request->json()->get('enabled')));

        // login, if this returns false, we 
        // hit the rate limit and should return an error
        $connection = $this->login();

        if ($connection) { // if the login was successful, proceed
            $method = 'PUT';

            $result = $connection->custom_api_request('/api/s/' . env("UNIFI_SITE") . '/rest/firewallrule/' . $rule_id, $method, $payload);

            if ($result) { // only return response if not empty, second API call could have had an error
                return response()->json($result);
            }
        }

        // if we got here, that means we didn't return a useful result, so send an error
        return response()->json(['error' => 500, 'message' => 'Unifi API client returned an error (see https://github.com/Art-of-WiFi/UniFi-API-client/blob/master/src/Client.php#L205'], 500);
    }

    /**
     * initialize the Unifi API connection class, log in to the controller and request the alarms collection
     * (this example assumes you have already assigned the correct values to the variables used)
     * 
     * Return: unifi_client if the loging worked, null if there was an error
     * 
     */
    private function login()
    {
        $unifi_connection = new Unifi_Client(env("UNIFI_USER"), env("UNIFI_PASS"), env("UNIFI_URI"), env("UNIFI_SITE"), env("UNIFI_VERSION"), false);
        $login            = $unifi_connection->login();

        if ($login) { // login was successful, return the connection to be used for the next request
            return $unifi_connection;
        } else { // login was unsuccessful, return the problem
            return $login;
        }
    }
}
