<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gargoyle</title>
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato:300,400,700);

        body {
            margin:0;
            text-align:center;
            color: #999;
        }

        .welcome {
            width: 300px;
            height: 300px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -150px;
            margin-top: -150px;
        }
    </style>

    <script src="js/jquery-2.0.3.js"></script>
</head>
<body>
    <div class="welcome">
        <h1>Gargoyle</h1>
        <p>
            <script>
                // var data = { "user": "1", "url": "www.wurst.de" }
                var data = {
                   // "email": "hello@world.de",
                   "name": "Tesla",
                   "password": "123456"
                }

                // fire off the request
                $.ajax({
                    // url: "user/new",
                    // url: "login",
                    url: "push",
                    type: "post",
                    // data: null,
                    // data: {"name": "Tesla", "password": "123456"},
                    data: {"url": "new post"},
                    // data: data,
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("The following error occured: "+
                            textStatus, errorThrown);
                    }
                });
            </script>
        </p>
    </div>
</body>
</html>
