@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto" x-data="solicitudForm()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nueva Solicitud de Presupuesto</h1>
            <p class="text-gray-600 mt-1">Generar solicitud de cotización para proveedor</p>
        </div>
        <a href="{{ route('solicitudes-presupuesto.index') }}" class="btn-secondary">Volver</a>
    </div>

    {{-- Mostrar errores de validación si existen --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p class="font-bold">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('solicitudes-presupuesto.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Vinculación con Pedido de Cliente (Si existe) --}}
        @if(isset($pedidoCliente) && $pedidoCliente)
            <input type="hidden" name="pedido_cliente_id" value="{{ $pedidoCliente->id }}">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex justify-between items-center">
                <div>
                    <span class="text-blue-800 font-bold">Vinculado al Pedido de Cliente:</span>
                    <span class="ml-2 text-blue-600">{{ $pedidoCliente->numero }}</span>
                    <span class="text-sm text-gray-500 ml-2">({{ $pedidoCliente->cliente_nombre }})</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="form-group">
                <label class="form-label form-label-required">Proveedor</label>
                <select name="proveedor_id" class="form-select" required>
                    <option value="">Seleccionar proveedor...</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                            {{ $proveedor->razon_social }} - {{ $proveedor->ruc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label form-label-required">Fecha de Solicitud</label>
                <input type="date" name="fecha_solicitud" 
                       value="{{ old('fecha_solicitud', date('Y-m-d')) }}" 
                       class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha Esperada de Respuesta</label>
                <input type="date" name="fecha_vencimiento" 
                       value="{{ old('fecha_vencimiento') }}" 
                       class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Observaciones</label>
                <input type="text" name="notas" 
                       value="{{ old('notas') }}" 
                       class="form-input" placeholder="Comentarios para el proveedor...">
            </div>
        </div>

        <div class="form-section bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Productos a Cotizar</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 uppercase">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Producto</th>
                            <th class="px-4 py-3 text-right w-32">Cantidad</th>
                            <th class="px-4 py-3 w-16 text-center rounded-tr-lg"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-2">
                                    <select :name="'items['+index+'][producto_id]'" x-model="item.producto_id" class="form-select w-full" required>
                                        <option value="">Seleccionar producto...</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}">
                                                {{ $producto->codigo }} - {{ $producto->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" :name="'items['+index+'][cantidad]'" x-model="item.cantidad" 
                                           step="0.01" min="0.01" class="form-input text-right" required>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                            class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <button type="button" @click="addItem()" class="mt-4 flex items-center text-blue-600 hover:text-blue-800 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Producto
            </button>
        </div>

        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('solicitudes-presupuesto.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium shadow-sm">
                Guardar Solicitud
            </button>
        </div>
    </form>
</div>

<script>
    function solicitudForm() {
        return {
            items: [
                // Si el controlador mandara items predefinidos (desde el pedido cliente),
                // podrías cargarlos aquí con @json($itemsPredefinidos ?? [])
                { producto_id: '', cantidad: 1 } 
            ],

            addItem() {
                this.items.push({ producto_id: '', cantidad: 1 });
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            }
        }
    }
</script>
@endsection