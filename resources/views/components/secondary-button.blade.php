<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            'inline-flex items-center px-4 py-2  border border-gray-300 text-white rounded-md font-semibold text-xs bg-yellow-600 uppercase tracking-widest shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150',
    ]) }}>
    {{ $slot }}
</button>
