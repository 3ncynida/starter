<table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden']) }}>
    <thead class="bg-gray-50">
        <tr>
            {{ $head }}
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        {{ $slot }}
    </tbody>
</table>
