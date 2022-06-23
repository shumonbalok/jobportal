@extends('layouts.dashboard')
@section('content')
  @include('layouts.includes.dashboard.rolePermessionbutton')
  <div class="container bg-white p-4 shadow-sm">
    <div class="row">
      <div class="offset-3 col-6">
        <form action="{{route('permission.store')}}" method="POST">
          @csrf
          <div class="form-group">
            <label for="exampleFormControlSelect1">Permission Name</label>
            <input type="text" name="name" required>
            @error('name')
            <p class="text-danger">{{$message}}</p>
            @enderror
          </div>
          <button type="submit" class="btn btn-success">save</button>
        </form>
      </div>

    </div>
  </div>
@endsection
