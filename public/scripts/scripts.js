// ========== FUNCIONES GLOBALES ==========

// MOSTRAR SECCIÓN (ÚNICA DEFINICIÓN)
function showSection(sectionId) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Mostrar la sección seleccionada
    document.getElementById(sectionId).classList.add('active');
    
    // Actualizar botones activos
    const buttons = document.querySelectorAll('.nav-tabs button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });
    event.target.classList.add('active');
}

// CONFIRMAR ELIMINACIÓN
function confirmarEliminacion(boton) {
    event.preventDefault();
    const form = boton.closest('form');

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// ========== FUNCIONES PARA USUARIOS ==========

// RESET FORMULARIO USUARIOS
function resetFormularioUsuarios() {
    const form = $('#formUsuarios');
    form.attr('action', '/usuarios');
    $('#form_method').val('POST');
    $('#usuario_id').val('');
    $('#nombre_completo').val('');
    $('input[name="email"]').val('');
    $('#rol').val('');
    form.find('button[type="submit"]').text('Agregar Usuario');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

// EDITAR USUARIO
function editarUsuario(id, nombre, email, rol) {
    Swal.fire({
        icon: 'info',
        title: 'Editar usuario',
        text: 'El formulario ha entrado en modo edición',
        timer: 2000
    });

    $('#usuario_id').val(id);
    $('#nombre_completo').val(nombre);
    $('input[name="email"]').val(email);
    $('#rol').val(rol);
    $('#form_method').val('PUT');
    $('#formUsuarios').attr('action', '/usuarios/' + id);
    $('#formUsuarios button[type="submit"]').text('Actualizar Usuario');
}

// ========== FUNCIONES PARA SUJETOS ==========

// EDITAR SUJETO
function editarSujeto(id, cedula, nombre, email, telefono, direccion, tipo) {
    Swal.fire({
        icon: 'info',
        title: 'Editar Sujeto de datos',
        text: 'El formulario ha entrado en modo edición',
        timer: 2000
    });
    
    const form = document.getElementById('formSujetos');
    form.querySelector('input[name="cedula"]').value = cedula;
    form.querySelector('input[name="nombre"]').value = nombre;
    form.querySelector('input[name="email"]').value = email;
    form.querySelector('input[name="telefono"]').value = telefono;
    form.querySelector('input[name="direccion"]').value = direccion;
    form.querySelector('select[name="tipo"]').value = tipo;
    document.getElementById('sujeto_id').value = id;
    document.getElementById('form_sujeto_method').value = 'PUT';
    form.action = `/sujetos/${id}`;
    form.querySelector('button[type="submit"]').innerText = 'Actualizar Sujeto';
}

// ========== FUNCIONES PARA PRODUCTOS FINANCIEROS ==========

// RESET FORMULARIO PRODUCTOS
function resetFormularioProductos() {
    const form = $('#formProductos');
    form.attr('action', '/productos');
    $('#form_producto_method').val('POST');
    $('#producto_id').val('');
    $('#producto_codigo').val('');
    $('#producto_nombre').val('');
    $('#producto_tipo').val('');
    $('#producto_descripcion').val('');
    $('#producto_datos').val('');
    form.find('button[type="submit"]').text('Guardar Producto');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

// EDITAR PRODUCTO
function editarProducto(id, codigo, nombre, tipo, descripcion, datos) {
    Swal.fire({
        icon: 'info',
        title: 'Editar producto financiero',
        text: 'El formulario ha entrado en modo edición',
        timer: 2000
    });

    $('#producto_id').val(id);
    $('#producto_codigo').val(codigo);
    $('#producto_nombre').val(nombre);
    $('#producto_tipo').val(tipo);
    $('#producto_descripcion').val(descripcion || '');
    $('#producto_datos').val(datos || '');
    $('#form_producto_method').val('PUT');
    $('#formProductos').attr('action', '/productos/' + id);
    $('#formProductos button[type="submit"]').text('Actualizar Producto');
}

// ========== FUNCIONES PARA CONSENTIMIENTOS ==========

// RESET FORMULARIO CONSENTIMIENTOS
function resetFormularioConsentimientos() {
    const form = $('#formConsentimientos');
    form.attr('action', '/consentimientos');
    $('#form_consentimiento_method').val('POST');
    $('#consentimiento_id').val('');
    $('#consentimiento_sujeto_id').val('');
    $('#consentimiento_proposito').val('');
    $('#consentimiento_estado').val('');
    $('#consentimiento_fecha_otorgamiento').val('');
    $('#consentimiento_metodo').val('');
    $('#consentimiento_fecha_expiracion').val('');
    form.find('button[type="submit"]').text('Registrar Consentimiento');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

// EDITAR CONSENTIMIENTO
function editarConsentimiento(id, sujeto_id, proposito, estado, fecha_otorgamiento, metodo, fecha_expiracion) {
    Swal.fire({
        icon: 'info',
        title: 'Editar consentimiento',
        text: 'El formulario ha entrado en modo edición',
        timer: 2000
    });

    $('#consentimiento_id').val(id);
    $('#consentimiento_sujeto_id').val(sujeto_id);
    $('#consentimiento_proposito').val(proposito);
    $('#consentimiento_estado').val(estado);
    $('#consentimiento_fecha_otorgamiento').val(fecha_otorgamiento);
    $('#consentimiento_metodo').val(metodo);
    $('#consentimiento_fecha_expiracion').val(fecha_expiracion);
    $('#form_consentimiento_method').val('PUT');
    $('#formConsentimientos').attr('action', '/consentimientos/' + id);
    $('#formConsentimientos button[type="submit"]').text('Actualizar Consentimiento');
}

// ========== FUNCIONES PARA MIEMBROS (¡CORREGIDAS!) ==========

// EDITAR MIEMBRO - VERSIÓN SIMPLIFICADA Y FUNCIONAL
function editarMiembro(id, numero_socio, cedula, nombre, fecha_ingreso, categoria, aportacion) {
    console.log('Editando miembro:', {id, numero_socio, cedula, nombre, fecha_ingreso, categoria, aportacion});
    
    // Mostrar notificación
    Swal.fire({
        icon: 'info',
        title: 'Editando Miembro',
        text: `ID: ${id} - ${nombre}`,
        timer: 2000,
        showConfirmButton: false
    });
    
    // 1. Llenar formulario con datos
    $('#miembro_id').val(id);
    $('#miembro_numero_socio').val(numero_socio);
    $('#miembro_cedula').val(cedula);
    $('#miembro_nombre_completo').val(nombre);
    $('#miembro_fecha_ingreso').val(fecha_ingreso);
    $('#miembro_categoria').val(categoria);
    $('#miembro_aportacion').val(parseFloat(aportacion) || 0);
    
    // 2. Cambiar método y URL
    $('#form_miembro_method').val('PUT');
    $('#formMiembros').attr('action', '/miembros/' + id);
    
    // 3. Cambiar texto del botón
    $('#btnMiembroSubmit').text('Actualizar Miembro');
    
    // 4. Mostrar sección de miembros
    showSection('miembros');
    
    console.log('Formulario listo para editar:', {
        id: $('#miembro_id').val(),
        action: $('#formMiembros').attr('action'),
        method: $('#form_miembro_method').val()
    });
}

// RESET FORMULARIO MIEMBROS
function resetFormularioMiembros() {
    $('#formMiembros')[0].reset();
    $('#form_miembro_method').val('POST');
    $('#miembro_id').val('');
    $('#formMiembros').attr('action', '/miembros');
    $('#btnMiembroSubmit').text('Registrar Miembro');
    $('#miembro_aportacion').val(0);
    
    Swal.fire({
        icon: 'info',
        title: 'Formulario reiniciado',
        text: 'Listo para registrar nuevo miembro',
        timer: 2000,
        showConfirmButton: false
    });
}

// ========== FUNCIONES PARA DSAR ==========

// EDITAR DSAR
window.editarDSAR = function (id, numero, cedula, tipo, descripcion, fechaSolicitud, fechaLimite, estado) {
    Swal.fire({
        icon: 'info',
        title: 'Editar solicitud',
        text: 'El formulario ha entrado en modo edición',
        timer: 2000,
        showConfirmButton: false
    });

    const form = document.getElementById('formDSAR');
    form.action = `/dsar/${id}`;
    document.getElementById('form_dsar_method').value = 'PUT';
    document.getElementById('dsar_numero').value = numero;
    document.getElementById('dsar_cedula').value = cedula;
    document.getElementById('dsar_tipo').value = tipo;
    document.getElementById('dsar_descripcion').value = descripcion;
    document.getElementById('dsar_fecha_solicitud').value = fechaSolicitud;
    document.getElementById('dsar_fecha_limite').value = fechaLimite ?? '';
    document.getElementById('dsar_estado').value = estado;
    document.getElementById('btnDsarGuardar').innerText = 'Actualizar Solicitud';
};

// RESET FORMULARIO DSAR
window.resetFormularioDSAR = function () {
    const form = document.getElementById('formDSAR');
    form.reset();
    form.action = "/dsar";
    document.getElementById('form_dsar_method').value = 'POST';
    document.getElementById('btnDsarGuardar').innerText = 'Registrar Solicitud';
};

// CONFIRMAR ELIMINAR DSAR
function confirmarEliminarDSAR(btn) {
    Swal.fire({
        title: '¿Eliminar solicitud?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            btn.closest('form').submit();
        }
    });
}

// ========== VALIDACIONES JQUERY ==========

$(document).ready(function () {
    console.log('Script cargado correctamente');
    
    // Event listener para botones editar miembros
    $(document).on('click', '.btn-editar-miembro', function() {
        const btn = $(this);
        console.log('Botón editar clickeado:', btn.data());
        
        editarMiembro(
            btn.data('id'),
            btn.data('numero'),
            btn.data('cedula'),
            btn.data('nombre'),
            btn.data('fecha'),
            btn.data('categoria'),
            btn.data('aportacion')
        );
    });
    
    // Event listener para botones editar DSAR
    $(document).on('click', '.btn-editar-dsar', function() {
        const b = $(this);
        editarDSAR(
            b.data('id'),
            b.data('numero'),
            b.data('cedula'),
            b.data('tipo'),
            b.data('descripcion'),
            b.data('fecha'),
            b.data('limite'),
            b.data('estado')
        );
    });

    // Métodos personalizados
    $.validator.addMethod("soloLetras", function (value, element) {
        return this.optional(element) || /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value);
    }, "Solo se permiten letras");

    $.validator.addMethod("soloNumeros", function (value, element) {
        return this.optional(element) || /^[0-9]+$/.test(value);
    }, "Solo se permiten números");

    // VALIDACIÓN USUARIOS
    $("#formUsuarios").validate({
    rules: {
        nombre_completo: { 
            required: true, 
            minlength: 3, 
            soloLetras: true 
        },
        email: { 
            required: true, 
            email: true,
            remote: {
                url: "/verificar-email",
                type: "get",
                data: {
                    email: function () {
                        return $("input[name='email']").val();
                    },
                    id: function () {
                        return $("#id_usuario").val(); // 👈 input hidden
                    }
                }
            }
        },
        rol: { required: true }
    },
    messages: {
        nombre_completo: {
            required: "El nombre es obligatorio",
            minlength: "Debe tener al menos 3 caracteres",
            soloLetras: "Solo se permiten letras"
        },
        email: {
            required: "El correo es obligatorio",
            email: "Correo no válido",
            remote: "Este correo ya está registrado"
        },
        rol: { required: "El rol es obligatorio" }
    },
    errorElement: "div",
    errorClass: "invalid-feedback",
    highlight: function (element) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
        $(element).removeClass("is-invalid");
    }

    
});

function editarUsuario(id, nombre, email, rol) {
    $("#id_usuario").val(id);  
    $("input[name='nombre_completo']").val(nombre);
    $("input[name='email']").val(email);
    $("select[name='rol']").val(rol);
}


    // VALIDACIÓN SUJETOS
    $("#formSujetos").validate({
        rules: {
            cedula: { required: true, minlength: 10, maxlength: 10, soloNumeros: true },
            nombre: { required: true, minlength: 3, soloLetras: true },
            email: { email: true },
            telefono: { soloNumeros: true, minlength: 7, maxlength: 10 },
            tipo: { required: true }
        },
        messages: {
            cedula: {
                required: "La cédula es obligatoria",
                minlength: "Debe tener 10 dígitos",
                maxlength: "Debe tener 10 dígitos",
                soloNumeros: "Solo se permiten números"
            },
            nombre: {
                required: "El nombre es obligatorio",
                minlength: "Debe tener al menos 3 caracteres",
                soloLetras: "Solo se permiten letras"
            },
            email: { email: "Correo no válido" },
            telefono: {
                soloNumeros: "Solo se permiten números",
                minlength: "Mínimo 7 dígitos",
                maxlength: "Máximo 10 dígitos"
            },
            tipo: { required: "Seleccione el tipo de sujeto" }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) { $(element).addClass("is-invalid"); },
        unhighlight: function (element) { $(element).removeClass("is-invalid"); }
    });

    // VALIDACIÓN PRODUCTOS FINANCIEROS
    $("#formProductos").validate({
        rules: {
            codigo: { required: true, minlength: 2 },
            nombre: { required: true, minlength: 3 },
            tipo: { required: true }
        },
        messages: {
            codigo: {
                required: "El código del producto es obligatorio",
                minlength: "Debe tener al menos 2 caracteres"
            },
            nombre: {
                required: "El nombre del producto es obligatorio",
                minlength: "Debe tener al menos 3 caracteres"
            },
            tipo: { required: "Seleccione el tipo de producto" }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) { $(element).addClass("is-invalid"); },
        unhighlight: function (element) { $(element).removeClass("is-invalid"); }
    });

    // VALIDACIÓN MIEMBROS
    $("#formMiembros").validate({
        rules: {
            numero_socio: { 
                required: true, 
                minlength: 3,
                digits: true
            },
            cedula: { 
                required: true, 
                minlength: 10, 
                maxlength: 10,
                digits: true
            },
            nombre_completo: { 
                required: true, 
                minlength: 3
            },
            fecha_ingreso: { 
                required: true, 
                date: true 
            },
            categoria: { 
                required: true 
            },
            aportacion: { 
                number: true, 
                min: 0 
            }
        },
        messages: {
            numero_socio: {
                required: "Número de socio requerido",
                minlength: "Mínimo 3 dígitos",
                digits: "Solo números permitidos"
            },
            cedula: {
                required: "Cédula requerida",
                minlength: "Debe tener 10 dígitos",
                maxlength: "Debe tener 10 dígitos",
                digits: "Solo números permitidos"
            },
            nombre_completo: {
                required: "Nombre completo requerido",
                minlength: "Mínimo 3 caracteres"
            },
            fecha_ingreso: "Fecha de ingreso requerida",
            categoria: "Seleccione una categoría",
            aportacion: {
                number: "Ingrese un número válido",
                min: "No puede ser negativo"
            }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function(element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function(form) {
            const isEdit = $('#form_miembro_method').val() === 'PUT';
            const title = isEdit ? '¿Actualizar miembro?' : '¿Registrar nuevo miembro?';
            
            Swal.fire({
                title: title,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            
            return false;
        }
    });

    // VALIDACIÓN CONSENTIMIENTOS
    $("#formConsentimientos").validate({
        rules: {
            sujeto_id: { required: true },
            proposito: { required: true },
            estado: { required: true },
            fecha_otorgamiento: { date: true },
            metodo: {},
            fecha_expiracion: { date: true }
        },
        messages: {
            sujeto_id: { required: "Seleccione un sujeto de datos" },
            proposito: { required: "Seleccione el propósito del tratamiento" },
            estado: { required: "Seleccione el estado del consentimiento" },
            fecha_otorgamiento: { date: "Ingrese una fecha válida" },
            fecha_expiracion: { date: "Ingrese una fecha válida" }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) { $(element).addClass("is-invalid"); },
        unhighlight: function (element) { $(element).removeClass("is-invalid"); }
    });

    // VALIDACIÓN DSAR
    $("#formDSAR").validate({
        rules: {
            numero_solicitud: {
                required: true,
                minlength: 5,
                soloNumeros: true
            },
            cedula: {
                required: true
            },
            tipo: {
                required: true
            },
            descripcion: {
                required: true,
                minlength: 10,
                soloLetras: true
            },
            fecha_solicitud: {
                required: true,
                date: true
            },
            estado: {
                required: true
            }
        },
        messages: {
            numero_solicitud: {
                required: "El número de solicitud es obligatorio",
                minlength: "Debe tener al menos 5 dígitos",
                soloNumeros: "Solo se permiten números"
            },
            cedula: {
                required: "Seleccione un sujeto de datos"
            },
            tipo: {
                required: "Seleccione el tipo de solicitud"
            },
            descripcion: {
                required: "La descripción es obligatoria",
                minlength: "Debe tener al menos 10 caracteres",
                soloLetras: "Solo se permiten letras y espacios"
            },
            fecha_solicitud: {
                required: "La fecha de solicitud es obligatoria",
                date: "Fecha no válida"
            },
            estado: {
                required: "Seleccione el estado"
            }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        }
    });
});

// ========== DEPURACIÓN ==========

// Función para probar si todo funciona
function testFuncionalidades() {
    console.log('=== TEST FUNCIONALIDADES ===');
    console.log('showSection:', typeof showSection);
    console.log('editarMiembro:', typeof editarMiembro);
    console.log('resetFormularioMiembros:', typeof resetFormularioMiembros);
    console.log('=== FIN TEST ===');
}

// Ejecutar test cuando cargue la página
setTimeout(testFuncionalidades, 1000);