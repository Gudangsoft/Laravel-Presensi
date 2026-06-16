<footer class="pt-6 pb-2">
    <div class="flex flex-col items-center justify-center gap-1 text-center">
        <p class="text-xs text-gray-400">
            &copy; {{ date('Y') }} <span class="font-semibold text-gray-500">{{ $brand?->footer_text ?? config('app.name') }}</span>. Hak Cipta Dilindungi.
        </p>
    </div>
</footer>
