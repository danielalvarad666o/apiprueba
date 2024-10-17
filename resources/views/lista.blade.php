<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="/docs/4.0/assets/brand/bootstrap-solid.svg" width="30" height="30" alt="">
    </a>

    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cerrarSesion') }}">Cerrar sesión</a>
        </li>
    </ul>
</nav>

<br>

<!-- Alerta -->
@if (session('alerta'))
    <div class="alert alert-info">
        {{ session('alerta') }}
    </div>
@endif

<!-- Lista de usuarios -->
<div class="container">
    <h2>Usuarios</h2>
    <ul class="list-group">
        @if(isset($usuarios) && !empty($usuarios))
            @foreach($usuarios as $usuario)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $usuario['name'] }} ({{ $usuario['email'] }})
                    
                    <span>
                        <!-- Botón de editar -->
                        <a href="{{ url('editar/'.$usuario['id']) }}" class="btn btn-primary btn-sm">Editar</a>
                        <!-- Botón de eliminar -->
                        <form action="{{ url('eliminar/'.$usuario['id']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </span>
                </li>
            @endforeach
        @else
            <li class="list-group-item">No se encontraron usuarios</li>
        @endif
    </ul>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
