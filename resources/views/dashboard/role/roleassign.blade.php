@extends('layouts.dashboard')

@section('content')

    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Assign Role to User</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('role.index') }}" class="btn btn-primary font-weight-bolder">
                    Create New Role
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <form action="{{ route('store.assign') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Select User</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="user">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Select Role</label>
                            <select class="form-control select2" id="exampleFormControlSelect1" name="roles[]" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">save</button>
                    </form>
                </div>
                <div class="col-8">
                    <table id="myTable" class="table table-bordered" width="100%">
                        <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Role</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            @if (count($user->roles) > 0)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @foreach ($user->roles as $item)
                                            <span class="badge badge-danger">{{ $item->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="2">
                                    no user found!
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')

@endpush
