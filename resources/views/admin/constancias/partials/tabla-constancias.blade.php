<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Usuario
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Equipo
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tipo
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Fecha de Emisión
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acciones
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($constancias as $constancia)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold mr-3">
                        {{ substr($constancia->usuario->name ?? 'N', 0, 2) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $constancia->usuario->name ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $constancia->usuario->email ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">
                    {{ $constancia->equipo->nombre ?? 'N/A' }}
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    @if($constancia->tipo === 'ganador')
                        bg-yellow-100 text-yellow-800
                    @elseif($constancia->tipo === 'juez')
                        bg-purple-100 text-purple-800
                    @else
                        bg-green-100 text-green-800
                    @endif">
                    @if($constancia->tipo === 'ganador')
                        <i class="fas fa-trophy mr-1"></i>
                    @elseif($constancia->tipo === 'juez')
                        <i class="fas fa-award mr-1"></i>
                    @else
                        <i class="fas fa-user-check mr-1"></i>
                    @endif
                    {{ ucfirst($constancia->tipo) }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>
                {{ \Carbon\Carbon::parse($constancia->fecha_emision)->format('d/m/Y') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.constancias.show', $constancia->id) }}"
                       class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form action="{{ route('admin.constancias.destroy', $constancia->id) }}"
                          method="POST"
                          class="inline"
                          onsubmit="return confirm('¿Está seguro de eliminar esta constancia?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No hay constancias de este tipo</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
