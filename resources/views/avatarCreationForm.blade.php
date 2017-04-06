@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ __('messages.addanavatar') }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('addAvatar') }}" accept="image/*" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group{{ ($errors->has('email') || (isset($msg) && $msg=='email already used')) ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">{{ __('messages.email') }}</label>

                                <div class="col-md-6">
                                    @if (isset($email))
                                        <input type="email" class="form-control" value="{{ $email }}" disabled>
                                        <input id="email" type="hidden" class="form-control" name="email" value="{{ $email }}" >
                                    @else
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                    @endif

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                    @if (isset($msg) && $msg=='an avatar with this email already exists')
                                        <span class="help-block">
                                        <strong>{{ $msg }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ (isset($msg) && $msg=='invalid format') ? ' has-error' : '' }}">
                                <label for="inputFile" class="col-md-4 control-label">{{ __('messages.youravatarimage') }}</label>
                                <div class="col-md-6">
                                    <input type="file" id="exampleInputFile" class="form-control" name="file" aria-describedby="helpBlock" required>
                                    <span id="helpBlock" class="help-block">Taille mini: 128px / Taille max: 256 px</span>
                                    <span id="helpBlock" class="help-block">Formats image: jpeg, png</span>
                                    @if (isset($msg) && $msg=='invalid format')
                                        <span class="help-block">
                                        <strong>{{ $msg }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('messages.create') }}
                                    </button>
                                    <a class="btn btn-primary" href="{{ route('user.dashboard') }}" role="button">{{ __('messages.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
