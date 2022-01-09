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
        //
        return response()->json($this -> listrules());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // not used
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // not used
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this -> getrule($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // not used
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payload = array('enabled' => ($request->json()->get('enabled')));
        return response()->json($this -> updaterule($id, $payload));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // not used
    }

    private function listrules()
    {
        /**
         * initialize the Unifi API connection class, log in to the controller and request the alarms collection
         * (this example assumes you have already assigned the correct values to the variables used)
         */
        $unifi_connection = new Unifi_Client(env("UNIFI_USER"), env("UNIFI_PASS"), env("UNIFI_URI"), env("UNIFI_SITE"), env("UNIFI_VERSION"), false);
        $login            = $unifi_connection->login();
        $results          = $unifi_connection->list_firewallrules(); // returns a PHP array containing alarm objects

        return $results;
    }

    private function getrule($id)
    {
        $method = "GET";

        $unifi_connection = new Unifi_Client(env("UNIFI_USER"), env("UNIFI_PASS"), env("UNIFI_URI"), env("UNIFI_SITE"), env("UNIFI_VERSION"), false);
        $login            = $unifi_connection->login();
        $results          = $unifi_connection->custom_api_request('/api/s/' . env("UNIFI_SITE") . '/rest/firewallrule/' . $id, $method);

        return $results;
    }

    private function updaterule($id, $rule)
    {
        $method = 'PUT';

        $unifi_connection = new Unifi_Client(env("UNIFI_USER"), env("UNIFI_PASS"), env("UNIFI_URI"), env("UNIFI_SITE"), env("UNIFI_VERSION"), false);
        $login            = $unifi_connection->login();
        $results          = $unifi_connection->custom_api_request('/api/s/' . env("UNIFI_SITE") . '/rest/firewallrule/' . $id, $method, $rule);

        return $results;

    }

}
