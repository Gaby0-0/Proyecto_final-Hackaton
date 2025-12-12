@extends('errors::layout')

@section('code', '429')

@section('icon', 'fas fa-exclamation-triangle')

@section('title', 'Demasiadas Solicitudes')

@section('message')
    Has realizado demasiadas solicitudes en un período corto de tiempo. Por favor, espera un momento antes de volver a intentarlo. Esta limitación ayuda a mantener el sistema estable y seguro para todos los usuarios.
@endsection

@section('additional-info')
    <strong>¿Por qué está limitado?</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>Has excedido el límite de solicitudes permitidas por minuto</li>
        <li>Esto puede ocurrir al recargar la página repetidamente</li>
        <li>También puede suceder al enviar múltiples formularios seguidos</li>
        <li>Es una medida de protección contra abuso y ataques automatizados</li>
    </ul>
    <p class="mt-3 text-sm bg-yellow-50 border border-yellow-200 rounded p-2">
        <i class="fas fa-hourglass-half mr-1"></i>
        <strong>Sugerencia:</strong> Espera unos 60 segundos antes de intentar nuevamente.
    </p>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
