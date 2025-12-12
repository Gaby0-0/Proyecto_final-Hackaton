@extends('errors::layout')

@section('code', '500')

@section('icon', 'fas fa-server')

@section('title', 'Error Interno del Servidor')

@section('message')
    Lo sentimos, algo salió mal en nuestro servidor. Nuestro equipo ha sido notificado automáticamente y está trabajando para resolver el problema lo antes posible.
@endsection

@section('additional-info')
    <strong>¿Qué puedes hacer?</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>Intenta recargar la página en unos momentos</li>
        <li>Verifica que los datos que ingresaste sean correctos</li>
        <li>Si el problema persiste, contacta al administrador del sistema</li>
        <li>Proporciona la hora exacta en que ocurrió el error para ayudar en el diagnóstico</li>
    </ul>
    <div class="mt-3 bg-red-50 border border-red-200 rounded p-3">
        <p class="text-sm font-semibold text-red-800">
            <i class="fas fa-bug mr-1"></i>
            Este es un error del servidor, no es tu culpa
        </p>
        <p class="text-xs text-red-700 mt-1">
            El equipo de desarrollo ha sido notificado y está investigando el problema.
        </p>
    </div>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
