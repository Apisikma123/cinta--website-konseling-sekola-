@props(['name' => 'otp', 'length' => 6])

<div class="flex justify-between gap-2" id="otp-container">
    @for($i = 0; $i < $length; $i++)
        <input type="text"
               maxlength="1"
               inputmode="numeric"
               pattern="[0-9]"
               class="otp-input w-9 h-11 text-center text-base border border-gray-300 rounded-md focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
               data-index="{{ $i }}"
               required>
    @endfor
</div>
<input type="hidden" name="{{ $name }}" id="otp_hidden">

@push('scripts')
<script>
(function() {
    const container = document.getElementById('otp-container');
    if (!container) return;

    const inputs = container.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otp_hidden');

    function updateHiddenValue() {
        let value = '';
        inputs.forEach(input => value += input.value);
        if (hiddenInput) hiddenInput.value = value;
    }

    inputs.forEach((input, index) => {
        // Focus first input on load
        if (index === 0) {
            setTimeout(() => input.focus(), 100);
        }

        // Handle input
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length === 1) {
                updateHiddenValue();
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
        });

        // Handle keydown
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace') {
                if (this.value === '' && index > 0) {
                    e.preventDefault();
                    inputs[index - 1].value = '';
                    inputs[index - 1].focus();
                    updateHiddenValue();
                } else if (this.value !== '') {
                    this.value = '';
                    updateHiddenValue();
                }
            }
            else if (e.key === 'ArrowLeft' && index > 0) {
                e.preventDefault();
                inputs[index - 1].focus();
            }
            else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                e.preventDefault();
                inputs[index + 1].focus();
            }
        });

        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, {{ $length }});

            if (pasteData.length > 0) {
                pasteData.split('').forEach((char, i) => {
                    if (i < inputs.length) {
                        inputs[i].value = char;
                    }
                });
                updateHiddenValue();

                const nextEmpty = Array.from(inputs).findIndex(inp => inp.value === '');
                if (nextEmpty !== -1) {
                    inputs[nextEmpty].focus();
                } else {
                    inputs[inputs.length - 1].blur();
                }
            }
        });

        // Prevent non-numeric input
        input.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    });
})();
</script>
@endpush
