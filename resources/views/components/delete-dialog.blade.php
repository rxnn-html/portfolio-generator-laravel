{{-- Open it from anywhere with:
     $dispatch('confirm-delete', { action: '/portfolios/ID', name: 'Juan' }) --}}
<dialog x-data="{ action: '', name: '', busy: false }" x-ref="dlg"
        x-on:confirm-delete.window="action = $event.detail.action; name = $event.detail.name; busy = false; $refs.dlg.showModal()"
        x-on:click="if ($event.target === $refs.dlg) $refs.dlg.close()"
        class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border border-gray-200 bg-white p-0 text-gray-900 shadow-2xl backdrop:bg-slate-950/60 backdrop:backdrop-blur-sm">
    <form method="POST" :action="action" x-on:submit="busy = true" class="p-6">
        @csrf
        @method('DELETE')

        <div class="flex items-start gap-4">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-xl text-red-600" aria-hidden="true">&#128465;</span>
            <div class="min-w-0">
                <h2 class="text-lg font-bold text-gray-900">Delete <span x-text="'&quot;' + name + '&quot;'"></span>?</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600">
                    This permanently removes the portfolio, all of its information, and its profile picture. This cannot be undone.
                </p>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" x-on:click="$refs.dlg.close()" :disabled="busy"
                    class="min-h-11 rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-60">Cancel</button>
            <button type="submit" :disabled="busy"
                    class="min-h-11 rounded-xl bg-red-600 px-4 text-sm font-semibold text-white hover:bg-red-500 disabled:cursor-not-allowed disabled:opacity-60">
                <span x-text="busy ? 'Deleting...' : 'Yes, delete it'"></span>
            </button>
        </div>
    </form>
</dialog>