@extends('emails.layout')

@section('content')
    <h3>Recordatorio de Cita</h3>
    <p>Hola <strong>{{ $patient->user->name }}</strong>,</p>
    <p>Te recordamos que tienes una cita programada para <strong>mañana</strong>.</p>
    
    <table>
        <tr><th>Doctor</th><td>{{ $doctor->user->name }}</td></tr>
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td></tr>
        <tr><th>Horario</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td></tr>
    </table>
    
    <p>Por favor, llega 10 minutos antes de tu cita.</p>
    
    <p style="text-align: center;">
        <a href="{{ config('app.url') }}" class="button">Ver detalles en Healthify</a>
    </p>
@endsection
