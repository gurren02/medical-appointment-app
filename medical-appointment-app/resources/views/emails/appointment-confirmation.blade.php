@extends('emails.layout')

@section('content')
    <h3>¡Cita Confirmada!</h3>
    <p>Hola <strong>{{ $patient->user->name }}</strong>,</p>
    <p>Tu cita médica ha sido registrada exitosamente.</p>
    
    <table>
        <tr><th>Doctor</th><td>{{ $doctor->user->name }}</td></tr>
        <tr><th>Especialidad</th><td>{{ $doctor->specialty ?? 'General' }}</td></tr>
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td></tr>
        <tr><th>Horario</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td></tr>
    </table>
    
    <p>Se adjunta el comprobante en PDF.</p>
    
    <p style="text-align: center;">
        <a href="{{ config('app.url') }}" class="button">Ir a Healthify</a>
    </p>
@endsection
