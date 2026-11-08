<x-layouts::error :title="__('503 - Layanan Sedang Pemeliharaan')">
    <x-error-page
        code="503"
        :badge="__('Galat 503 (Service Unavailable)')"
        :title="__('Pemeliharaan Sistem')"
        :message="__('Sistem CSIRT Pekanbaru saat ini sedang dalam peningkatan performa atau pemeliharaan berkala. Silakan periksa kembali beberapa saat lagi.')"
        icon="wrench-screwdriver"
        :show-reload="true"
        :show-home="true"
        :show-dashboard="true"
    />
</x-layouts::error>

