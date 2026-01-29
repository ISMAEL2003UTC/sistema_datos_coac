<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Datos Personales - COAC</title>
    
    <form action="{{ route('logout') }}" method="POST" style="position:absolute; top:20px; right:20px;">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
        </button>
    </form>
    

    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">
    <!-- jQuery  -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="{{ asset('scripts/scripts.js') }}"></script>

    

</head>

<style>
    .input-error {
    border: 1px solid #ef4444 !important;
    background: #fff5f5;
}

.text-error {
    color: #dc2626;
    font-size: 12px;
}

</style>
<body>
    <div class="container">
        <div class="header">
            <h1> Sistema de Gestión de Datos Personales</h1>
            <p>Cooperativa de Ahorro y Crédito - Protección de Datos</p>
        </div>
        
        <div class="nav-tabs">
            {{-- ADMIN --}}
    @if(auth()->user()->rol === 'admin')
        <button class="active" onclick="showSection('usuarios')">Usuarios</button>
        <button onclick="showSection('sujetos')">Sujetos</button>
        <button onclick="showSection('miembros')">Miembros</button>
        <button onclick="showSection('productos')">Productos</button>
        <button onclick="showSection('consentimientos')">Consentimientos</button>
        <button onclick="showSection('dsar')">DSAR</button>
        <button onclick="showSection('incidentes')">Incidentes</button>
        <button onclick="showSection('procesamiento')">Procesamiento</button>
        <button onclick="showSection('auditorias')">Auditorías</button>
        <button onclick="showSection('reportes')">Reportes</button>

    {{-- DPO --}}
    @elseif(auth()->user()->rol === 'dpo')
        <button class="active" onclick="showSection('sujetos')">Sujetos</button>
        <button onclick="showSection('consentimientos')">Consentimientos</button>
        <button onclick="showSection('dsar')">DSAR</button>
        <button onclick="showSection('incidentes')">Incidentes</button>
        <button onclick="showSection('procesamiento')">Procesamiento</button>
        <button onclick="showSection('reportes')">Reportes</button>

    {{-- AUDITOR --}}
    @elseif(auth()->user()->rol === 'auditor')
        <button class="active" onclick="showSection('auditorias')">Auditorías</button>
        <button onclick="showSection('reportes')">Reportes</button>

    {{-- OPERADOR --}}
    @elseif(auth()->user()->rol === 'operador')
        <button class="active" onclick="showSection('sujetos')">Sujetos</button>
        <button onclick="showSection('miembros')">Miembros</button>
        <button onclick="showSection('productos')">Productos</button>
        <button onclick="showSection('consentimientos')">Consentimientos</button>
    @endif
        </div>

        
        <!-- USUARIOS ----------------------------------------------------------------------------------------->
        @if(auth()->user()->rol === 'admin')
        <div id="usuarios" class="content-section active">
            <h2 class="section-title">Gestión de Usuarios del Sistema</h2>
  
            <form id="formUsuarios"  method="POST" action="{{ url('/usuarios') }}">
            <input type="hidden" id="id_usuario" name="id_usuario">

                @csrf
                <input type="hidden" name="_method" id="form_method" value="POST">
                <input type="hidden" name="id" id="usuario_id">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre Completo *</label>
                        <input type="text" name="nombre_completo" id="nombre_completo">

                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" >
                    </div>
                    <div class="form-group">
                        <label>Rol *</label>
                        <select name="rol" id="rol" >
                            <option value="">Seleccionar...</option>
                            <option value="admin">Administrador</option>
                            <option value="dpo">DPO (Oficial de Protección)</option>
                            <option value="auditor">Auditor</option>
                            <option value="operador">Operador</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Usuario</button>
            </form>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->nombre_completo }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ ucfirst($usuario->rol) }}</td>
                            <td>
                                @if($usuario->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>


                            <td>
                                <button class="btn btn-secondary" style="padding: 8px 15px;"
                                    onclick="editarUsuario({{ $usuario->id }}, 
                                    '{{ $usuario->nombre_completo }}', 
                                    '{{ $usuario->email }}', 
                                    '{{ $usuario->rol }}')">
                                    Editar
                                </button>

                                <form action="{{ route('usuarios.estado', $usuario->id) }}"
                                    method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning">
                                        Cambiar estado
                                    </button>
                                </form>
                                <form action="{{ route('usuarios.destroy', $usuario->id) }}"
                                    method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        
        <!-- SUJETOS DE DATOS -------------------------------------------------------------------------------->
        <div id="sujetos"  class="content-section">
            <h2 class="section-title">Registro de Sujetos de Datos</h2>
            
            <form id="formSujetos" method="POST" action="{{ route('sujetos.store') }}">
                
            @csrf
            <input type="hidden" name="_method" id="form_sujeto_method" value="POST">

            <input type="hidden" id="sujeto_id" name="sujeto_id">


                <div class="form-row">
                    <div class="form-group">
                        <label>Cédula/Identificación *</label>
                        <input type="text" name="cedula" >
                    </div>
                    <div class="form-group">
                        <label>Nombre Completo *</label>
                        <input type="text" name="nombre" >
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="telefono">
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion">
                    </div>
                    <div class="form-group">
                        <label>Tipo de Sujeto *</label>
                        <select name="tipo" >
                            <option value="">Seleccionar...</option>
                            <option value="cliente">Cliente</option>
                            <option value="empleado">Empleado</option>
                            <option value="proveedor">Proveedor</option>
                            <option value="tercero">Tercero</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Sujeto</button>
            </form>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sujetos as $sujeto)
                        <tr>
                            <td>{{ $sujeto->cedula }}</td>
                            <td>{{ $sujeto->nombre_completo }}</td>
                            <td>{{ $sujeto->email }}</td>
                            <td>{{ $sujeto->telefono }}</td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($sujeto->tipo) }}</span>
                            </td>
                            <td>
                                <button class="btn btn-secondary"
                                    onclick="editarSujeto(
                                        {{ $sujeto->id }},
                                        '{{ $sujeto->cedula }}',
                                        '{{ $sujeto->nombre_completo }}',
                                        '{{ $sujeto->email }}',
                                        '{{ $sujeto->telefono }}',
                                        '{{ $sujeto->direccion }}',
                                        '{{ $sujeto->tipo }}'
                                    )">
                                    Editar
                                </button>

                                <form action="{{ route('sujetos.destroy', $sujeto->id) }}"
                                    method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    
                                </form>

                            </td>
                        </tr>
                        @endforeach
                        </tbody>

                </table>
            </div>
        </div>
        <!-- MIEMBROS COAC -->
        <div id="miembros" class="content-section">
            <h2 class="section-title">Gestión de Miembros de la Cooperativa</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- FORMULARIO -->
            <form id="formMiembros" method="POST" action="{{ route('miembros.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form_miembro_method" value="POST">
                <input type="hidden" name="id" id="miembro_id">

                <div class="form-row">
                    <div class="form-group">
                        <label>Número de Socio *</label>
                        <input type="text" name="numero_socio" id="miembro_numero_socio" required>
                    </div>

                    <div class="form-group">
                        <label>Cédula *</label>
                        <input type="text" name="cedula" id="miembro_cedula" required>
                    </div>

                    <div class="form-group">
                        <label>Nombre Completo *</label>
                        <input type="text" name="nombre_completo" id="miembro_nombre_completo" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha de Ingreso *</label>
                        <input type="date" name="fecha_ingreso" id="miembro_fecha_ingreso" required>
                    </div>

                    <div class="form-group">
                        <label>Categoría *</label>
                        <select name="categoria" id="miembro_categoria" required>
                            <option value="">Seleccionar...</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="honorario">Honorario</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Aportación Inicial</label>
                        <input type="number" name="aportacion" id="miembro_aportacion" step="0.01" value="0">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="btnMiembroSubmit">Registrar Miembro</button>
                <button type="button" class="btn btn-secondary" onclick="resetFormularioMiembros()">Cancelar</button>
            </form>

            <!-- TABLA -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N° Socio</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Fecha Ingreso</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($miembros as $miembro)
                            <tr>
                                <td>{{ $miembro->numero_socio }}</td>
                                <td>{{ $miembro->cedula }}</td>
                                <td>{{ $miembro->nombre_completo }}</td>
                                <td>{{ \Carbon\Carbon::parse($miembro->fecha_ingreso)->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($miembro->categoria) }}</td>
                                <td>
                                    @if($miembro->estado === 'vigente')
                                        <span class="badge badge-success">Vigente</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- BOTÓN EDITAR CORREGIDO -->
                                    <button class="btn btn-secondary btn-editar-miembro" 
                                        data-id="{{ $miembro->id }}"
                                        data-numero="{{ $miembro->numero_socio }}"
                                        data-cedula="{{ $miembro->cedula }}"
                                        data-nombre="{{ $miembro->nombre_completo }}"
                                        data-fecha="{{ $miembro->fecha_ingreso }}"
                                        data-categoria="{{ $miembro->categoria }}"
                                        data-aportacion="{{ $miembro->aportacion ?? 0 }}">
                                        Editar
                                    </button>

                                    <form action="{{ route('miembros.estado', $miembro->id) }}"
                                        method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-warning">
                                            Cambiar estado
                                        </button>
                                    </form>

                                    <form action="{{ route('miembros.destroy', $miembro->id) }}"
                                        method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-danger"
                                            onclick="confirmarEliminacion(this)">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
       <!-- PRODUCTOS FINANCIEROS ------------------------------------------------------>
       <!-- PRODUCTOS FINANCIEROS ------------------------------------------------------>
        <div id="productos" class="content-section">
            <h2 class="section-title">Productos Financieros</h2>
            
            <form id="formProductos" method="POST" action="{{ route('productos.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form_producto_method" value="POST">
                <input type="hidden" name="id" id="producto_id">

                <div class="form-row">
                    <div class="form-group">
                        <label>Código Producto *</label>
                        <input type="text" name="codigo" id="producto_codigo" placeholder="Ej: CA-001, CR-2024">
                    
                    </div>
                    <div class="form-group">
                        <label>Nombre del Producto *</label>
                        <input type="text" name="nombre" id="producto_nombre" placeholder="Ej: Cuenta de Ahorro Juvenil, Crédito Personal Express">
                        
                    </div>
                    <div class="form-group">
                        <label>Tipo *</label>
                        <select name="tipo" id="producto_tipo">
                            <option value="">Seleccionar...</option>
                            <option value="ahorro">Cuenta de Ahorro</option>
                            <option value="credito">Crédito</option>
                            <option value="inversion">Inversión</option>
                            <option value="seguros">Seguros</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descripción *</label>
                    <textarea name="descripcion" id="producto_descripcion" rows="3" placeholder="Describa el producto, sus características principales, público objetivo, etc."></textarea>
                    <small class="form-text text-muted">Proporcione una descripción clara </small>
                </div>

                <div class="form-group">
                    <label>Datos Personales Procesados *</label>
                    <textarea name="datos_procesados" id="producto_datos" rows="4" placeholder="Ejemplo:
        - Nombre completo
        - Cédula de identidad
        - Fecha de nacimiento
        - Dirección
        - Teléfono
        - Correo electrónico

        Incluya todos los datos personales."></textarea>
                    <small class="form-text text-muted">Liste los datos personales que se recopilan</small>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </form>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Datos Procesados</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->codigo }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>
                                @if($producto->tipo === 'ahorro')
                                    <span class="badge badge-info">Cuenta de Ahorro</span>
                                @elseif($producto->tipo === 'credito')
                                    <span class="badge badge-success">Crédito</span>
                                @elseif($producto->tipo === 'inversion')
                                    <span class="badge badge-warning">Inversión</span>
                                @elseif($producto->tipo === 'seguros')
                                    <span class="badge badge-primary">Seguros</span>
                                @endif
                            </td>
                            <td>{{ $producto->descripcion ? Str::limit($producto->descripcion, 50) : 'N/A' }}</td>
                            <td>
                                @if($producto->datos_procesados)
                                    <button type="button" class="btn btn-sm btn-info" onclick="Swal.fire({
                                        title: 'Datos Procesados: {{ $producto->nombre }}',
                                        html: `<pre style='text-align: left; white-space: pre-wrap;'>{{ $producto->datos_procesados }}</pre>`,
                                        confirmButtonText: 'Cerrar'
                                    })">
                                        Ver Datos
                                    </button>
                                @else
                                    <span class="badge badge-danger">No definidos</span>
                                @endif
                            </td>
                            <td>
                                @if($producto->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-secondary" style="padding: 8px 15px;"
                                    onclick="editarProducto(
                                        {{ $producto->id }},
                                        '{{ $producto->codigo }}',
                                        '{{ $producto->nombre }}',
                                        '{{ $producto->tipo }}',
                                        `{{ str_replace('"', '&quot;', $producto->descripcion ?? '') }}`,
                                        `{{ str_replace('"', '&quot;', $producto->datos_procesados ?? '') }}`
                                    )">
                                    Editar
                                </button>

                                <form action="{{ route('productos.estado', $producto->id) }}"
                                    method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning">
                                        Cambiar estado
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay productos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <script>
        class GeneradorCodigos {
            constructor() {
                this.prefijo = 'B';
                this.digitos = 3;
                this.init();
            }
            
            init() {
                this.cargarCodigosExistentes();
                this.configurarEventos();
                this.generarSiNecesario();
            }
            
            cargarCodigosExistentes() {
                this.codigos = [];
                document.querySelectorAll('#productos table tbody tr td:first-child').forEach(td => {
                    const codigo = td.textContent.trim();
                    if (codigo) this.codigos.push(codigo);
                });
            }
            
            getSiguienteCodigo() {
                let siguienteNumero = 1;
                
                // Extraer números de los códigos existentes
                this.codigos.forEach(codigo => {
                    const match = codigo.match(new RegExp(`${this.prefijo}(\\d+)`, 'i'));
                    if (match) {
                        const num = parseInt(match[1]);
                        if (num >= siguienteNumero) siguienteNumero = num + 1;
                    }
                });
                
                // Verificar que no exista
                let codigoPropuesto;
                let intentos = 0;
                
                do {
                    codigoPropuesto = this.prefijo + siguienteNumero.toString().padStart(this.digitos, '0');
                    if (!this.codigos.includes(codigoPropuesto)) break;
                    siguienteNumero++;
                    intentos++;
                } while (intentos < 100);
                
                return codigoPropuesto;
            }
            
            generarSiNecesario() {
                const input = document.getElementById('producto_codigo');
                const enEdicion = document.getElementById('producto_id').value;
                
                if (!enEdicion && !input.value.trim()) {
                    input.value = this.getSiguienteCodigo();
                    this.mostrarNotificacion();
                }
            }
            
            mostrarNotificacion() {
                const notificado = sessionStorage.getItem('codigoAutoNotificado');
                if (!notificado) {
                    const codigo = document.getElementById('producto_codigo').value;
                    const notificacion = document.createElement('div');
                    notificacion.className = 'alert alert-info fade show';
                    notificacion.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        z-index: 9999;
                        max-width: 300px;
                        animation: slideIn 0.3s ease;
                    `;
                    notificacion.innerHTML = `
                        <strong>📝 Código generado:</strong> ${codigo}<br>
                        <small>Se genera automáticamente. Puedes editarlo.</small>
                        <button type="button" class="close" onclick="this.parentElement.remove()">
                            &times;
                        </button>
                    `;
                    document.body.appendChild(notificacion);
                    
                    setTimeout(() => notificacion.remove(), 5000);
                    sessionStorage.setItem('codigoAutoNotificado', 'true');
                }
            }
            
            configurarEventos() {
                // Cuando se muestre la sección productos
                const observer = new MutationObserver(() => {
                    if (document.getElementById('productos').classList.contains('active')) {
                        this.cargarCodigosExistentes();
                        this.generarSiNecesario();
                    }
                });
                
                observer.observe(document.getElementById('productos'), {
                    attributes: true,
                    attributeFilter: ['class']
                });
                
                // Cuando se haga clic en la pestaña
                document.querySelectorAll('.nav-tabs button').forEach(btn => {
                    if (btn.getAttribute('onclick')?.includes("'productos'")) {
                        btn.addEventListener('click', () => {
                            setTimeout(() => this.generarSiNecesario(), 150);
                        });
                    }
                });
                
                // Validar que el código no se repita
                document.getElementById('producto_codigo')?.addEventListener('blur', (e) => {
                    const codigo = e.target.value.trim().toUpperCase();
                    if (codigo && this.codigos.includes(codigo) && !document.getElementById('producto_id').value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Código duplicado',
                            text: `El código "${codigo}" ya existe. Se sugiere: ${this.getSiguienteCodigo()}`,
                            showCancelButton: true,
                            confirmButtonText: 'Usar sugerencia',
                            cancelButtonText: 'Mantener'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                e.target.value = this.getSiguienteCodigo();
                            }
                        });
                    }
                });
            }
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            new GeneradorCodigos();
        });

        // Añade este CSS para la animación
        const estilo = document.createElement('style');
        estilo.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .alert-info {
                background-color: #d1ecf1;
                border-color: #bee5eb;
                color: #0c5460;
            }
        `;
        document.head.appendChild(estilo);
        </script>
        
        <!-- CONSENTIMIENTOS ------------------------------------------------------------------------------------>
        <div id="consentimientos" class="content-section">
            <h2 class="section-title">Gestión de Consentimientos</h2>
            
            <form id="formConsentimientos" method="POST" action="{{ route('consentimientos.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form_consentimiento_method" value="POST">
                <input type="hidden" name="id" id="consentimiento_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Sujeto de Datos (ID) *</label>
                        <select name="sujeto_id" id="consentimiento_sujeto_id" required>
                            <option value="">Seleccionar...</option>
                            @foreach($sujetos as $sujeto)
                                <option value="{{ $sujeto->id }}">{{ $sujeto->cedula }} - {{ $sujeto->nombre_completo }}</option>
                            @endforeach
                        </select>
                        <span class="text-error" id="error-sujeto_id"></span>
                    </div>
                    <div class="form-group">
                        <label>Propósito del Tratamiento *</label>
                        <select name="proposito" id="consentimiento_proposito" required>
                            <option value="">Seleccionar...</option>
                            <option value="productos">Oferta de Productos</option>
                            <option value="marketing">Marketing</option>
                            <option value="analisis">Análisis Crediticio</option>
                            <option value="cumplimiento">Cumplimiento Legal</option>
                        </select>
                        <span class="text-error" id="error-proposito"></span>
                    </div>
                    <div class="form-group">
                        <label>Estado *</label>
                        <select name="estado" id="consentimiento_estado" required>
                            <option value="">Seleccionar...</option>
                            <option value="otorgado">Otorgado</option>
                            <option value="revocado">Revocado</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                        <span class="text-error" id="error-estado"></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha de Otorgamiento (Hoy) *</label>
                        <input type="date" name="fecha_otorgamiento" id="consentimiento_fecha_otorgamiento" readonly style="background-color: #f0f0f0; cursor: not-allowed;">
                        <small style="display: block; margin-top: 5px; color: #666;">Esta fecha se establece automáticamente con la fecha actual</small>
                        <span class="text-error" id="error-fecha_otorgamiento"></span>
                    </div>
                    <div class="form-group">
                        <label>Método de Obtención *</label>
                        <select name="metodo" id="consentimiento_metodo" required>
                            <option value="">Seleccionar...</option>
                            <option value="presencial">Presencial</option>
                            <option value="digital">Digital</option>
                            <option value="telefono">Telefónico</option>
                        </select>
                        <span class="text-error" id="error-metodo"></span>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Expiración *</label>
                        <input type="date" name="fecha_expiracion" id="consentimiento_fecha_expiracion" required>
                        <small style="display: block; margin-top: 5px; color: #666;">Se calculará automáticamente un año desde la fecha de otorgamiento</small>
                        <span class="text-error" id="error-fecha_expiracion"></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Consentimiento</button>
            </form>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sujeto</th>
                            <th>Propósito</th>
                            <th>Fecha Otorgamiento</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consentimientos as $consentimiento)
                        <tr>
                            <td>{{ $consentimiento->id }}</td>
                            <td>{{ $consentimiento->sujeto->cedula }} - {{ $consentimiento->sujeto->nombre_completo }}</td>
                            <td>
                                @if($consentimiento->proposito === 'productos')
                                    <span class="badge badge-info">Oferta de Productos</span>
                                @elseif($consentimiento->proposito === 'marketing')
                                    <span class="badge badge-success">Marketing</span>
                                @elseif($consentimiento->proposito === 'analisis')
                                    <span class="badge badge-warning">Análisis Crediticio</span>
                                @elseif($consentimiento->proposito === 'cumplimiento')
                                    <span class="badge badge-primary">Cumplimiento Legal</span>
                                @endif
                            </td>
                            <td>{{ $consentimiento->fecha_otorgamiento ? \Carbon\Carbon::parse($consentimiento->fecha_otorgamiento)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                @if($consentimiento->metodo === 'presencial')
                                    <span class="badge badge-success">Presencial</span>
                                @elseif($consentimiento->metodo === 'digital')
                                    <span class="badge badge-info">Digital</span>
                                @elseif($consentimiento->metodo === 'telefono')
                                    <span class="badge badge-warning">Telefónico</span>
                                @else
                                    <span class="badge badge-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($consentimiento->estado === 'otorgado')
                                    <span class="badge badge-success">Otorgado</span>
                                @elseif($consentimiento->estado === 'revocado')
                                    <span class="badge badge-danger">Revocado</span>
                                @elseif($consentimiento->estado === 'pendiente')
                                    <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-secondary" style="padding: 8px 15px;"
                                    onclick="editarConsentimiento(
                                        {{ $consentimiento->id }},
                                        {{ $consentimiento->sujeto_id }},
                                        '{{ $consentimiento->proposito }}',
                                        '{{ $consentimiento->estado }}',
                                        '{{ $consentimiento->fecha_otorgamiento }}',
                                        '{{ $consentimiento->metodo }}',
                                        '{{ $consentimiento->fecha_expiracion }}'
                                    )">
                                    Editar
                                </button>

                                <form action="{{ route('consentimientos.toggleActivo', $consentimiento->id) }}"
                                    method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit"
                                        class="btn {{ $consentimiento->activo ? 'btn-success' : 'btn-warning' }}"
                                        style="padding: 8px 15px;">
                                        {{ $consentimiento->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay consentimientos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
<!-- SOLICITUDES DSAR ---------------------------------------------------------------------------->
<div id="dsar" class="content-section">
    <h2 class="section-title">Solicitudes de Derechos (DSAR)</h2>
    <p style="margin-bottom: 20px; color: #666;">
        Gestión de solicitudes de Acceso, Rectificación, Cancelación y Oposición
    </p>


    {{-- FORMULARIO --}}
    <form id="formDSAR" method="POST" action= "/dsar">
        @csrf
        <input type="hidden" name="_method" id="form_dsar_method" value="POST">
        <input type="hidden" id="dsar_id">

        <div class="form-row">
            <div class="form-group">
                <label>Número de Solicitud *</label>
                <input type="text" name="numero_solicitud" id="dsar_numero" >
            </div>

            <div class="form-group">
                <label for="dsar_cedula">Sujeto de Datos *</label>

                <select name="cedula" id="dsar_cedula" class="select-sujeto" required>
                    <option value="">Seleccione un Sujeto de Datos</option>
                    @foreach ($sujetos as $s)
                        <option value="{{ $s->cedula }}">
                            {{ $s->cedula }} — {{ $s->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>



            <div class="form-group">
                <label>Tipo de Solicitud *</label>
                <select name="tipo" id="dsar_tipo" >
                    <option value="">Seleccionar...</option>
                    <option value="acceso">Acceso</option>
                    <option value="rectificacion">Rectificación</option>
                    <option value="cancelacion">Cancelación</option>
                    <option value="oposicion">Oposición</option>
                    <option value="portabilidad">Portabilidad</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" id="dsar_descripcion" rows="4" ></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Fecha de Solicitud *</label>
                <input type="date" name="fecha_solicitud" id="dsar_fecha_solicitud" >
            </div>

            <div class="form-group">
                <label>Plazo de Respuesta</label>
                <input type="date" name="fecha_limite" id="dsar_fecha_limite">
            </div>

            <div class="form-group">
                <label>Estado *</label>
                <select name="estado" id="dsar_estado" >
                    <option value="pendiente">Pendiente</option>
                    <option value="proceso">En Proceso</option>
                    <option value="completada">Completada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" id="btnDsarGuardar">
            Registrar Solicitud
        </button>

        <button type="button" class="btn btn-secondary"
                onclick="resetFormularioDSAR()" style="margin-left:10px;">
            Cancelar Edición
        </button>
    </form>

    {{-- TABLA --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>N° Solicitud</th>
                    <th>Solicitante</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Plazo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @forelse($dsars  as $d)
                <tr>
                    <td>{{ $d->numero_solicitud }}</td>
                    <td>{{ $d->sujeto?->cedula ?? 'N/A' }}</td>
                    <td>{{ ucfirst($d->tipo) }}</td>
                    <td>{{ $d->fecha_solicitud }}</td>
                    <td>{{ $d->fecha_limite ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-warning">
                            {{ ucfirst($d->estado) }}
                        </span>
                    </td>

                    <td style="display:flex; gap:8px; flex-wrap:wrap;">
                        {{-- EDITAR --}}
                        <button type="button"
                            class="btn btn-secondary btn-editar-dsar"
                            data-id="{{ $d->id }}"
                            data-numero="{{ $d->numero_solicitud }}"
                            data-cedula="{{ $d->sujeto?->cedula }}"
                            data-tipo="{{ $d->tipo }}"
                            data-descripcion="{{ $d->descripcion }}"
                            data-fecha="{{ $d->fecha_solicitud }}"
                            data-limite="{{ $d->fecha_limite }}"
                            data-estado="{{ $d->estado }}">
                        Editar
                    </button>


                        {{-- ELIMINAR --}}
                        <form method="POST"
                              action="{{ route('dsar.destroy', $d->id) }}"
                              onsubmit="return confirm('¿Eliminar solicitud?')">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger"
                                onclick="confirmarEliminarDSAR(this)">
                                Eliminar
                            </button>

                        </form>

                        {{-- CAMBIAR ESTADO --}}
                        <form method="POST"
                              action="{{ route('dsar.update', $d->id) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="numero_solicitud" value="{{ $d->numero_solicitud }}">
                            <input type="hidden" name="cedula" value="{{ $d->sujeto?->cedula }}">
                            <input type="hidden" name="tipo" value="{{ $d->tipo }}">
                            <input type="hidden" name="descripcion" value="{{ $d->descripcion }}">
                            <input type="hidden" name="fecha_solicitud" value="{{ $d->fecha_solicitud }}">
                            <input type="hidden" name="fecha_limite" value="{{ $d->fecha_limite }}">

                            <select name="estado">
                                <option value="pendiente" {{ $d->estado=='pendiente'?'selected':'' }}>Pendiente</option>
                                <option value="proceso" {{ $d->estado=='proceso'?'selected':'' }}>En Proceso</option>
                                <option value="completada" {{ $d->estado=='completada'?'selected':'' }}>Completada</option>
                                <option value="rechazada" {{ $d->estado=='rechazada'?'selected':'' }}>Rechazada</option>
                            </select>

                            <button type="submit" class="btn btn-warning">
                                Cambiar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">
                        No hay solicitudes DSAR registradas
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- INCIDENTES ------------------------------------------------------------------------------------->
<div id="incidentes" class="content-section">
    <h2 class="section-title">Registro de Incidentes de Seguridad</h2>

    <div class="alert alert-danger">
        <strong>⚠️ Atención:</strong> Registre todos los incidentes de seguridad que involucren datos personales
    </div>

    {{-- FORMULARIO --}}
    <form id="formIncidentes" method="POST" action="{{ route('incidentes.store') }}">
        @csrf
        <input type="hidden" name="_method" id="form_incidente_method" value="POST">
        <input type="hidden" id="incidente_id">

        <div class="form-row">
            <div class="form-group">
                <label>Código de Incidente *</label>
                <input type="text"
                    id="codigo"
                    value="Se generará automáticamente"
                    readonly
                    style="background:#f3f3f3; cursor:not-allowed;">
                @error('codigo')
                    <small class="text-error">El código es obligatorio</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Fecha del Incidente *</label>
                <input type="datetime-local"
                    name="fecha"
                    id="fecha"
                    value="{{ old('fecha') }}"
                    required
                    min="{{ now()->startOfMonth()->format('Y-m-d\T00:00') }}"
                    max="{{ now()->subDay()->format('Y-m-d\T23:59') }}"
                    class="{{ $errors->has('fecha') ? 'input-error' : '' }}">

                @error('fecha')
                    <small class="text-error">La fecha es obligatoria</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Severidad *</label>
                <select name="severidad"
                        id="severidad"
                        class="{{ $errors->has('severidad') ? 'input-error' : '' }}">
                    <option value="">Seleccionar...</option>
                    <option value="baja" {{ old('severidad')=='baja'?'selected':'' }}>Baja</option>
                    <option value="media" {{ old('severidad')=='media'?'selected':'' }}>Media</option>
                    <option value="alta" {{ old('severidad')=='alta'?'selected':'' }}>Alta</option>
                    <option value="critica" {{ old('severidad')=='critica'?'selected':'' }}>Crítica</option>
                </select>
                @error('severidad')
                    <small class="text-error">Seleccione una severidad</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Descripción del Incidente *</label>
            <textarea name="descripcion"
                      id="descripcion"
                      rows="4"
                      class="{{ $errors->has('descripcion') ? 'input-error' : '' }}">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <small class="text-error">La descripción es obligatoria</small>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tipo de Incidente *</label>
                <select name="tipo"
                        id="tipo"
                        class="{{ $errors->has('tipo') ? 'input-error' : '' }}">
                    <option value="">Seleccionar...</option>
                    <option value="fuga" {{ old('tipo')=='fuga'?'selected':'' }}>Fuga de Información</option>
                    <option value="acceso" {{ old('tipo')=='acceso'?'selected':'' }}>Acceso No Autorizado</option>
                    <option value="perdida" {{ old('tipo')=='perdida'?'selected':'' }}>Pérdida de Datos</option>
                    <option value="ransomware" {{ old('tipo')=='ransomware'?'selected':'' }}>Ransomware</option>
                    <option value="otro" {{ old('tipo')=='otro'?'selected':'' }}>Otro</option>
                </select>
                @error('tipo')
                    <small class="text-error">Seleccione el tipo de incidente</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Sujetos Afectados</label>
                <input type="number"
                       name="sujetos_afectados"
                       id="sujetos_afectados"
                       min="0"
                       value="{{ old('sujetos_afectados', 0) }}">
            </div>

            <div class="form-group">
                <label>Estado *</label>
                <select name="estado"
                        id="estado"
                        class="{{ $errors->has('estado') ? 'input-error' : '' }}">
                    <option value="abierto" {{ old('estado')=='abierto'?'selected':'' }}>Abierto</option>
                    <option value="investigacion" {{ old('estado')=='investigacion'?'selected':'' }}>En Investigación</option>
                    <option value="contenido" {{ old('estado')=='contenido'?'selected':'' }}>Contenido</option>
                    <option value="resuelto" {{ old('estado')=='resuelto'?'selected':'' }}>Resuelto</option>
                </select>
                @error('estado')
                    <small class="text-error">Seleccione un estado</small>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Registrar Incidente
        </button>
    </form>

    <!-- TABLA DE INCIDENTES (AHORA SÍ DENTRO DE #incidentes) -->
    <div class="table-container" style="margin-top:25px;">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Severidad</th>
                    <th>Afectados</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($incidentes as $incidente)
                <tr>
                    <td>{{ $incidente->codigo }}</td>
                    <td>{{ \Carbon\Carbon::parse($incidente->fecha)->format('d/m/Y H:i') }}</td>
                    <td>{{ $incidente->tipo }}</td>
                    <td>
                        <span class="badge 
                            @if($incidente->severidad=='baja') badge-success
                            @elseif($incidente->severidad=='media') badge-warning
                            @elseif($incidente->severidad=='alta') badge-danger
                            @else badge-dark @endif">
                            {{ ucfirst($incidente->severidad) }}
                        </span>
                    </td>
                    <td>{{ $incidente->sujetos_afectados ?? 0 }}</td>
                    <td>
                        <span class="badge 
                            @if($incidente->estado=='abierto') badge-info
                            @elseif($incidente->estado=='investigacion') badge-warning
                            @elseif($incidente->estado=='contenido') badge-secondary
                            @else badge-success @endif">
                            {{ ucfirst($incidente->estado) }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-secondary"
                            onclick="editarIncidente(
                                '{{ $incidente->id }}',
                                '{{ $incidente->codigo }}',
                                '{{ $incidente->fecha }}',
                                '{{ $incidente->severidad }}',
                                `{{ $incidente->descripcion }}`,
                                '{{ $incidente->tipo }}',
                                '{{ $incidente->sujetos_afectados }}',
                                '{{ $incidente->estado }}'
                            )">
                            Editar
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No hay incidentes registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function editarIncidente(id, codigo, fecha, severidad, descripcion, tipo, afectados, estado){
    Swal.fire({
                icon: 'info',
                title: 'Editar Incidente',
                text: 'El formulario ha entrado en modo edición'
            });
    document.getElementById('incidente_id').value = id;
    document.getElementById('codigo').value = codigo;
    document.getElementById('fecha').value = fecha.replace(' ', 'T'); // Para datetime-local
    document.getElementById('severidad').value = severidad;
    document.getElementById('descripcion').value = descripcion;
    document.getElementById('tipo').value = tipo;
    document.getElementById('sujetos_afectados').value = afectados;
    document.getElementById('estado').value = estado;

    document.getElementById('form_incidente_method').value = 'PUT';
    document.getElementById('formIncidentes').action = '/incidentes/' + id;
}

// SweetAlert para confirmar eliminación
function confirmarEliminacion(btn){
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            btn.closest('form').submit();
        }
    });
}@if(session('success'))
Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: '{{ session("success") }}',
    timer: 2500,
    showConfirmButton: false
});
@endif
</script>

        <!-- ACTIVIDADES DE PROCESAMIENTO ----------------------------------------------------------->
<div id="procesamiento" class="content-section">

    <h2 class="section-title">Registro de Actividades de Procesamiento</h2>
    <p style="margin-bottom: 20px; color: #666;">
        Inventario de todas las actividades de tratamiento de datos personales
    </p>

    <form method="POST" action="{{ route('actividades.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Código de Actividad *</label>
                <input type="text" name="codigo">
            </div>

            <div class="form-group">
                <label>Nombre de la Actividad *</label>
                <input type="text" name="nombre">
            </div>

            <div class="form-group">
                <label>Responsable *</label>
                <input type="text" name="responsable">
            </div>
        </div>

        <div class="form-group">
            <label>Finalidad del Tratamiento *</label>
            <textarea name="finalidad" rows="3"></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Base Legal *</label>
                <select name="base_legal">
                    <option value="">Seleccionar...</option>
                    <option value="consentimiento">Consentimiento</option>
                    <option value="contrato">Ejecución de Contrato</option>
                    <option value="legal">Obligación Legal</option>
                    <option value="interes">Interés Legítimo</option>
                </select>
            </div>

            <div class="form-group">
                <label>Categorías de Datos</label>
                <input type="text" name="categorias_datos">
            </div>

            <div class="form-group">
                <label>Plazo de Conservación</label>
                <input type="text" name="plazo_conservacion">
            </div>
        </div>

        <div class="form-group">
            <label>Medidas de Seguridad</label>
            <textarea name="medidas_seguridad" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Registrar Actividad
        </button>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Actividad</th>
                    <th>Responsable</th>
                    <th>Base Legal</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @foreach($procesamientos ?? [] as $act)
                <tr>
                    <td>{{ $act->codigo }}</td>
                    <td>{{ $act->nombre }}</td>
                    <td>{{ $act->responsable }}</td>
                    <td>{{ $act->base_legal }}</td>
                    <td>
                        <span class="badge badge-success">{{ $act->estado }}</span>
                    </td>
                    <td>
                        <button class="btn btn-secondary"
                                onclick="verActividad({{ $act->id }})">
                            Ver
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- PANEL VER ACTIVIDAD -->
        <div id="panelActividad" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:9999;">
            <div style="background:#fff; width:60%; margin:5% auto; padding:20px; border-radius:8px; max-height:80%; overflow:auto;">
                <h3>Detalle de la Actividad</h3>

                <p><strong>Código:</strong> <span id="v_codigo"></span></p>
                <p><strong>Nombre:</strong> <span id="v_nombre"></span></p>
                <p><strong>Responsable:</strong> <span id="v_responsable"></span></p>
                <p><strong>Finalidad:</strong> <span id="v_finalidad"></span></p>
                <p><strong>Base Legal:</strong> <span id="v_base_legal"></span></p>
                <p><strong>Categorías:</strong> <span id="v_categorias"></span></p>
                <p><strong>Plazo:</strong> <span id="v_plazo"></span></p>
                <p><strong>Medidas:</strong> <span id="v_medidas"></span></p>
                <p><strong>Estado:</strong> <span id="v_estado"></span></p>

                <button onclick="cerrarActividad()" class="btn btn-secondary">Cerrar</button>
            </div>
        </div>

    </div>
</div> 

<!-- AUDITORÍAS -->
<div id="auditorias" class="content-section">
    <h2 class="section-title">Gestión de Auditorías</h2>

    {{-- FORMULARIO --}}
    <form method="POST" action="{{ route('auditorias.store') }}" id="formAuditoria">
        @csrf

        {{-- Mostrar errores generales --}}
        @if($errors->any())
        <div class="alert alert-error" style="background: #fee; color: #c33; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            <strong>¡Errores encontrados!</strong>
            <ul style="margin: 5px 0 0 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label for="codigo_aud">Código de Auditoría *</label>
                <input type="text" id="codigo_aud" name="codigo_aud" 
                       value="{{ old('codigo_aud') }}"
                       maxlength="50"
                       required 
                       placeholder="Ej: AUD-001"
                       oninput="this.value = this.value.toUpperCase(); validarCampo(this, 'codigo')">
                <span id="error-codigo" class="error-message"></span>
                <small style="display: block; margin-top: 5px; color: #666;">
                    Formato: AUD-001, AUD-002, etc.
                </small>
            </div>

            <div class="form-group">
                <label for="tipo_aud">Tipo de Auditoría *</label>
                <select id="tipo_aud" name="tipo_aud" required onchange="validarCampo(this, 'tipo')">
                    <option value="">Seleccionar tipo...</option>
                    <option value="interna" {{ old('tipo_aud') == 'interna' ? 'selected' : '' }}>Interna</option>
                    <option value="externa" {{ old('tipo_aud') == 'externa' ? 'selected' : '' }}>Externa</option>
                    <option value="cumplimiento" {{ old('tipo_aud') == 'cumplimiento' ? 'selected' : '' }}>Cumplimiento</option>
                </select>
                <span id="error-tipo" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="auditor">Auditor Responsable *</label>
                <input type="text" id="auditor" name="auditor" 
                       value="{{ old('auditor') }}"
                       maxlength="150"
                       required
                       placeholder="Nombre completo del auditor"
                       oninput="validarCampo(this, 'auditor')">
                <span id="error-auditor" class="error-message"></span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio *</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" 
                       value="{{ old('fecha_inicio', date('Y-m-d')) }}"
                       required
                       onchange="validarFechas()">
                <span id="error-fecha-inicio" class="error-message"></span>
                <small style="display: block; margin-top: 5px; color: #666;">
                    No se permiten fechas anteriores a hoy
                </small>
            </div>

            <div class="form-group">
                <label for="fecha_fin">Fecha de Finalización *</label>
                <input type="date" id="fecha_fin" name="fecha_fin" 
                       value="{{ old('fecha_fin') }}"
                       required
                       onchange="validarFechas()">
                <span id="error-fecha-fin" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="estado_aud">Estado *</label>
                <select id="estado_aud" name="estado_aud" required onchange="validarCampo(this, 'estado')">
                    <option value="">Seleccionar estado...</option>
                    <option value="planificada" {{ old('estado_aud') == 'planificada' ? 'selected' : '' }}>Planificada</option>
                    <option value="proceso" {{ old('estado_aud') == 'proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="completada" {{ old('estado_aud') == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="revisada" {{ old('estado_aud') == 'revisada' ? 'selected' : '' }}>Revisada</option>
                </select>
                <span id="error-estado" class="error-message"></span>
            </div>
        </div>

        <div class="form-group">
            <label for="alcance">Alcance de la Auditoría *</label>
            <textarea id="alcance" name="alcance" 
                     rows="3" 
                     maxlength="500"
                     required
                     placeholder="Describa el alcance de la auditoría..."
                     oninput="validarTextarea(this, 'alcance')">{{ old('alcance') }}</textarea>
            <div class="char-counter">
                <span id="contador-alcance">0</span>/500 caracteres
            </div>
            <span id="error-alcance" class="error-message"></span>
        </div>

        <div class="form-group">
            <label for="hallazgos">Hallazgos y Observaciones *</label>
            <textarea id="hallazgos" name="hallazgos" 
                     rows="4" 
                     maxlength="1000"
                     required
                     placeholder="Describa los hallazgos y observaciones..."
                     oninput="validarTextarea(this, 'hallazgos')">{{ old('hallazgos') }}</textarea>
            <div class="char-counter">
                <span id="contador-hallazgos">0</span>/1000 caracteres
            </div>
            <span id="error-hallazgos" class="error-message"></span>
        </div>

        <button type="submit" class="btn btn-primary">
            ✅ Registrar Auditoría
        </button>
        
        <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
            🗑️ Limpiar Formulario
        </button>
    </form>

    {{-- TABLA --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Auditor</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditorias as $auditoria)
                <tr>
                    <td>{{ $auditoria->codigo }}</td>
                    <td>{{ ucfirst($auditoria->tipo) }}</td>
                    <td>{{ $auditoria->auditor }}</td>
                    <td>{{ \Carbon\Carbon::parse($auditoria->fecha_inicio)->format('d/m/Y') }}</td>
                    <td>{{ $auditoria->fecha_fin ? \Carbon\Carbon::parse($auditoria->fecha_fin)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($auditoria->estado == 'completada')
                            <span class="badge badge-success">Completada</span>
                        @elseif($auditoria->estado == 'proceso')
                            <span class="badge badge-warning">En Proceso</span>
                        @elseif($auditoria->estado == 'planificada')
                            <span class="badge badge-info">Planificada</span>
                        @elseif($auditoria->estado == 'revisada')
                            <span class="badge badge-primary">Revisada</span>
                        @else
                            <span class="badge badge-secondary">{{ $auditoria->estado }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('auditorias.show', $auditoria->id) }}"
                           class="btn btn-secondary"
                           style="padding: 8px 15px;">
                            👁️ Ver
                        </a>
                    </td>
                </tr>
                @endforeach

                @if($auditorias->isEmpty())
                <tr>
                    <td colspan="7" style="text-align:center;">
                        No hay auditorías registradas
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- Script para validación en el cliente --}}
<script>
// ========== CONFIGURACIÓN INICIAL ==========
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha mínima como hoy para fecha_inicio
    const fechaInicio = document.getElementById('fecha_inicio');
    const hoy = new Date().toISOString().split('T')[0];
    fechaInicio.min = hoy;
    
    // Si fecha_inicio está vacía, establecer hoy
    if (!fechaInicio.value) {
        fechaInicio.value = hoy;
    }
    
    // Inicializar contadores
    actualizarContador('alcance');
    actualizarContador('hallazgos');
});

// ========== FUNCIONES DE VALIDACIÓN ==========

// Validar campo genérico
function validarCampo(campo, tipo) {
    const errorSpan = document.getElementById(`error-${tipo}`);
    
    if (!campo.value.trim()) {
        mostrarError(campo, errorSpan, `El campo es obligatorio`);
        return false;
    }
    
    // Validaciones específicas por tipo
    switch(tipo) {
        case 'codigo':
            const regexCodigo = /^[A-Z0-9\-]+$/;
            if (!regexCodigo.test(campo.value)) {
                mostrarError(campo, errorSpan, 'Solo mayúsculas, números y guiones');
                return false;
            }
            break;
            
        case 'auditor':
            const regexAuditor = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
            if (!regexAuditor.test(campo.value)) {
                mostrarError(campo, errorSpan, 'Solo letras y espacios');
                return false;
            }
            if (campo.value.trim().length < 5) {
                mostrarError(campo, errorSpan, 'Mínimo 5 caracteres');
                return false;
            }
            break;
    }
    
    limpiarError(campo, errorSpan);
    return true;
}

// Validar textarea
function validarTextarea(textarea, tipo) {
    const errorSpan = document.getElementById(`error-${tipo}`);
    const maxLength = tipo === 'alcance' ? 500 : 1000;
    
    if (!textarea.value.trim()) {
        mostrarError(textarea, errorSpan, 'Este campo es obligatorio');
        return false;
    }
    
    if (textarea.value.trim().length < 10) {
        mostrarError(textarea, errorSpan, 'Mínimo 10 caracteres');
        return false;
    }
    
    if (textarea.value.length > maxLength) {
        mostrarError(textarea, errorSpan, `Máximo ${maxLength} caracteres`);
        return false;
    }
    
    limpiarError(textarea, errorSpan);
    actualizarContador(textarea.id);
    return true;
}

// Validar fechas
function validarFechas() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const errorInicio = document.getElementById('error-fecha-inicio');
    const errorFin = document.getElementById('error-fecha-fin');
    
    const hoy = new Date().toISOString().split('T')[0];
    let valido = true;
    
    // Validar fecha inicio
    if (!fechaInicio.value) {
        mostrarError(fechaInicio, errorInicio, 'La fecha de inicio es obligatoria');
        valido = false;
    } else if (fechaInicio.value < hoy) {
        mostrarError(fechaInicio, errorInicio, 'No puede seleccionar fechas anteriores a hoy');
        fechaInicio.value = hoy;
        fechaInicio.focus();
        valido = false;
    } else {
        limpiarError(fechaInicio, errorInicio);
    }
    
    // Validar fecha fin
    if (!fechaFin.value) {
        mostrarError(fechaFin, errorFin, 'La fecha de finalización es obligatoria');
        valido = false;
    } else if (fechaFin.value < fechaInicio.value) {
        mostrarError(fechaFin, errorFin, 'Debe ser igual o posterior a la fecha de inicio');
        fechaFin.value = fechaInicio.value;
        fechaFin.focus();
        valido = false;
    } else {
        limpiarError(fechaFin, errorFin);
    }
    
    return valido;
}

// Validar todo el formulario
function validarFormularioCompleto() {
    const campos = [
        {id: 'codigo_aud', tipo: 'codigo'},
        {id: 'tipo_aud', tipo: 'tipo'},
        {id: 'auditor', tipo: 'auditor'},
        {id: 'estado_aud', tipo: 'estado'}
    ];
    
    let valido = true;
    
    // Validar campos simples
    campos.forEach(campo => {
        const elemento = document.getElementById(campo.id);
        if (!validarCampo(elemento, campo.tipo)) {
            valido = false;
        }
    });
    
    // Validar textareas
    if (!validarTextarea(document.getElementById('alcance'), 'alcance')) {
        valido = false;
    }
    
    if (!validarTextarea(document.getElementById('hallazgos'), 'hallazgos')) {
        valido = false;
    }
    
    // Validar fechas
    if (!validarFechas()) {
        valido = false;
    }
    
    return valido;
}

// ========== FUNCIONES AUXILIARES ==========

// Mostrar error
function mostrarError(elemento, errorSpan, mensaje) {
    errorSpan.textContent = mensaje;
    errorSpan.style.color = '#e74c3c';
    errorSpan.style.fontSize = '12px';
    errorSpan.style.display = 'block';
    errorSpan.style.marginTop = '5px';
    elemento.style.borderColor = '#e74c3c';
}

// Limpiar error
function limpiarError(elemento, errorSpan) {
    errorSpan.textContent = '';
    elemento.style.borderColor = '';
}

// Actualizar contador de caracteres
function actualizarContador(textareaId) {
    const textarea = document.getElementById(textareaId);
    const contadorId = textareaId === 'alcance' ? 'contador-alcance' : 'contador-hallazgos';
    const contador = document.getElementById(contadorId);
    const maxLength = textareaId === 'alcance' ? 500 : 1000;
    
    if (contador) {
        contador.textContent = textarea.value.length;
        
        // Cambiar color según uso
        if (textarea.value.length > maxLength * 0.9) {
            contador.style.color = '#e74c3c';
        } else if (textarea.value.length > maxLength * 0.7) {
            contador.style.color = '#f39c12';
        } else {
            contador.style.color = '#666';
        }
    }
}

// Limpiar formulario
function limpiarFormulario() {
    if (confirm('¿Está seguro de limpiar todos los campos? Se perderán los datos no guardados.')) {
        document.getElementById('formAuditoria').reset();
        
        // Limpiar errores
        document.querySelectorAll('.error-message').forEach(span => {
            span.textContent = '';
        });
        
        // Restaurar estilos
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.style.borderColor = '';
        });
        
        // Restablecer fecha inicio a hoy
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_inicio').value = hoy;
        
        // Resetear contadores
        actualizarContador('alcance');
        actualizarContador('hallazgos');
        
        alert('Formulario limpiado correctamente');
    }
}

// ========== EVENTOS ==========

// Validación al enviar el formulario
document.getElementById('formAuditoria').addEventListener('submit', function(e) {
    if (!validarFormularioCompleto()) {
        e.preventDefault();
        
        // Encontrar primer error y enfocar
        const primerError = document.querySelector('.error-message:not(:empty)');
        if (primerError) {
            const campoId = primerError.id.replace('error-', '');
            const campo = document.getElementById(campoId);
            if (campo) {
                campo.focus();
                campo.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        alert('❌ Por favor complete todos los campos correctamente');
        return false;
    }
    
    // Confirmación final
    if (!confirm('¿Está seguro de registrar esta auditoría?')) {
        e.preventDefault();
        return false;
    }
    
    return true;
});

// Eventos en tiempo real
document.getElementById('codigo_aud').addEventListener('blur', function() {
    validarCampo(this, 'codigo');
});

document.getElementById('auditor').addEventListener('blur', function() {
    validarCampo(this, 'auditor');
});

document.getElementById('tipo_aud').addEventListener('blur', function() {
    validarCampo(this, 'tipo');
});

document.getElementById('estado_aud').addEventListener('blur', function() {
    validarCampo(this, 'estado');
});

// Prevenir edición manual de fechas
document.getElementById('fecha_inicio').addEventListener('keydown', function(e) {
    e.preventDefault();
});

document.getElementById('fecha_fin').addEventListener('keydown', function(e) {
    e.preventDefault();
});

// Actualizar contadores en tiempo real
document.getElementById('alcance').addEventListener('input', function() {
    actualizarContador('alcance');
});

document.getElementById('hallazgos').addEventListener('input', function() {
    actualizarContador('hallazgos');
});
</script>

<style>
.error-message {
    color: #e74c3c;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}

.char-counter {
    text-align: right;
    font-size: 11px;
    margin-top: 5px;
}

.badge-success { background: #2ecc71; color: white; padding: 3px 8px; border-radius: 3px; }
.badge-warning { background: #f39c12; color: white; padding: 3px 8px; border-radius: 3px; }
.badge-info { background: #3498db; color: white; padding: 3px 8px; border-radius: 3px; }
.badge-primary { background: #9b59b6; color: white; padding: 3px 8px; border-radius: 3px; }
.badge-secondary { background: #95a5a6; color: white; padding: 3px 8px; border-radius: 3px; }

.btn-primary {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.table-container {
    overflow-x: auto;
    margin-top: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th {
    background: #2c3e50;
    color: white;
    padding: 12px;
    text-align: left;
}

table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

table tr:hover {
    background: #f5f5f5;
}
</style>

<!-- ================= REPORTES ================= -->
<div id="reportes" class="content-section">
    <h2 class="section-title">Dashboard de Reportes y Estadísticas</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ $sujetos->count() }}</h3>
            <p>Total Sujetos Registrados</p>
        </div>
        <div class="stat-card">
            <h3>{{ $consentimientos->where('estado','otorgado')->count() }}</h3>
            <p>Consentimientos Activos</p>
        </div>
        <div class="stat-card">
            <h3>{{ $dsars->count() }}</h3>
            <p>Solicitudes DSAR</p>
        </div>
        <div class="stat-card">
            <h3>{{ $incidentes->where('estado','abierto')->count() }}</h3>
            <p>Incidentes Abiertos</p>
        </div>
    </div>

    <!-- ===== DSAR PIE CHART ===== -->
    <div class="chart-container" style="max-width:480px; margin:30px auto;">
        <h3 style="color:#667eea; text-align:center;">
            📊 Distribución de Solicitudes DSAR
        </h3>
        <canvas id="dsarChart" height="110"></canvas>
    </div>

    <!-- ===== INCIDENTES BAR CHART ===== -->
    <div class="chart-container" style="max-width:600px; margin:40px auto;">
        <h3 style="color:#ef4444; text-align:center;">
            ⚠️ Incidentes por Severidad
        </h3>
        <canvas id="incidentesChart" height="100"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ================= DSAR PIE ================= */
const dsarLabels = [
@foreach($dsars->groupBy('tipo') as $tipo => $grupo)
    "{{ ucfirst($tipo) }}",
@endforeach
];

const dsarData = [
@foreach($dsars->groupBy('tipo') as $tipo => $grupo)
    {{ $grupo->count() }},
@endforeach
];

new Chart(document.getElementById('dsarChart'), {
    type: 'doughnut',
    data: {
        labels: dsarLabels,
        datasets: [{
            data: dsarData,
            backgroundColor: [
                '#6366f1',
                '#22c55e',
                '#f59e0b',
                '#ef4444',
                '#06b6d4'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 30,
                    padding: 15,
                    usePointStyle: true
                }
            }
        }
    }
});

/* ================= INCIDENTES BAR ================= */
const incidenteLabels = [
@foreach($incidentes->groupBy('severidad') as $sev => $grupo)
    "{{ ucfirst($sev) }}",
@endforeach
];

const incidenteData = [
@foreach($incidentes->groupBy('severidad') as $sev => $grupo)
    {{ $grupo->count() }},
@endforeach
];

new Chart(document.getElementById('incidentesChart'), {
    type: 'bar',
    data: {
        labels: incidenteLabels,
        datasets: [{
            data: incidenteData,
            backgroundColor: [
                '#22c55e',
                '#0ea5e9',
                '#f59e0b',
                '#ef4444'
            ],
            borderRadius: 20,
            maxBarThickness: 80,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>



<script>
    const csrf = "{{ csrf_token() }}";
</script>

<script>
    const actividades = @json($procesamientos);

    function verActividad(id) {
        const act = actividades.find(a => a.id === id);
        if (!act) return;

        document.getElementById('v_codigo').innerText = act.codigo;
        document.getElementById('v_nombre').innerText = act.nombre;
        document.getElementById('v_responsable').innerText = act.responsable;
        document.getElementById('v_finalidad').innerText = act.finalidad;
        document.getElementById('v_base_legal').innerText = act.base_legal;
        document.getElementById('v_categorias').innerText = act.categorias_datos;
        document.getElementById('v_plazo').innerText = act.plazo_conservacion;
        document.getElementById('v_medidas').innerText = act.medidas_seguridad;
        document.getElementById('v_estado').innerText = act.estado;

        document.getElementById('panelActividad').style.display = 'block';
    }

    function cerrarActividad() {
        document.getElementById('panelActividad').style.display = 'none';
    }
</script>

@if(session('swal'))
<script>
    Swal.fire({
        icon: "{{ session('swal.icon') }}",
        title: "{{ session('swal.title') }}",
        text: "{{ session('swal.text') }}",
        confirmButtonText: 'Aceptar'
    });
</script>
@endif
