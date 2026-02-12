<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema</title>
</head>
<body>

    @yield('content')

    {{-- JS para una sola pestaña tipo WhatsApp --}}
    <script>
    const channel = new BroadcastChannel('app_single_tab');
    let isActiveTab = false;

    channel.postMessage({ type: 'CHECK_ACTIVE' });

    channel.onmessage = (event) => {
        if (event.data.type === 'ACTIVE_TAB') {
            showTabModal();
        }

        if (event.data.type === 'TAKE_OVER') {
            isActiveTab = false;
            alert('Esta sesión fue abierta en otra pestaña.');
            window.location.reload();
        }
    };

    setTimeout(() => {
        if (!isActiveTab) {
            isActiveTab = true;
            channel.postMessage({ type: 'ACTIVE_TAB' });
        }
    }, 300);

    function showTabModal() {
        if (document.getElementById('tab-modal')) return;

        const modal = document.createElement('div');
        modal.id = 'tab-modal';
        modal.style = `
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;

        modal.innerHTML = `
            <div style="background:#fff;padding:25px;border-radius:10px;max-width:400px;text-align:center">
                <p><strong>La aplicación está abierta en otra pestaña.</strong></p>
                <p>¿Quieres usarla aquí?</p>
                <button id="closeTab">Cerrar</button>
                <button id="useHere" style="margin-left:10px">Usar aquí</button>
            </div>
        `;

        document.body.appendChild(modal);

        document.getElementById('closeTab').onclick = () => {
            window.close();
        };

        document.getElementById('useHere').onclick = () => {
            channel.postMessage({ type: 'TAKE_OVER' });
            isActiveTab = true;
            modal.remove();
        };
    }
    </script>

</body>
</html>
