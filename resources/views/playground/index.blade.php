<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - API Playground</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.10.3/swagger-ui.css">
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            padding: 0;
        }
        .topbar {
            display: none;
        }
        .swagger-ui .info {
            margin: 20px 0;
        }
        .swagger-ui .info .title {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .dev-notice {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 20px;
            text-align: center;
            font-family: sans-serif;
        }
        .dev-notice strong {
            color: #b45309;
        }
    </style>
</head>
<body>
    <div class="dev-notice">
        <strong>🔒 Development Mode Only</strong>
        <p>This API playground is only available when the application is in development mode (APP_DEBUG=true).</p>
        <p>Generate a test token: <code>php artisan token:generate --role=admin</code></p>
    </div>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5.10.3/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.10.3/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Build the Swagger UI
            const ui = SwaggerUIBundle({
                url: "{{ url('/api/docs.openapi') }}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                persistAuthorization: true,
                tryItOutEnabled: true,
                requestInterceptor: function(request) {
                    // Add default headers
                    request.headers['Content-Type'] = 'application/json';
                    request.headers['Accept'] = 'application/json';
                    return request;
                }
            });

            window.ui = ui;
        };
    </script>
</body>
</html>
