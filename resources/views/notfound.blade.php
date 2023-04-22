@extends('shop.layouts.sub')
@section('title', 'S-Book | 404')
@section('content')

<div class="container text-center">
    <div class="logo-404">
        <a href="index.html"><img src="images/home/logo.png" alt="" /></a>
    </div>
    <div class="content-404">
        <h1><b>OPPS!</b> Page không tồn tại</h1>
        <h2><a href="{{URL::to('/')}}">Bring me back Home</a></h2>
    </div>
</div>
@endsection
