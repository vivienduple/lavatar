@extends('layouts.app')

@section('content')
<div class="container">
    <div class="content">
        <div class="jumbotron">
            <h1>Lavatar</h1>
            <p class="lead">
                Bienvenue sur Lavatar, l'appli qui stocke tes avatars
            </p>

            <a type="button" class="btn btn-lg btn-success" href="{{ url('/login') }}">Se connecter</a>

            <a type="button" class="btn btn-lg btn-default" href="{{ url('/register') }}">S'enregistrer</a>
        </div>
        <footer class="footer">
            <p>&copy; 2017 Lavatar.com</p>
        </footer>
    </div>
</div>
@endsection
