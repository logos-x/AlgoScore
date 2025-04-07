<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;">
    <h1>Thanks for your order!!</h1>
    <h2>Your Order Id is: {{ $mailData['order']->id }}</h2>

    <h2>Products</h2>

    <table cellpadding="3" cellspacing="3" border="0">
        <thead>
        <tr style="background: #CCC;">
            <th>Product</th>
            <th width="100">Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($mailData['order']->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }} ₫</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="1" align="right">Subtotal:</th>
            <td>{{ number_format($mailData['order']->subtotal, 0, ',', '.') }} ₫</td>
        </tr>
        <tr>
            <th colspan="1" align="right">Discount:</th>
            <td>{{ number_format($mailData['order']->discount, 0, ',', '.') }} ₫</td>
        </tr>
        <tr>
            <th colspan="1" align="right">Grand Total:</th>
            <td>{{ number_format($mailData['order']->grand_total, 0, ',', '.') }} ₫</td>
        </tr>
        </tbody>
    </table>
</body>
</html>
