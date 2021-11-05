<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Order placed on these products:</h1>

    @foreach ($order->products as $product)
        <p>{{ $product->name }} - {{ $product->pivot->quantity }} piece/s</p>
    @endforeach

</body>
</html>