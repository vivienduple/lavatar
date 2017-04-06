@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">{{ __('messages.dashboard') }}</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{ __('messages.yoursavatars') }}
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('addAvatar') }}">
                                                <button type="submit" class="btn btn-success pull-right">
                                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                    @if($avatars->isEmpty())
                                        <div class="alert alert-info" role="alert">{{ __('messages.noavatar') }}</div>
                                    @else
                                        @foreach ($avatars as $avatar)
                                            <div class="list-group">
                                                <form class="form-horizontal" role="form" method="GET" action="@php echo route('removeAvatar.process', [$avatar->id]) @endphp">
                                                    {{ csrf_field() }}
                                                <div class="list-group-item default">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <img src="@php echo config('lavatar.avatarStorageURL').'128_'.$avatar->image @endphp" alt="{{ 'avatar' }}"></img>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="list-group-item-text">{{ $avatar->email }}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="submit" class="btn btn-danger pull-right">
                                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        @endforeach
                                    @endif
                            </div>
                        </div>
                    </div>
                <div class="panel-footer">LP Multimédia - Benjamin Abadie, Julie Thébaut, Vivien Duplé - Iut de Bayonne et du Pays Basque</div>
            </div>
        </div>
    </div>
</div>
@endsection
