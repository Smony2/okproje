<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yetkisiz Giriş!</title>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 100px;
        }
        h1 {
            font-size: 80px;
            color: #ff4d4d;
        }
        p {
            font-size: 24px;
            color: #555;
            margin-top: 20px;
        }
        a {
            margin-top: 30px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        a:hover {
            background-color: #e60000;
        }
    </style>
</head>
<body>
<h1>403 🚫</h1>
<p>La nereye gidiyon amk, yetkin yok buraya 😂</p>

<a href="{{ route('admin.dashboard') }}">Ana Sayfaya Dön</a>
</body>
</html>
