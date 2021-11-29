@extends('layouts.app')

@section('content')
    <login-component csrf="{{ csrf_token() }}"></login-component>
@endsection
