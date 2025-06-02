<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Money Sent Email</title>
</head>
<body>

    <h2>Money Sent Confirmation</h2>

    <p>Hi {{ $transaction->sender->name }},</p>

    <p>You have successfully sent ${{ $transaction->amount }} to {{ $transaction->recipient->email }}.</p>

    <p>Transaction ID: {{ $transaction->id }}</p>
    
    
</body>
</html>