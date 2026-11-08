<x-layouts::error :title="__('403 - Akses Ditolak')">
    <x-error-page
        code="403"
        :badge="__('Galat 403 (Forbidden)')"
        :title="__('Akses Dibatasi')"
        :message="__('Mohon maaf, Anda tidak memiliki izin otorisasi untuk mengakses halaman atau fungsi ini.')"
        icon="shield-exclamation"
        :show-home="true"
        :show-dashboard="true"
    />
</x-layouts::error>

