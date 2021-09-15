<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test</title>
</head>
<body>
Hello {{$user->name}}
<br>
Here is the code you need to change your Macca cafe login credentials:
<strong>{{$user->generatedCode}}</strong>
<br>
<hr>
<br>
If you are not trying to change your Macca cafe login credentials please ignore this email. It is possible that another
user entered their login information incorrectly.
</body>
</html>
