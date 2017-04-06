@extends('layouts.app')

@section('content')
<div class="container">
    <div class="content">
        <div class="jumbotron">
            <h1>Lavatar</h1>
            <p class="lead">
                Bienvenue sur Lavatar, l'appli qui stocke tes avatars
            </p>

            <a type="button" class="btn btn-lg btn-success" href="{{ url('/login') }}">connexion</a>

            <a type="button" class="btn btn-lg btn-default" href="{{ url('/register') }}">register</a>
        </div>
        <footer class="footer">
            <p>&copy; 2017  larval commpany, Inc.</p>
        </footer>
    </div>
</div>
@endsection
