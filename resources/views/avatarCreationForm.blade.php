@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Créez votre nouvel avatar!</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('addAvatar') }}" accept="image/*" enctype="multipart/form-data">
                            {{ csrf_field() }}


                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">Votre adresse e-mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputFile" class="col-md-4 control-label">Votre image d'avatar</label>
                                <div class="col-md-6">
                                    <input type="file" id="exampleInputFile" class="form-control" name="file" aria-describedby="helpBlock" required>
                                    <span id="helpBlock" class="help-block">Taille mini: 128px / Taille max: 256 px</span>
                                    <span id="helpBlock" class="help-block">Formats image: jpeg, png, bmp, gif, or svg</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Créer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
