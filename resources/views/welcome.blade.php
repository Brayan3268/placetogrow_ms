<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8fafc;
        }
        .welcome-container {
            text-align: center;
        }
        .welcome-image {
            max-width: 100%;
            height: auto;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 40px;
        }
        .login-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff7c06;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .login-button:hover {
            background-color: #702bd8;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome to PlaceToGrow Microsites manager</h1>
        <img src="{{ asset('images/welcome.jpg') }}" alt="Welcome Image" class="welcome-image">
        <br>
        <a href="{{ route('login') }}" class="login-button">Login</a>
    </div>
</body>
</html>