<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="theme-color" content="#ffffff">
    <title>imágen</title>
    <style>
        button {
            background-color: black;
            color: white;
            font-weight: bold;
            font-size: 16px;
            padding: 4px 7px;
            border: 2px solid black;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #222222;
        }
    </style>
</head>
<body style="font-family: sans-serif">
<div style="margin-bottom: 8px">
    {{-- Will go back if possible, otherwise will close the window when there is no history. --}}
    <button onclick="window.history.back() || window.history.go(-1); if (window.history.length===1) window.close();">
        &times; {!! __('Close') !!}
    </button>
</div>
<div style="width: 100%">
    {!! $slot !!}
</div>
</body>
</html>
