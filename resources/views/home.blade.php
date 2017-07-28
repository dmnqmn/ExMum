@extends('layouts.main')

@section('title', '首页')

@section('css')
@php css('home.css') @endphp
@endsection

@section('header')
    @parent
@endsection

@section('main')
@endsection

@section('js')
@php js('home.js') @endphp
@endsection
