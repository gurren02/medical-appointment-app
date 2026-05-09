<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .header h1 { color: #2563eb; font-size: 22px; margin: 0 0 5px; }
        .header p { color: #666; font-size: 13px; margin: 0; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .info-table td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        .info-table td:first-child { font-weight: bold; width: 140px; color: #555; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; color: #fff; font-weight: bold; }
        .badge-programado { background: #2563eb; }
        .badge-completado { background: #16a34a; }
        .badge-cancelado { background: #dc2626; }
        .footer { text-align: center; color: #999; font-size: 10px; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Comprobante de Cita Médica</p>
        <p>Generado: {{ $date }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td>Folio de Cita</td>
            <td>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>Paciente</td>
            <td>{{ $patient->user->name }}</td>
        </tr>
        <tr>
            <td>Doctor</td>
            <td>{{ $doctor->user->name }}</td>
        </tr>
        <tr>
            <td>Especialidad</td>
            <td>{{ $doctor->specialty ?? 'General' }}</td>
        </tr>
        <tr>
            <td>Fecha</td>
            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Horario</td>
            <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
        </tr>
        <tr>
            <td>Duración</td>
            <td>{{ $appointment->duration }} minutos</td>
        </tr>
        @if($appointment->reason)
        <tr>
            <td>Motivo</td>
            <td>{{ $appointment->reason }}</td>
        </tr>
        @endif
        <tr>
            <td>Estado</td>
            <td><span class="badge badge-{{ strtolower($appointment->status_label) }}">{{ $appointment->status_label }}</span></td>
        </tr>
    </table>

    <div class="footer">
        <p>Este comprobante es generado automáticamente por {{ config('app.name') }}.</p>
        <p>Presentar el día de su consulta.</p>
    </div>
</body>
</html>
