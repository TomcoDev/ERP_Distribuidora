@extends('layouts.app')

@section('content')

{{-- 
    BLOQUE PHP: Preparamos los datos aquí para evitar el ParseError en el script.
    Si viene de un pedido de cliente, cargamos sus items; si no, una fila vacía.
--}}
@php
    $itemsIniciales = [['producto_id' => '', 'cantidad_solicitada' => 1, 'precio_unitario' => 0, 'subtotal' => 0]];

    if (isset($pedidoCliente) && $pedidoCliente) {
        $itemsIniciales = $pedidoCliente->items->map(function($i) {
            return [
                'producto_id' => $i->producto_id,
                'cantidad_solicitada' => $i->cantidad,
                'precio_unitario' => 0, // El precio de compra se define al seleccionar el producto o manualmente
                'subtotal' => 0
            ];
        });
    }
@endphp

<div class="max-w-5xl mx-auto" x-data="ordenForm()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nueva Orden de Compra</h1>
            @if(isset($pedidoCliente) && $pedidoCliente)
                <p class="text-gray-600 mt-1">
                    Vinculada al Pedido: <span class="font-bold">{{ $pedidoCliente->numero }}</span> 
                    - {{ $pedidoCliente->cliente_nombre }}
                </p>
            @endif
        </div>
        <a href="{{ route('ordenes-compra.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">Volver</a>
    </div>

    {{-- Mostrar errores de validación si existen --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ordenes-compra.store') }}" method="POST" class="space-y-6">
        @csrf
        
        @if(isset($pedidoCliente) && $pedidoCliente)
            <input type="hidden" name="pedido_cliente_id" value="{{ $pedidoCliente->id }}">
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Datos del Proveedor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Proveedor *</label>
                    <input type="text" name="proveedor_nombre" value="{{ old('proveedor_nombre') }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RUC</label>
                    <input type="text" name="proveedor_ruc" value="{{ old('proveedor_ruc') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="proveedor_telefono" value="{{ old('proveedor_telefono') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="proveedor_email" value="{{ old('proveedor_email') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" name="proveedor_direccion" value="{{ old('proveedor_direccion') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Fechas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Orden *</label>
                    <input type="date" name="fecha_orden" value="{{ old('fecha_orden', date('Y-m-d')) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Entrega Esperada</label>
                    <input type="date" name="fecha_entrega_esperada" value="{{ old('fecha_entrega_esperada') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Productos a Comprar</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Producto</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider w-28">Cantidad</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider w-40">Precio Unit.</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider w-40">Subtotal</th>
                            <th class="px-4 py-3 w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-3">
                                    <select x-model="item.producto_id" :name="'items['+index+'][producto_id]'" required
                                            @change="updatePrecio(index, $event)" 
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <option value="">Seleccionar...</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_compra }}">
                                                {{ $producto->codigo }} - {{ $producto->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" x-model="item.cantidad_solicitada" :name="'items['+index+'][cantidad_solicitada]'"
                                           step="0.001" min="0.001" required @input="calcularSubtotal(index)"
                                           class="w-full rounded-lg border-gray-300 shadow-sm text-right focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" x-model="item.precio_unitario" :name="'items['+index+'][precio_unitario]'"
                                           min="0" required @input="calcularSubtotal(index)"
                                           class="w-full rounded-lg border-gray-300 shadow-sm text-right focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900 text-sm" x-text="formatGs(item.subtotal)"></td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                            class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <button type="button" @click="addItem()" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Producto
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas / Observaciones</label>
                <textarea name="notas" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Detalles adicionales para la orden...">{{ old('notas') }}</textarea>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Resumen Financiero</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Subtotal:</span>
                        <span class="font-bold text-gray-800 text-base" x-text="formatGs(subtotal)"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Descuento:</span>
                        <input type="number" name="descuento" x-model="descuento" min="0" @input="calcularTotal()"
                               class="w-32 rounded-lg border-gray-300 shadow-sm text-right focus:border-blue-500 focus:ring-blue-500 p-1">
                    </div>
                    <div class="border-t pt-3 flex justify-between items-center">
                        <span class="font-bold text-lg text-gray-900">Total:</span>
                        <span class="font-bold text-2xl text-blue-600" x-text="formatGs(total)"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('ordenes-compra.index') }}" class="px-6 py-3 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-300 font-medium transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md transition-colors">
                Crear Orden de Compra
            </button>
        </div>
    </form>
</div>

<script>
function ordenForm() {
    return {
        // Usamos la variable PHP que preparamos al inicio del archivo
        items: @json($itemsIniciales),
        
        descuento: 0, 
        subtotal: 0, 
        total: 0,

        init() {
            // Calculamos los totales iniciales por si vienen datos precargados
            this.items.forEach((item, index) => this.calcularSubtotal(index));
        },

        addItem() { 
            this.items.push({ producto_id: '', cantidad_solicitada: 1, precio_unitario: 0, subtotal: 0 }); 
        },
        
        removeItem(index) { 
            this.items.splice(index, 1); 
            this.calcularTotal(); 
        },

        updatePrecio(index, event) {
            // Usamos event.target para obtener el select que disparó el cambio
            // Esto es mucho más seguro que usar querySelector con nombres dinámicos
            const select = event.target;
            const option = select.options[select.selectedIndex];
            
            if (option && option.dataset.precio) {
                // Parseamos a entero (Gs.)
                this.items[index].precio_unitario = parseInt(option.dataset.precio) || 0;
                this.calcularSubtotal(index);
            }
        },
        
        calcularSubtotal(index) {
            const qty = parseFloat(this.items[index].cantidad_solicitada) || 0;
            const price = parseInt(this.items[index].precio_unitario) || 0;
            this.items[index].subtotal = Math.round(qty * price);
            this.calcularTotal();
        },
        
        calcularTotal() {
            this.subtotal = this.items.reduce((sum, item) => sum + item.subtotal, 0);
            const desc = parseInt(this.descuento) || 0;
            this.total = Math.max(0, this.subtotal - desc);
        },
        
        formatGs(value) { 
            return new Intl.NumberFormat('es-PY').format(value) + ' Gs.'; 
        }
    }
}
</script>
@endsection