<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container border border-black rounded w-25 d-flex flex-column align-items-center mt-5 p-3">
        <h2>Login</h2>
        @if(session('error'))
            <p style="color:red">{{ session('error') }}</p>
        @endif
        <form method="POST" action="{{ route('login') }}" class="mt-3 w-100">
            @csrf
            <input type="email" name="email" placeholder="E-mail" required class="form-control"><br>
            <input type="password" name="password" placeholder="Senha" required class="form-control"><br>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    

    <p class="mt-3">Não tem conta? <a href="{{ route('register') }}">Cadastrar</a></p>
</div>
</body>
</html>