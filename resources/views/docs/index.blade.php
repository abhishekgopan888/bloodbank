<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank API Docs</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.17.2/swagger-ui.css" />
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        #auth-status { padding: 12px 16px; background: #f6f8fa; border-bottom: 1px solid #d0d7de; font-size: 14px; }
    </style>
</head>
<body>
<div id="auth-status">Signing in automatically…</div>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@5.17.2/swagger-ui-bundle.js"></script>
<script>
    const authStatus = document.getElementById('auth-status');
    const defaultCredentials = {
        email: 'admin@example.com',
        password: 'password'
    };

    const setStatus = (message) => {
        if (authStatus) {
            authStatus.textContent = message;
        }
    };

    const initSwagger = (token) => {
        const ui = SwaggerUIBundle({
            url: '{{ $specUrl }}',
            dom_id: '#swagger-ui',
            deepLinking: true,
            persistAuthorization: true,
            presets: [SwaggerUIBundle.presets.apis],
            requestInterceptor: (request) => {
                if (token && !request.headers.Authorization) {
                    request.headers.Authorization = `Bearer ${token}`;
                }
                return request;
            },
        });

        if (token) {
            ui.preauthorizeApiKey('sanctum', `Bearer ${token}`);
        }
    };

    window.onload = async () => {
        const storedToken = localStorage.getItem('bloodbank_swagger_token');

        if (storedToken) {
            setStatus('Auth ready.');
            initSwagger(storedToken);
            return;
        }

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(defaultCredentials)
            });

            const data = await response.json();

            if (data && data.token) {
                localStorage.setItem('bloodbank_swagger_token', data.token);
                setStatus('Auto-login successful. Protected endpoints are ready.');
                initSwagger(data.token);
            } else {
                setStatus('Auto-login failed. You can still view public endpoints.');
                initSwagger(null);
            }
        } catch (error) {
            setStatus('Auto-login failed. You can still view public endpoints.');
            initSwagger(null);
        }
    };
</script>
</body>
</html>
