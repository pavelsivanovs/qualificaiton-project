<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $_ENV['APP_NAME'] ?></title>

    <script defer src="/js/manifest.js"></script>
    <script defer src="/js/vendor.js"></script>
    <script defer src="/js/index.js"></script>

    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="">
    This one is home.
    <div id="root" class="mx-auto container min-h-screen">
    </div>
</body>
</html>
