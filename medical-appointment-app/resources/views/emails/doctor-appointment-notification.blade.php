@extends('emails.layout')

@section('content')
    <h3>Nueva Cita Asignada</h3>
    <p>Hola <strong>Dr. {{ $doctor->user->name }}</strong>,</p>
    <p>Se te ha asignado una nueva cita médica.</p>
    
    <table>
        <tr><th>Paciente</th><td>{{ $patient->user->name }}</td></tr>
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td></tr>
        <tr><th>Horario</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td></tr>
    </table>
    
    <p>Se adjunta el detalle en PDF.</p>
@endsection
