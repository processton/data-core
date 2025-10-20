<?php

namespace App\Http\Controllers\Soap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SoapServer;

class SoapServerController extends Controller
{
    /**
     * Handle SOAP requests.
     */
    public function handle(Request $request)
    {
        $wsdl = public_path('datacore.wsdl');

        if (! file_exists($wsdl)) {
            return response('WSDL file not found', 404);
        }

        // Handle WSDL request
        if ($request->query('wsdl') !== null) {
            return response()->file($wsdl, [
                'Content-Type' => 'text/xml; charset=utf-8',
            ]);
        }

        // Create SOAP server
        $server = new SoapServer($wsdl, [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'soap_version' => SOAP_1_2,
        ]);

        // Set the service class
        $server->setClass(SoapService::class);

        // Handle the request
        ob_start();
        $server->handle();
        $response = ob_get_clean();

        return response($response, 200, [
            'Content-Type' => 'text/xml; charset=utf-8',
        ]);
    }
}
