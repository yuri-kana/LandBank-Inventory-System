<!DOCTYPE html>
<html>
<head>
    <title>Test View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 40px;
        }
        .test-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        h1 {
            color: #006C2E;
            border-bottom: 3px solid #F7C300;
            padding-bottom: 10px;
        }
        .success {
            background: #e8f5e9;
            color: #006C2E;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="test-box">
        <h1>LANDBANK System Test</h1>
        <div class="success">
            <h2>✅ View Test Successful</h2>
            <p>{{ $message }}</p>
        </div>
        
        <div style="margin-top: 30px; text-align: left;">
            <h3>Test Links:</h3>
            <ul>
                <li><a href="/debug" target="_blank">Debug Route</a></li>
                <li><a href="/test-auth" target="_blank">Test Authentication</a></li>
                <li><a href="/dashboard-test" target="_blank">Dashboard Test (requires login)</a></li>
                <li><a href="/login" target="_blank">Login Page</a></li>
            </ul>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #fff8e1; border-radius: 5px;">
            <h4>Testing Steps:</h4>
            <ol style="text-align: left;">
                <li>Visit <a href="/debug">/debug</a> - Should show JSON</li>
                <li>Visit <a href="/test-auth">/test-auth</a> - Check if logged in</li>
                <li>Login at <a href="/login">/login</a></li>
                <li>Visit <a href="/dashboard">/dashboard</a> after login</li>
            </ol>
        </div>
    </div>
</body>
</html>