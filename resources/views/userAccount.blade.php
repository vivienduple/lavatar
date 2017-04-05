@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Panneau de bords</div>

                <div class="panel-body">
                    @if($avatars != null)
                        @php
                            dd($avatars);
                        @endphp
                        <div class="alert alert-info" role="alert">Aucun avatar disponible pour votre profil</div>
                    @else
                        @foreach ($avatars as $avatar)
                            @php
                                dd($avatar);
                            @endphp
                        <div class="list-group">
                            <a href="#" class="list-group-item active">
                                <div class="col-md-4">
                                    <p class="list-group-item-text">{{ $avatar }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="list-group-item-text">{{ $avatar }}</p>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-default"></button>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>

                <div class="panel-footer">Panel footer</div>
            </div>
        </div>
    </div>
</div>
@endsection
