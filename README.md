# Laravel CRUD passo a passo
Para referência futura, esse é o passo a passo para criar um crud

## Criar uma tabela para salvar dados
<p style="color: #55ddff; font-weight: bold;">Demontração de como criar uma tabela com o Laravel</p>
- Registre o banco de dados na pasta .env

- Rode o seguinte comando no terminal:

```
php artisan make:migration create_users_table
``` 

- Encontre o file com o nome "xxxx_xx_xx_xxxxxx_create_users_table.php" em `/database/migrations`

- Edite a função "*up*" na migration com a seguinte informação:

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});
```

- Faça a migração com o comando abaixo para atualizar o banco de dados

```
php artisan migrate
```

## Criar o controller para autenticação
<p style="color: #55ccff; font-weight: bold;">Fazendo os controles de formulário CRUD</p>

- Crie o controller utilizando o seguinte comando

```
php artisan make:controller AuthController
```

- Abra o file em `/app/Http\Controllers/AuthController`

- Coloque a seguinte informação:

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Mostrar formulário de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Processar login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Credenciais inválidas');
    }

    // Mostrar formulário de cadastro
    public function showRegister()
    {
        return view('auth.register');
    }

    // Processar cadastro
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Dashboard (após login)
    public function dashboard()
    {
        return view('dashboard');
    }
}
```

- Após isso, crie as rotas para funcionamento dentro do file `/routes/web.php` com a seguinte informação:

```php
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

## Criação das views:
<p style="color: #55ccff; font-weight: bold;">Esta parte é mais subjetiva ou exemplificativa, pois as views pode mudar de projeto a projeto, porém mostra os exemplos de formulários e links corretos</p>

Exemplo de página login:
```html
<body>
    <h2>Login</h2>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="E-mail" required><br>
        <input type="password" name="password" placeholder="Senha" required><br>
        <button type="submit">Entrar</button>
    </form>

    <p>Não tem conta? <a href="{{ route('register') }}">Cadastrar</a></p>
</body>
```

Exemplo de página de cadastro:
```html
<body>
    <h2>Cadastro</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" placeholder="Nome" required><br>
        <input type="email" name="email" placeholder="E-mail" required><br>
        <input type="password" name="password" placeholder="Senha" required><br>
        <input type="password" name="password_confirmation" placeholder="Confirmar senha" required><br>
        <button type="submit">Cadastrar</button>
    </form>

    <p>Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
</body>
```

Exemplo de logout:
```html
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Sair</button>
</form>
```