<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .content {
            width: 500px;
            height: 500px;
            overflow: hidden;
        }
        .image1 {
            display: block;
            width: 200px;
            height: 200px;
            margin: 20px 150px;
        }
        .image2 {
            display: block;
            width: 100px;
            height: 100px;
            margin-top: 33px;
            margin-left: 400px;
        }
        h3 {
            text-align: center;
            font-size: 15px;
            margin-bottom: 33px;
        }
        @font-face {
            font-family: 'msyh';
            font-style: normal;
            font-weight: normal;
            src: url({{ '../storage/fonts/msyh.ttf' }}) format('truetype');
        }
        body {
            font-family: msyh, DejaVu Sans,sans-serif;
        }
    </style>
</head>
<body>
<div class="content">

    <h3>{{$orderNo}} &nbsp;&nbsp;&nbsp; {{'../../storage/fonts/msyh.ttf' }} &nbsp;&nbsp; {{$recipients}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$sourceName}}</h3>
    <h3 class="order-right">{{$orderNo}}</h3>
    <div>
        <img class="image1" width="300px" src="{{$imageUrl}}" alt="">
    </div>
    <div>
        <img class="image2" width="100px" src="{{$imageUrl}}" alt="">
    </div>

</div>
</body>
</html>