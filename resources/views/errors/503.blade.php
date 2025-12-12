@extends('errors::layout')

@section('code', '503')

@section('icon', 'fas fa-tools')

@section('title', 'Servicio No Disponible')

@section('message')
    El sistema está temporalmente fuera de servicio debido a mantenimiento programado o sobrecarga del servidor. Volveremos a estar en línea muy pronto.
@endsection

@section('additional-info')
    <strong>Información importante:</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>Estamos realizando mantenimiento para mejorar tu experiencia</li>
        <li>El servicio volverá a estar disponible en breve</li>
        <li>Todos tus datos están seguros y no se perderán</li>
        <li>No es necesario que hagas nada, solo espera un momento</li>
    </ul>
    <div class="mt-3 bg-blue-50 border border-blue-200 rounded p-3">
        <p class="text-sm font-semibold text-blue-800">
            <i class="fas fa-info-circle mr-1"></i>
            Mantenimiento en Progreso
        </p>
        <p class="text-xs text-blue-700 mt-1">
            Estamos trabajando para brindarte un mejor servicio. Gracias por tu paciencia.
        </p>
    </div>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
