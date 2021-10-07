@extends('layouts.layout')

@section('link')
<a href='/login'>ログイン</a>
@endsection

@section('title',"$place")

@section('floor',"$page"."階")

@section('main')
    <link rel="stylesheet" href="{{ asset('css/page.css') }}">

    <div class="pagination-container"> <!-- ページネーション -->
        <div class="pagination">
            <?php if($_GET['page'] < $startFloor+1):?>
                <span><</span>
            <?php else: ?>
                <a href="?page=<?php print($_GET['page']-1); ?>"><</a>
            <?php endif; ?>
        </div>
        
        <?php for($i = $startFloor; $i <= $lastFloor; $i++):?>
            <?php if($_GET['page'] == $i):?>
                <span class="active"><?php echo $i ?></span>
            <?php else: ?>
                <a class="pagination" href="?page=<?php echo $i ?>"><?php echo $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <div class="pagination">
            <?php if($_GET['page'] > $lastFloor-1):?>
                <span>></span>
            <?php else: ?>
                <a href="?page=<?php print($_GET['page']+1); ?>">></a>
            <?php endif; ?>
        </div>
    </div>

    <table> <!-- 表 -->
        @foreach($data as $item)
            @if(isset($item->imag))
            <tr class="add-room">
                <th class=“room-num”>{{ $item->room }}</th>
                <td>@if( !empty($item->time))<span class="resave">予約</span>@endif</td>
                <td>@php echo substr($item->time,0,5) @endphp</td>
                <td><a href="room?room={{$item->room}}">詳細</a></td>
            </tr>
            @else
            <tr>
                <th class=“room-num”>{{ $item->room }}</th>
                <td>@if( !empty($item->time))<span class="resave">予約</span>@endif</td>
                <td>@php echo substr($item->time,0,5) @endphp</td>
                <td><a href="room?room={{$item->room}}">詳細</a></td>
            </tr>
            @endif
        @endforeach
    </table>

{{-- 予約ページ --}}

    <form action="{{ action('OrgController@resaveSet') }}" method="POST">
    @csrf
    <div class="resave-post">
        <select class="room-number" name="RoomSelect" required>
            <option value="">-部屋番号-</option>
            @foreach( $data as $item )
                <option value="{{$item->room}}">{{$item->room}}</option>
            @endforeach
        </select>
        <select class="time-set" name="time" required>
            <option value="">-予約時間-</option>
            <option value="" >予約取り消し</option>
            <optgroup label="午前">
                @php
                    $time_start = 9;
                    $time_end = 12;
                    $minute_increase = 30;
                @endphp
                @for($h = $time_start; $h <= $time_end; $h++)
                    @for($m = 0; $m < 60; $m = $m + $minute_increase)
                        <?php $resulut = date("H:i", strtotime("00:00 + " . $h . "hour" . $m . "minute")) ?>
                        <option value="<?php echo $resulut ?>"><?php echo $resulut ?></option>
                    @endfor
                @endfor
            </optgroup>
            <optgroup label="午後">
                @php
                    $time_start = 14;
                    $time_end = 16;
                    $minute_increase = 30;
                @endphp
                @for($h = $time_start; $h <= $time_end; $h++)
                    @for($m = 0; $m < 60; $m = $m + $minute_increase)
                        <?php $resulut = date("H:i", strtotime("00:00 + " . $h . "hour" . $m . "minute")) ?>
                        <option value="<?php echo $resulut ?>"><?php echo $resulut ?></option>
                    @endfor
                @endfor
            </optgroup>
        </select>
        <button class="resave-set-button" method="submit">予約！</button>
    </div>
    </form>
@endsection