@extends('emails.layout')

@section('content')
    <h3>Reporte Diario de Citas</h3>
    <p>Hola <strong>{{ $recipientName }}</strong>,</p>
    <p>Adjuntamos el resumen de citas para hoy.</p>
    
    <ul>
        <li>Total: {{ $appointments->count() }}</li>
        <li>Programadas: {{ $appointments->where('status', 1)->count() }}</li>
    </ul>
    
    <p>El detalle completo está en el PDF adjunto.</p>
@endsection
