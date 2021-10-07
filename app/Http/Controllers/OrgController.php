<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgController extends Controller
{
    public function login(Request $request) //ログインページ
    {
        $result = [];
        $data = DB::table('building')->select('building_id')->get();
        foreach($data as $item){
            if($item->building_id == "admin"){continue;}
            $result[] = $item->building_id;
        }
        return view('view.login',['place'=>$result]);
    }

    public function collation(Request $request) //認証
    { 
        $place = $request->select; //プルダウンの値
        $data = $request->session()->put('place',$place); //セッションに保存

        $result = DB::table('building')
            ->join('password','building.id','=','password.building_id') //buildingとpasswordを結合
            ->where('building.building_id',$place) //プルダウン値で検索
            ->first();
        $pass = $result->pass; //プルダウンの値で検索したパスを抜きだす
        $inputPass = md5($request->pass); //パスワード入力を受け取る

        if ($place == "admin") { //管理画面
            if($inputPass == $pass){
                return redirect('admin');
            } else {
                return redirect('login');
            }
        } elseif($pass == $inputPass) { //パスの照合
            return redirect('page?page=2'); //微妙
        } else {
            return redirect('login');
        } 
    }

    public function admin(Request $request) //管理画面
    {
        $result = [];
        $id = [];
        if(is_null($request->session()->get('place'))){ //セッションが切れたらloginページへ
            return redirect('login');
        } else {
            $place = $request->session()->get('place');
        }

        $data = DB::table('building')->select('building_id')->get();
        foreach($data as $item){
            if($item->building_id == "admin"){continue;} //adminはスキップ
            $result[] = $item->building_id;
        }
        foreach($result as $item){
            $id[] = DB::table('building')
                    ->where('building_id',$item)
                    ->first('id');
        }
        if(session()->has('failed')){
            $failed = session()->get('failed');
            session()->forget('failed');
        } else {
            $failed = [];
        };
        return view('view.admin',['place'=>$place,'list'=>$result,'id'=>$id, 'failed'=>$failed]);
    }

    public function newBuildingSet(Request $request) //新規マンション登録
    {
        $building = $request->building;
        $inputFloor = $request->floor;
        $password = $request->password;

        DB::table('building')->insert(['building_id'=>$building]); //building名追加
        $id = DB::table('building') //buildingのid抜き出し
                ->where('building_id',$building)->first()->id;
        DB::table('password') //passwordのpass挿入
                ->insert(['building_id'=>$id,'pass'=>md5($request->password)]);

        for($floor=1; $floor <= $inputFloor; $floor++){
            $setFloor = "floor" . $floor;
            if($request->$setFloor == 0){continue;}
            $numberRoom = $request->$setFloor;
            for($room=1; $room <= $numberRoom; $room++){
                $number = substr(("0" . $room),-2,2);
                $result = $floor . $number;
                DB::table('room')->insert(['building_id'=>$id,'room'=>$result]); 
            }
        }

        return redirect('admin');
    }

    public function ajax() //ajax処理
    {
        $result = [];
        $id = [];
        $roomNumber = [];
        $data = DB::table('building')->select('building_id')->get();
        foreach($data as $item){
            if($item->building_id == "admin"){continue;} //adminはスキップ
            $result[] = $item->building_id;
        }
        foreach($result as $item){
            $id[] = DB::table('building')
                    ->where('building_id',$item)
                    ->first('id');
        }
        for($i=0; $i < count($id); $i++){
            $roomData = DB::table('room')
                ->where('building_id',$id[$i]->id)
                ->get('room');
            $room[$id[$i]->id] = $roomData;
        }

        header('Content-type: application/json; charset=utf-8'); // ヘッダ（データ形式、文字コードなど指定）
        $index = filter_input(INPUT_POST, 'index'); // 送ったデータを受け取る（GETで送った場合は、INPUT_GET）
        for($i=0; $i < count($room[$index]); $i++){
            $roomNumber[] = $room[$index][$i]->room;
        }

        echo json_encode($roomNumber); //　echoするとデータを返せる（JSON形式に変換して返す）

    }

    public function partDelete(Request $request) //一部削除ページ
    {
        foreach($request->value as $item){
            DB::table('room')
            ->where('building_id',$request->place)
            ->where('room',$item)
            ->delete();
        }

        return response()->json([
            'result' => true,
            'place' => $request->place,
            'data' => $request->value,
        ]);
    }

    public function partAdd(Request $request) //一部追加
    {
        $failed = [];
        for($i=0; $i < count($request->item); $i++){
            $result = DB::table('room')
            ->where('building_id', $request->buildingName)
            ->where('room',$request->item[$i])
            ->first();

            if(!isset($result)){ //nullであれば
                DB::table('room') //挿入を実行
                    ->insert(['building_id'=>$request->buildingName,
                            'room'=>$request->item[$i]]);
            } else {
                $failed[] = $result->room;
            }
        }
        session()->put('failed',$failed);
        return redirect('admin');
    }

    public function buildingDelete(Request $request) //マンションの削除
    {
        $id = DB::table('building')
            ->where('building_id',$request->list)
            ->first()->id;

        DB::table('room')
            ->where('building_id',$id)
            ->delete();

        DB::table('password')
            ->where('building_id','=',$id)
            ->delete();

        DB::table('building')
            ->where('id',$id)
            ->delete();

        return redirect('admin');
    }

    public function page(Request $request) //マンションページ
    {
        if(is_null($request->session()->get('place'))){ //セッションが切れたらloginページへ
            return view('view.login');
        } else {
            $place = $request->session()->get('place');
        }

        $page = substr($request->page,0,1); //現在のページの値

        $pageMin = $page * 100; //３桁に変換
        $pageMax = $pageMin + 100;  //100の位を更新
        $data = DB::table('room') //各階を抜き出す
                ->join('building','building.id','=','room.building_id')
                ->where('building.building_id',$place)
                ->where('room', '>', $pageMin)
                ->where('room', '<', $pageMax)
                ->get();

        $last = DB::table('room')  //最後の階層を抜き出す
                ->join('building','building.id','=','room.building_id')
                ->where('building.building_id',$place)
                ->orderby('room','desc')->limit(1)->first();
        $lastFloor = substr($last->room,0,1);

        $start = DB::table('room')  //初めの階層を抜き出す
                ->join('building','building.id','=','room.building_id')
                ->where('building.building_id',$place)
                ->orderby('room','asc')->limit(1)->first();
        $startFloor = substr($start->room,0,1);

        // dd($data);
        return view('view.page',
            ['place'=>$place, 'page'=>$page, 'data'=>$data,
            'lastFloor'=>$lastFloor,'startFloor'=>$startFloor,]);
    }

    public function resaveSet(Request $request) //予約設定
    {
        $result = substr($request->RoomSelect,0,1);
        if(is_null($request->session()->get('place'))){ //セッションが切れたらloginページへ
            return view('view.login');
        } else {
            $place = $request->session()->get('place');
        }

        DB::table('room')
            ->join('building', 'building.id','=','room.building_id')
            ->where('building.building_id',$place)
            ->where('room.room',$request->RoomSelect)
            ->update(['time' => $request->time]);
        return redirect("page?page=$result");
    }

    public function reset(Request $request) //サイン取り消し
    {
        $room = $request->room;
        $result = substr($room,0,1); //頭文字抜き取り
        $place = $request->session()->get('place');
        DB::table('room')
            ->join('building','building.id','=','room.building_id')
            ->where('building.building_id', $place)
            ->where('room.room', $room)
            ->update(['imag' => '']);
        return redirect("room?room=$room");
    }

    public function room(Request $request) //サインページ
    {
        if(is_null($request->session()->get('place'))){ //セッションが切れたらloginページへ
            return view('view.login');
        } else {
            $place = $request->session()->get('place');
        }

        $room = $request->room; //部屋番号
        $result = substr($room,0,1);
        $place = $request->session()->get('place');
        $imag = DB::table('room')
                ->join('building','building.id','=','room.building_id')
                ->where('building.building_id', $place)
                ->where('room.room',$room)
                ->first('imag');
        return view('view.room',['room'=>$room,'place'=>$place,'imag'=>$imag->imag,'result'=>$result]);
    }

    public function url(Request $request) //サイン保存設定
    {
        header('Content-type: application/json; charset=utf-8'); // ヘッダ（データ形式、文字コードなど指定）
        $data = filter_input(INPUT_POST, 'url'); // 送ったデータを受け取る（GETで送った場合は、INPUT_GET）
        $room = filter_input(INPUT_POST, 'room_id'); // 送ったデータを受け取る（GETで送った場合は、INPUT_GET）

        DB::table('room')
            ->join('building','building.id','=','room.building_id')
            ->where('room', $room)
            ->where('building.building_id',$request->session()->get('place'))
            ->update(['imag' => $data]);

        $param = $data;	//　画像処理
        echo json_encode($param); //　echoするとデータを返せる（JSON形式に変換して返す）

    }
}