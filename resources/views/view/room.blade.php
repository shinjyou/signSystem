@extends('layouts.layout')

@section('link')
<a href="page?page={{$result}}">戻る</a>
@endsection

@section('title',"$place")

@section('floor',"$room"."号室")

@section('main')

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/room.css') }}">
    </head>

    <body>
        @if(!empty($imag))
        <div class="container-resulut">
            <div class="imag-container">
                <img class="imag" src={{$imag}}>
            </div>
            <div class="reset-button">
                <a class="reset" type="button" href="reset?room={{$room}}" >書き直す</a>
            </div>
        </div>
        @else
        <div class="signature-pad--footer txt-center">
            <div class="signature-pad--actions txt-center">
                <h5 class="sing-h5">サインをお願いします</h5>
            </div>
        </div>
            <div id="signature-pad" class="jay-signature-pad" >
                <div class="jay-signature-pad--body">
                    <canvas class="canvas" id="jay-signature-pad" width=678 height=276></canvas>
                </div>
                <div class="signature-pad--footer txt-center">
                    <div class="signature-pad--actions txt-center" >
                        <div class="clear-button">
                            <a class="clear" type="button" data-action="clear">リセット</a>
                        </div>
                        <div class="save-button">
                            <a class="button save" href="page?page={{$result}}" data-action="save-png">保存</a>
                        </div>
                    </div>
                </div>
            </div>

        <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            var wrapper = document.getElementById("signature-pad");
            var clearButton = wrapper.querySelector("[data-action=clear]");
            // var changeColorButton = wrapper.querySelector("[data-action=change-color]");
            var savePNGButton = wrapper.querySelector("[data-action=save-png]");
            // var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
            // var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
            var canvas = wrapper.querySelector("canvas");
            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });
            // Adjust canvas coordinate space taking into account pixel ratio,
            // to make it look crisp on mobile devices.
            // This also causes canvas to be cleared.
            function resizeCanvas() {
                // When zoomed out to less than 100%, for some very strange reason,
                // some browsers report devicePixelRatio as less than 1
                // and only part of the canvas is cleared then.
                var ratio =  Math.max(window.devicePixelRatio || 1,1);
                ratio = 1;
                // console.log(window.devicePixelRatio)
                // This part causes the canvas to be cleared
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                // This library does not listen for canvas changes, so after the canvas is automatically
                // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
                // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
                // that the state of this library is consistent with visual state of the canvas, you
                // have to clear it manually.
                signaturePad.clear();
            }
            // On mobile devices it might make more sense to listen to orientation change,
            // rather than window resize events.
            window.onresize = resizeCanvas;
            resizeCanvas();
            function download(dataURL, filename) {
                var blob = dataURLToBlob(dataURL);
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement("a");
                a.style = "display: none";
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }
            // One could simply use Canvas#toBlob method instead, but it's just to show
            // that it can be done using result of SignaturePad#toDataURL.
            function dataURLToBlob(dataURL) {
                var parts = dataURL.split(';base64,');
                var contentType = parts[0].split(":")[1];
                var raw = window.atob(parts[1]);
                var rawLength = raw.length;
                var uInt8Array = new Uint8Array(rawLength);
                for (var i = 0; i < rawLength; ++i) {
                    uInt8Array[i] = raw.charCodeAt(i);
                }
                return new Blob([uInt8Array], { type: contentType });
            }
            clearButton.addEventListener("click", function (event) {
                signaturePad.clear();
            });
            savePNGButton.addEventListener("click", function (event) {
                if (signaturePad.isEmpty()) {
                alert("署名を入力してください");
                } else {
                var dataURL = signaturePad.toDataURL();
                // download(dataURL, "signature.png");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post", //　GETでも可
                    url: "/url", //　送り先
                    data: { 
                        'url': dataURL,
                        'room_id': {{$room}} 
                        }, //　渡したいデータをオブジェクトで渡す
                    dataType : "json", //　データ形式を指定
                    scriptCharset: 'utf-8' //　文字コードを指定
                })
                .then(
                    function(param){　 //　paramに処理後のデータが入って戻ってくる
                        alert("保存しました");
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){ //　エラーが起きた時はこちらが実行される
                        console.log(XMLHttpRequest); //　エラー内容表示
                });
                }
            });
        </script>
        @endif
    </body>
</html>

@endsection