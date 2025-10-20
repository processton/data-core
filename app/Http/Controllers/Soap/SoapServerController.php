<?php

namespace App\Http\Controllers\Soap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        // Defensive: ensure the PHP SOAP extension (SoapServer) is available
        if (! class_exists('SoapServer')) {
            $message = "PHP SOAP extension (SoapServer) is not available.\n".
                "Please enable or install ext-soap. On Ubuntu: 'sudo apt install php-soap' (or 'php8.2-soap' for specific versions), then restart your webserver/PHP-FPM.\n".
                "After enabling, verify with: php -r \"echo class_exists('SoapServer') ? 'yes' : 'no';\"\n".
                "If you're using the built-in 'php artisan serve', restart the command after enabling the extension.";

            // Provide an HTML response when accessed via browser, otherwise plain text.
            $body = Str::contains($request->header('Accept', ''), 'text/html')
                ? nl2br(e($message))
                : $message;

            return response($body, 500, [
                'Content-Type' => Str::contains($request->header('Accept', ''), 'text/html') ? 'text/html; charset=utf-8' : 'text/plain; charset=utf-8',
            ]);
        }

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
