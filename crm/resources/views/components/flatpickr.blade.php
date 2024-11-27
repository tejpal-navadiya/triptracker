<!-- <input type="text" {{ $attributes->merge(['class' => 'form-control datetimepicker-input']) }} placeholder="From" value="{{ $value }}" /> -->

<input 
    type="text" 
    id="{{ $id }}" 
    name="{{ $name }}" 
    placeholder="{{ $placeholder }}" 
    value="{{ old($name, $value) }}"  
    {{ $attributes->merge(['class' => 'form-control datetimepicker-input']) }} 
/>


