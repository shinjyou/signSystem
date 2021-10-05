@extends('layouts.layout')

@section('link')
<a href="login">ログイン</a>
@endsection

@section('title',$place)

@section('floor')

@section('main')

<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<!-- List -->
<div class="select-button">
  <div class="et_pb_module">
    <button id="fuu" class='secList'>新規作成</button>
  </div>
  
  <div class="et_pb_module">
    <button id="euu" class='secList'>更新</button> 
  </div>
  
  <div class="et_pb_module">
    <button id="guu" class='secList'>削除</button> 
  </div>
</div>
  
<!-- Sections -->
  <div class="fuu section">
    <div class="et_pb_text_inner1">
      <h2>新規作成</h2>

      <form action="new" method="post" id="new">
        @csrf
        <label>マンション名：<input type="text" name="building" required></label><br>
        <label>階層：<input type="text" name="floor" id="floor" required></label><br>

        <script>
            let formInput = document.forms.new.floor;
            formInput.addEventListener('input',()=>{ //入力操作があれば
                let output = formInput.value;
                var parent = document.getElementById('parent'); //親要素取得

                if (parent.hasChildNodes()){  //parentに子要素があるか
                   document.getElementById('child').remove(); 
                }
                var div = document.createElement('div');
                div.id = 'child';
                parent.appendChild(div); //childの生成

                for(let i=1; i<=output; i++){
                    var input = document.createElement('input');
                    input.className = 'new-input';
                    input.type = 'number';
                    input.id = 'input-number' + i;
                    input.name = 'floor' + i;
                    input.min = 0;

                    var label = document.createElement('label');
                    label.textContent = i + '階';

                    div.appendChild(label); //labelの生成
                    label.appendChild(input); //inputの生成
                }
            });
        </script>

        <label>部屋の数：<input type="text" name="room" id="roomCount"></label><br>

        <script>
            let inputRoomNumber = document.getElementById('roomCount');
            inputRoomNumber.addEventListener('input',()=>{
                for(let i=1; i<=formInput.value; i++){
                    var input = document.getElementById('input-number' + i);
                    var count = document.getElementById('roomCount');
                    input.value = count.value;
                    document.getElementById('input-number1').value = 0;
                }
            });
        </script>

        <div id="parent"></div>
        <label>パスワード：<input type="password" name="password" required></label>
      </form>
    </div> 
    <button class="create-new" type="submit" form="new">PUSH !</button> 
  </div>
  
  <div class="euu section">
    <div class="et_pb_text_inner2">
      <h2>一部削除</h2>
      <form id="update">
      @csrf
          <select name="select" id="select" required>
            <option value="" style="display:none;">選択して下さい</option>
              @for($i=0; $i < count($list); $i++)
                  <option id="selectPlace" value={{$id[$i]->id}}>{{$list[$i]}}</option>
              @endfor
          </select>
          
            <div class="multiselect">
              <div class="selectBox" onclick="showCheckboxes()">
                <select>
                  <option id="select-an-option" >None option</option>
                </select>
                <div class="overSelect"></div>
              </div>
              <div id="checkboxes">
              </div>
            </div>
        
        <script>
          var expanded = false;
            function showCheckboxes() {
                var checkboxes = document.getElementById("checkboxes");
                if (!expanded) {
                    checkboxes.style.display = "block";
                    expanded = true;
                } else {
                    checkboxes.style.display = "none";
                    expanded = false;
                }
            }

            var elem = document.getElementById('select');
            elem.addEventListener('change', function(){  //selectを操作すれば発火
                var index = elem.value; //selectの値取得

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post", //　GETでも可
                    url: "ajax", //　送り先
                    data: { 
                        'index': index,
                        }, //　渡したいデータをオブジェクトで渡す
                    dataType : "json", //　データ形式を指定
                    scriptCharset: 'utf-8' //　文字コードを指定
                })
                .then(
                    function(roomNumber){　 //　処理後のデータが入って戻ってくる
                        if(document.getElementById("checkboxes").lastChild){
                            select = document.getElementById('checkboxes');
                            while(select.lastChild)
                            {
                                select.removeChild(select.lastChild);
                            }
                        }
                        roomNumber.forEach(element => {　//配列の数の分入れる
                            document.getElementById('select-an-option').textContent = "select an option";
                            var check = document.getElementById('checkboxes');

                            var label = document.createElement('label');
                            label.id= element;
                            label.textContent = element;

                            var input = document.createElement('input');
                            input.setAttribute('class','checkboxesSelect');
                            input.type = "checkbox";
                            input.value = element;
                            input.name = "selectInput";

                            check.appendChild(label);
                            document.getElementById(element).prepend(input);
                        });
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){ //　エラーが起きた時はこちらが実行される
                        console.log(XMLHttpRequest); //　エラー内容表示
                });
            });
        </script>
      </form>
    </div>
    <button class="part-delete" type="submit" form="update">
      <span class="box">
        <span class="box-face front">一部削除<i class="fas fa-angle-right right-arrow"></i></span>
        <span class="box-face back">送信！<i class="fas fa-angle-right right-arrow"></i></span>
      </span>
    </button>
  </div>

  <div class="euu section">
    <div class="et_pb_text_inner2" >
      <h2>一部追加</h2>
        <form action="partAdd" method="post" id="part-add">
          @csrf
          <select name="buildingName" id="" required>
            <option value="" style='display:none'>選択してください</option>
              @for($i=0; $i < count($list); $i++)
                <option value={{$id[$i]->id}}>{{$list[$i]}}</option>
              @endfor
          </select><br>
          <label>追加する数：<input type="text" id="add-number" required></label><br>
          <div id="add-roomNumber"></div>
        </form>
    </div>
    {{-- <button class="add-button" type="submit" form="part-add">一部追加</button> --}}

    <button class="add-button" type="submit" form="part-add">
      <span class="button-text">一部追加</span>
      <span class="button-text">送信！</span>
    </button>
    @php
      if(!empty($failed)){
        $alert =
          "<script type='text/javascript'>alert('". implode(',',$failed) . "号室は既に存在しています。" . "');</script>";
        echo $alert;
      }
    @endphp
  </div>
  
  <div class="guu section">
    <div class="et_pb_text_inner3">
      <h2>削除</h2>
      <form action="delete" method="post" id="delete">
      @csrf
        <label>削除するマンション
        <select name="list" id="list" required>
          <option value="">選択してください</option>
          @for($i=0; $i < count($list); $i++)
              <option value="{{$list[$i]}}">{{$list[$i]}}</option>
          @endfor
        </select>
        </label>
      </form>
    </div> 
     <button class="all-delete" type="submit" form="delete">送信</button>
  </div>
</body>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$(function(){
  $('.section').hide();
  
  $('.secList').on('click',function(){
        // クリックした要素の ID と違うクラス名のセクションを非表示
    $('.section').not($('.'+$(this).attr('id'))).hide();
    // クリックした要素の ID と同じクラスのセクションを表示
    // $('.'+$(this).attr('id')).show(1000);
    
    // toggle にすると、同じボタンを 2 回押すと非表示になる
    $('.'+$(this).attr('id')).toggle(900);
  });
});

$(function(){
    $('#update').submit(function(){
        var vals = $('input[name=selectInput]:checked').map(function(){
            return $(this).val();
        }).get();

    var placeSelect = $('#select').val();

        $.ajax({
            type: "post", //　GETでも可
            url: "partDelete", //　送り先
            data: { 
              value: vals,
              place: placeSelect,
            },
        })
        .then(
            function(param){　 //　paramに処理後のデータが入って戻ってくる
              // alert('選択した部屋を削除しました');
              window.location.href = 'admin';
              console.log(param);
            },
            function(XMLHttpRequest, textStatus, errorThrown){ //　エラーが起きた時はこちらが実行される
              console.log(XMLHttpRequest); //　エラー内容表示
        });
    });
});

let addNumber = document.getElementById('add-number'); //追加したい部屋の数
addNumber.addEventListener('input',()=>{ //入力操作があれば
  if(document.getElementById('add-roomNumber').lastChild){
    let child = document.getElementById('add-roomNumber');
    while(child.lastChild){
      child.removeChild(child.lastChild);
    }
  }
  var addRoomNumber = document.getElementById('add-roomNumber') //inputを追加するdiv
  var inputRoom = addNumber.value;
  for(let i=1; i <= inputRoom; i++){
      var label = document.createElement('label');
      label.innerHTML = "その" + i;
      addRoomNumber.appendChild(label);

      var input = document.createElement('input');
      input.className = 'roomNumber';
      input.name = "item[]";
      input.type = "number";
      input.min = 0;
      input.required = true;

      addRoomNumber.appendChild(label);
      label.appendChild(input);
  }
});

// $(function(){
//   $('#part-add').submit(function(){
//     var a = document.getElementsByName('item[]').value;
//     console.log(a);
//   });
// });

</script>
@endsection
