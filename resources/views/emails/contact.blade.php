@extends('emails/layouts/default')

@section('content')
    <p>Form Data:</p>

    <p>Name: {{ $data['name'] }}</p>

    <p>Email: {{ $data['email'] }}</p>

    <p>Message: {{ $data['message'] }}  </p>

@stop
