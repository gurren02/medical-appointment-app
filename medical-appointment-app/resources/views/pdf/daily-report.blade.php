<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Diario de Citas</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { color: #2563eb; font-size: 20px; margin: 0 0 3px; }
        .header p { color: #666; font-size: 12px; margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #2563eb; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; color: #fff; }
        .badge-1 { background: #2563eb; }
        .badge-2 { background: #16a34a; }
        .badge-3 { background: #dc2626; }
        .summary { margin-top: 20px; padding: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 5px; }
        .summary span { font-weight: bold; }
        .footer { text-align: center; color: #999; font-size: 9px; margin-top: 25px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Reporte Diario de Citas - {{ $date }}</p>
        @if($doctor)
            <p>Doctor: {{ $doctor->user->name }} ({{ $doctor->specialty ?? 'General' }})</p>
        @else
            <p>Reporte General - Administración</p>
        @endif
    </div>

    @if($appointments->isEmpty())
        <p style="text-align:center;color:#999;margin-top:30px;">No hay citas programadas para hoy.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    @if($adminView)<th>Doctor</th>@endif
                    <th>Horario</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    @if($adminView)<th>Motivo</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $appointment->patient->user->name }}</td>
                    @if($adminView)<td>{{ $appointment->doctor->user->name }}</td>@endif
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                    <td>{{ $appointment->duration }} min</td>
                    <td><span class="badge badge-{{ $appointment->status }}">{{ $appointment->status_label }}</span></td>
                    @if($adminView)<td>{{ $appointment->reason ?? '-' }}</td>@endif
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <p>Total de citas: <span>{{ $appointments->count() }}</span></p>
            <p>Programadas: <span>{{ $appointments->where('status', 1)->count() }}</span></p>
            <p>Completadas: <span>{{ $appointments->where('status', 2)->count() }}</span></p>
            <p>Canceladas: <span>{{ $appointments->where('status', 3)->count() }}</span></p>
        </div>
    @endif

    <div class="footer">
        <p>Reporte generado automáticamente por {{ config('app.name') }} el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
