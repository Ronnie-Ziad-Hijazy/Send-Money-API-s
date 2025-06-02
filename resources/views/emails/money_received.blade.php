<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Money Received Email</title>
</head>
<body>

    <h2>Money Received Confirmation</h2>

    <p>Hi {{ $transaction->recipient->name }},</p>

    <p>You have successfully Received ${{ $transaction->amount }} from {{ $transaction->sender->email }}.</p>

    <p>Transaction ID: {{ $transaction->id }}</p>
    
    
</body>
</html>