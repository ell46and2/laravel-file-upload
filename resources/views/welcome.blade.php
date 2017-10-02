<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
    <style type="text/css">
        body, html {
            height: 100%;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
<body class="container">
    
    <form action="/avatars" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <input type="file" name="avatar">

        <button type="submit">Save Avatar</button>
    </form>

</body>
</html>