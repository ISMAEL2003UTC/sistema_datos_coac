<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Auditoría - {{ $auditoria->codigo }}</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            position: relative;
        }
        
        /* Botón de retorno mejorado */
        .btn-volver {
            position: fixed;
            top: 30px;
            left: 30px;
            background: var(--primary-color);
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(44, 62, 80, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            border: 2px solid rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            font-size: 15px;
        }
        
        .btn-volver:hover {
            background: #1a252f;
            transform: translateY(-3px) translateX(2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            gap: 16px;
        }
        
        .btn-volver:active {
            transform: translateY(-1px);
            transition: transform 0.1s;
        }
        
        .btn-volver span {
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        
        .btn-volver:hover span {
            transform: translateX(-3px);
        }
        
        .document-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .document-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a252f 100%);
            color: white;
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
        }
        
        .document-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--success-color));
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            position: relative;
            z-index: 1;
        }
        
        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .header-left .document-subtitle {
            font-size: 17px;
            opacity: 0.9;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.85);
        }
        
        .document-code {
            background: rgba(255, 255, 255, 0.15);
            padding: 15px 30px;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-size: 20px;
            letter-spacing: 1.5px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .document-body {
            padding: 50px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--light-bg);
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        
        .section-title::before {
            content: "";
            width: 8px;
            height: 8px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: inline-block;
        }
        
        .section-title::after {
            content: "";
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--secondary-color), transparent);
            border-radius: 3px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
            margin-bottom: 50px;
        }
        
        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            padding: 28px;
            border-left: 5px solid var(--secondary-color);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(transparent, rgba(52, 152, 219, 0.03));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .info-card:hover::before {
            opacity: 1;
        }
        
        .info-label {
            display: block;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .info-value {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.5;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            gap: 8px;
        }
        
        .status-badge::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .status-completada {
            background: rgba(39, 174, 96, 0.12);
            color: var(--success-color);
            border: 2px solid rgba(39, 174, 96, 0.2);
        }
        
        .status-completada::before {
            background: var(--success-color);
        }
        
        .status-proceso {
            background: rgba(243, 156, 18, 0.12);
            color: var(--warning-color);
            border: 2px solid rgba(243, 156, 18, 0.2);
        }
        
        .status-proceso::before {
            background: var(--warning-color);
        }
        
        .status-planificada {
            background: rgba(52, 152, 219, 0.12);
            color: var(--secondary-color);
            border: 2px solid rgba(52, 152, 219, 0.2);
        }
        
        .status-planificada::before {
            background: var(--secondary-color);
        }
        
        .status-revisada {
            background: rgba(155, 89, 182, 0.12);
            color: #9b59b6;
            border: 2px solid rgba(155, 89, 182, 0.2);
        }
        
        .status-revisada::before {
            background: #9b59b6;
        }
        
        .hallazgos-section {
            margin-top: 50px;
        }
        
        .hallazgos-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 35px;
            border: 2px solid var(--border-color);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .hallazgos-content {
            white-space: pre-wrap;
            line-height: 1.8;
            color: var(--text-primary);
            font-size: 16px;
            text-align: justify;
        }
        
        .timeline-section {
            margin-top: 50px;
        }
        
        .timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
            padding: 30px 0;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--border-color), transparent);
            transform: translateY(-50%);
        }
        
        .timeline-item {
            position: relative;
            text-align: center;
            z-index: 1;
            flex: 1;
            max-width: 200px;
        }
        
        .timeline-dot {
            width: 20px;
            height: 20px;
            background: var(--secondary-color);
            border-radius: 50%;
            margin: 0 auto 15px;
            border: 4px solid white;
            box-shadow: 0 0 0 4px var(--border-color), 0 4px 15px rgba(0, 0, 0, 0.15);
            position: relative;
            transition: all 0.3s ease;
        }
        
        .timeline-item:hover .timeline-dot {
            transform: scale(1.2);
            box-shadow: 0 0 0 4px var(--secondary-color), 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        .timeline-date {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.4;
        }
        
        .timeline-date small {
            display: block;
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
            margin-top: 5px;
        }
        
        .document-footer {
            background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
            padding: 30px 50px;
            border-top: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--text-secondary);
        }
        
        .footer-info {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .footer-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        
        .footer-item span:first-child {
            font-size: 18px;
            opacity: 0.8;
        }
        
        @media (max-width: 992px) {
            .document-container {
                margin: 20px;
                border-radius: 16px;
            }
            
            .btn-volver {
                top: 15px;
                left: 15px;
                padding: 12px 20px;
                font-size: 14px;
            }
            
            .document-header,
            .document-body {
                padding: 35px;
            }
            
            .header-left h1 {
                font-size: 24px;
            }
            
            .document-code {
                font-size: 18px;
                padding: 12px 24px;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }
            
            .btn-volver {
                position: absolute;
                top: 15px;
                left: 50%;
                transform: translateX(-50%);
                width: auto;
                padding: 10px 20px;
                font-size: 14px;
                border-radius: 8px;
            }
            
            .document-container {
                margin-top: 70px;
            }
            
            .document-header,
            .document-body {
                padding: 25px;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .timeline {
                flex-direction: column;
                align-items: flex-start;
                gap: 30px;
                padding: 20px 0;
            }
            
            .timeline::before {
                left: 10px;
                right: auto;
                width: 3px;
                height: calc(100% - 40px);
                top: 0;
                transform: none;
            }
            
            .timeline-item {
                display: flex;
                align-items: center;
                gap: 20px;
                text-align: left;
                max-width: 100%;
                width: 100%;
            }
            
            .timeline-dot {
                margin: 0;
                flex-shrink: 0;
            }
            
            .document-footer {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                padding: 25px;
            }
            
            .footer-info {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .document-header,
            .document-body {
                padding: 20px;
            }
            
            .header-left h1 {
                font-size: 22px;
            }
            
            .info-card {
                padding: 20px;
            }
            
            .hallazgos-container {
                padding: 20px;
            }
            
            .section-title {
                font-size: 18px;
            }
        }
        
        /* Animación para las tarjetas */
        .info-card {
            animation: slideUp 0.5s ease-out forwards;
            opacity: 0;
            animation-delay: calc(var(--card-index) * 0.1s);
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Estilos para mejorar la impresión */
        @media print {
            .btn-volver {
                display: none;
            }
            
            body {
                background: white;
                padding: 0;
            }
            
            .document-container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
            
            .info-card:hover {
                transform: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <!-- Botón de retorno mejorado -->
    <a href="/auditorias" class="btn-volver">
        <span>←</span>
        Volver al Listado
    </a>
    
    <!-- Versión para Laravel -->
    <!-- <a href="{{ route('auditorias.index') }}" class="btn-volver"> -->
    
    <div class="document-container">
        <header class="document-header">
            <div class="header-content">
                <div class="header-left">
                    <h1>INFORME DE AUDITORÍA</h1>
                    <div class="document-subtitle">Documento oficial del Sistema de Gestión de Calidad</div>
                </div>
                <div class="document-code">{{ $auditoria->codigo }}</div>
            </div>
        </header>
        
        <main class="document-body">
            <div class="info-grid">
                <div class="info-card" style="--card-index: 1;">
                    <span class="info-label">Tipo de Auditoría</span>
                    <div class="info-value">{{ ucfirst($auditoria->tipo) }}</div>
                </div>
                
                <div class="info-card" style="--card-index: 2;">
                    <span class="info-label">Auditor Responsable</span>
                    <div class="info-value">{{ $auditoria->auditor }}</div>
                </div>
                
                <div class="info-card" style="--card-index: 3;">
                    <span class="info-label">Estado Actual</span>
                    <div class="info-value">
                        @if($auditoria->estado == 'completada')
                            <span class="status-badge status-completada">Completada</span>
                        @elseif($auditoria->estado == 'proceso')
                            <span class="status-badge status-proceso">En Proceso</span>
                        @elseif($auditoria->estado == 'planificada')
                            <span class="status-badge status-planificada">Planificada</span>
                        @elseif($auditoria->estado == 'revisada')
                            <span class="status-badge status-revisada">Revisada</span>
                        @else
                            <span class="status-badge status-planificada">{{ ucfirst($auditoria->estado) }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="info-card" style="--card-index: 4;">
                    <span class="info-label">Ámbito de la Auditoría</span>
                    <div class="info-value">{{ $auditoria->alcance ?? 'No especificado' }}</div>
                </div>
            </div>
            
            <div class="section-title">Cronología</div>
            <div class="timeline-section">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-date">
                            {{ date('d/m/Y', strtotime($auditoria->fecha_inicio)) }}
                            <small>Fecha de Inicio</small>
                        </div>
                    </div>
                    
                    @if($auditoria->fecha_fin)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-date">
                            {{ date('d/m/Y', strtotime($auditoria->fecha_fin)) }}
                            <small>Fecha de Finalización</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($auditoria->hallazgos)
            <div class="section-title">Hallazgos y Observaciones</div>
            <div class="hallazgos-section">
                <div class="hallazgos-container">
                    <div class="hallazgos-content">{{ $auditoria->hallazgos }}</div>
                </div>
            </div>
            @endif
        </main>
        
        <footer class="document-footer">
            <div class="footer-info">
                <div class="footer-item">
                    <span>📄</span>
                    <span>Documento generado: {{ date('d/m/Y H:i', strtotime($auditoria->updated_at)) }}</span>
                </div>
                <div class="footer-item">
                    <span>🆔</span>
                    <span>ID de Registro: AUD-{{ str_pad($auditoria->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
            <div class="footer-item">
                <span>🔒</span>
                <span>Documento confidencial - Uso interno exclusivo</span>
            </div>
        </footer>
    </div>
    
    <script>
        // Añadir interacción adicional para mejorar la experiencia de usuario
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar efecto de scroll suave a los enlaces
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Agregar animación al cargar las tarjetas
            const cards = document.querySelectorAll('.info-card');
            cards.forEach((card, index) => {
                card.style.setProperty('--card-index', index);
            });
            
            // Efecto de impresión mejorado
            const printButton = document.createElement('button');
            printButton.innerHTML = '🖨 Imprimir';
            printButton.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: var(--primary-color);
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                font-weight: 600;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 1000;
                transition: all 0.3s ease;
            `;
            printButton.onmouseover = () => printButton.style.transform = 'translateY(-2px)';
            printButton.onmouseout = () => printButton.style.transform = 'translateY(0)';
            printButton.onclick = () => window.print();
            document.body.appendChild(printButton);
            
            // Responsive para el botón de impresión
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 768) {
                    printButton.style.bottom = '20px';
                    printButton.style.right = '20px';
                    printButton.style.padding = '10px 20px';
                } else {
                    printButton.style.bottom = '30px';
                    printButton.style.right = '30px';
                    printButton.style.padding = '12px 24px';
                }
            });
        });
    </script>
</body>
</html>