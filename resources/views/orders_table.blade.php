<table class="min-w-full divide-y divide-gray-200 border">
    <thead>
        <tr>
            <th class="px-6 py-3 bg-gray-50 text-center">
                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Status</span>
            </th>
            <th class="px-6 py-3 bg-gray-50 text-center">
                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Count</span>
            </th>
            <th class="px-6 py-3 bg-gray-50 text-center">
                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Total (EGP)</span>
            </th>
        </tr>
    </thead>

    <tbody class="bg-white divide-y divide-gray-200 divide-solid">
        @foreach($statuses as $status)
        <tr>
            <th class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 text-center">
                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{$status}}</span>
            </th>
            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 text-center">
                {{ $dataArray[$status]['count'] }} <br/>
            </td>
            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 text-center">
                {{ number_format($dataArray[$status]['total'], 2) }} EGP
            </td>
        </tr>
        @endforeach
    </tbody>
</table>