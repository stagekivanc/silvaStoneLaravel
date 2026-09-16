<div class="flex gap-2 mb-6 p-1 bg-slate-100 rounded-2xl w-fit">
    @foreach(\App\Models\Language::active() as $lang)
    <button type="button" 
            onclick="switchLanguage('{{ $lang->code }}')"
            data-lang-btn="{{ $lang->code }}"
            class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all {{ $lang->is_default ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
        {{ $lang->name }}
    </button>
    @endforeach
</div>

<script>
    function switchLanguage(langCode) {
        // Toggle Buttons
        document.querySelectorAll('[data-lang-btn]').forEach(btn => {
            if (btn.getAttribute('data-lang-btn') === langCode) {
                btn.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                btn.classList.remove('text-slate-500', 'hover:text-slate-700');
            } else {
                btn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                btn.classList.add('text-slate-500', 'hover:text-slate-700');
            }
        });

        // Toggle Content Areas
        document.querySelectorAll('[data-lang-area]').forEach(area => {
            if (area.getAttribute('data-lang-area') === langCode) {
                area.classList.remove('hidden');
            } else {
                area.classList.add('hidden');
            }
        });

        var langInput = document.getElementById('activeLangInput');
        if (langInput) {
            langInput.value = langCode;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var requestedLang = '{{ request('lang') }}';
        if (requestedLang) {
            switchLanguage(requestedLang);
        }
    });
</script>
