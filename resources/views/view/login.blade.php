<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ログイン画面</title>
    </head>

    <body>
        <div class="container">
            <h1 class="welcome">ようこそ！</h1>

            <div class="form">
                <form action="login" method="POST">
                    @csrf
                    <select name="select"> <!-- プルダウンメニュー -->
                        <option value="admin" style="display:none;">選択して下さい</option>
                        @for($i=0; $i < count($place); $i++)
                            <option value="{{$place[$i]}}">{{$place[$i]}}</option>
                        @endfor
                    </select>

                    <label><p class="pass">パスワード</p> <!-- パスワード入力箇所 -->
                        <input class="send" type="password" name="pass">
                    </label>
            
                    <input class="button" type="submit" value="ログイン"> <!-- 送信ボタン -->
                </form>
            </div>
        </div>
    </body>
</html>