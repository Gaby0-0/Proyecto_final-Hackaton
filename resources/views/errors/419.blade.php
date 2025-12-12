@extends('errors::layout')

@section('code', '419')

@section('icon', 'fas fa-clock')

@section('title', 'Página Expirada')

@section('message')
    Tu sesión ha expirado debido a inactividad prolongada o porque dejaste la página abierta durante mucho tiempo. Por razones de seguridad, necesitas recargar la página y volver a intentarlo.
@endsection

@section('additional-info')
    <strong>¿Qué sucedió?</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>El token CSRF de tu formulario expiró (esto ocurre después de unas horas)</li>
        <li>Puede que hayas dejado el formulario abierto durante mucho tiempo</li>
        <li>Tu sesión se cerró automáticamente por seguridad</li>
        <li>La página fue recargada en otra pestaña o ventana</li>
    </ul>
    <p class="mt-3 text-sm">
        <i class="fas fa-shield-alt mr-1"></i>
        Esta medida de seguridad protege tu cuenta de ataques CSRF (Cross-Site Request Forgery).
    </p>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
