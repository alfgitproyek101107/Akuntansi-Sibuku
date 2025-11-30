@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'help' => null,
    'options' => [],
    'class' => '',
    'attributes' => []
])

<div class="form-field {{ $class }}">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="required">*</span>
            @endif
        </label>
    @endif

    @switch($type)
        @case('textarea')
            <textarea
                name="{{ $name }}"
                id="{{ $name }}"
                class="form-control {{ $error ? 'is-invalid' : '' }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes }}
            >{{ old($name, $value) }}</textarea>
            @break

        @case('select')
            <select
                name="{{ $name }}"
                id="{{ $name }}"
                class="form-control {{ $error ? 'is-invalid' : '' }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes }}
            >
                @if($placeholder)
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach($options as $key => $option)
                    <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                        {{ $option }}
                    </option>
                @endforeach
            </select>
            @break

        @case('checkbox')
            <div class="form-check">
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="form-check-input {{ $error ? 'is-invalid' : '' }}"
                    value="1"
                    {{ old($name, $value) ? 'checked' : '' }}
                    {{ $required ? 'required' : '' }}
                    {{ $attributes }}
                >
                <label for="{{ $name }}" class="form-check-label">
                    {{ $label }}
                    @if($required)
                        <span class="required">*</span>
                    @endif
                </label>
            </div>
            @break

        @default
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $name }}"
                class="form-control {{ $error ? 'is-invalid' : '' }}"
                value="{{ old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes }}
            >
    @endswitch

    @if($error)
        <div class="invalid-feedback">
            {{ $error }}
        </div>
    @endif

    @if($help)
        <div class="form-help">
            {{ $help }}
        </div>
    @endif
</div>

<style>
.form-field {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.required {
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    color: #374151;
    background-color: #ffffff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.form-control.is-invalid:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

select.form-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px;
    padding-right: 40px;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-check-input {
    width: 16px;
    height: 16px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    background-color: #ffffff;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.form-check-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-check-label {
    font-size: 14px;
    color: #374151;
    cursor: pointer;
    margin-bottom: 0;
}

.invalid-feedback {
    display: block;
    font-size: 12px;
    color: #ef4444;
    margin-top: 4px;
}

.form-help {
    display: block;
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .form-control {
        padding: 10px 12px;
        font-size: 16px; /* Prevent zoom on iOS */
    }
}
</style>