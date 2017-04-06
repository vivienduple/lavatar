@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">{{ __('messages.firstavatar') }}</div>
                    <div class="panel-body">

                        <form class="form-horizontal" role="form" method="POST" action="">
                            {{ csrf_field() }}
                            <div class="col-lg-4 col-lg-offset-4">
                                <div class="form-group">
                                    <!--<input class="btn btn-default active btn-success" type="submit" value="Oui">-->
                                    <a class="btn btn-primary" href="{{ route('addAvatarOnReg') }}"
                                       role="button">{{ __('messages.yes') }}</a>
                                    <a class="btn btn-primary" href="{{ route('user.dashboard') }}"
                                       role="button">{{ __('messages.nothanks') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
