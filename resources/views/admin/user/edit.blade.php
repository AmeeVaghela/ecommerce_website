@extends('layouts.admin')

@section('title','Edit Users List')

@section('content')

<div class="row">
    <div class="col-md-12">
        @if (session('message'))
            <div class="alert alert-sucess ">{{ session('message')}}</div>
        @endif

        @if ($errors->any())
        <div class="alert alert-warning">
            @foreach ($errors->all() as $error )
                <li>{{$error}}</li>
            @endforeach
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>Edit Users
                    <a href="{{url('admin/users')}}" class="btn btn-danger btn-sm text-white float-end">
                     Back</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/users/'.$users->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name"  value="{{ $users->name}}" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="text" name="email" value="{{ $users->email}}" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password</label>
                            <input type="text" name="password"  class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Select Role</label><br/>
                           <select name="role_as" class="form-control">
                                <option value="">Select Role</option>
                                <option value="0" {{ $users->role_as == '0' ? 'selected':''}}>User</option>
                                <option value="1" {{ $users->role_as == '1' ? 'selected':''}}>Admin</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                        <button type="submit" class="btn btn-primary float-end">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
