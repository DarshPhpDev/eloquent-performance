<table class="min-w-full divide-y divide-gray-200 border">
    <thead>
    <tr>
        <th class="px-6 py-3 bg-gray-50 text-center">
            <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">User name</span>
        </th>
        <th class="px-6 py-3 bg-gray-50 text-center">
            <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</span>
        </th>
        <th class="px-6 py-3 bg-gray-50 text-center">
            <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Orders</span>
        </th>
    </tr>
    </thead>

    <tbody class="bg-white divide-y divide-gray-200 divide-solid">
    @foreach($users as $user)
        <tr class="bg-white">
            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                {{ $user['name'] }}
            </td>
            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                {{ $user['email'] }}
            </td>
            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                @if(count($user['orders']))
                    <ul style="list-style:disc">
                        @foreach($user['orders'] as $order)
                            <li>
                                Order {{ $order['id'] }} ({{ $order['status'] }}) - Total: ${{ number_format($order['total_amount'], 2) }}
                            </li>
                            <ul style="list-style:disc; margin-left: 20px;">
                                @foreach($order['products'] as $product)
                                    <li> Product {{ $product['name'] }} (${{ number_format($product['price'], 2) }}) - Quantity: {{ $product['quantity'] }} </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </ul>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>