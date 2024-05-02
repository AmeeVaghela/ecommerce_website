@extends('layouts.app')

@section('title','Thank you for shopping')

@section('content')

    <div class="py-3 pyt-md-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    @if (session('message'))
                    <h5 class="alert alert-sucess">{{session('message')}}</h5>
                    @endif

                    <div class="p-4 shadow bg-white">
                        <h2>Your logo</h2>
                        <h4>Thank you for shopping wih Shopcy</h4>
                        <a href="{{ url('/collections')}}" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
