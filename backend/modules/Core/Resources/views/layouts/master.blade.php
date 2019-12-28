<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title or 'Module Core' }}</title>

    <style>
        .entity-wrapper {
            display: flex;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding: 10px;
        }

        .entity-info-wrapper {
            order: 2;
            padding: 0 20px;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .qr-code-img {
            order: 1;
        }

        .qr-code-link {
            margin: 20px 0;
        }

        .sub-entity-wrapper {

        }
    </style>
</head>
<body>
@yield('content')
</body>
</html>
