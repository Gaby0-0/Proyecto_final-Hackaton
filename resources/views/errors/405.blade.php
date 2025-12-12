@extends('errors::layout')

@section('code', '405')

@section('icon', 'fas fa-ban')

@section('title', 'Método No Permitido')

@section('message')
    El método HTTP utilizado para acceder a este recurso no está permitido. La operación que intentaste realizar no es compatible con este endpoint.
@endsection

@section('additional-info')
    <strong>Detalles técnicos:</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>El servidor reconoce el método de solicitud, pero este está deshabilitado</li>
        <li>Puede que hayas usado GET cuando se requiere POST, o viceversa</li>
        <li>El formulario o enlace puede estar configurado incorrectamente</li>
        <li>Puede haber un problema con el método HTTP en la ruta</li>
    </ul>
    <p class="mt-3 text-sm">
        <i class="fas fa-code mr-1"></i>
        Este es generalmente un error de programación. Contacta al desarrollador si persiste.
    </p>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
