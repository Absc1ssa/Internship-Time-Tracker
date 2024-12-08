@props(['disabled' => false, 'type' => 'text'])

<div class="relative">
    <input 
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full']) !!}
        type="{{ $type }}" 
    >

    @if ($type === 'password')
        <button type="button" style="left: 90%;" class="relative py-3 text-gray-500 " id="togglePassword">
            <span class="material-icons text-sm">
                visibility
            </span>
        </button>
    @endif
</div>
