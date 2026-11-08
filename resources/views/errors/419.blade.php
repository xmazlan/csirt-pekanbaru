<x-layouts::error :title="__('419 - Sesi Kedaluwarsa')">
    <x-error-page
        code="419"
        :badge="__('Galat 419 (Page Expired)')"
        :title="__('Sesi Telah Kedaluwarsa')"
        :message="__('Sesi token keamanan formulir Anda telah berakhir karena tidak ada aktivitas. Silakan muat ulang halaman dan coba kembali.')"
        icon="clock"
        :show-reload="true"
        :show-home="true"
        :show-dashboard="true"
    />
</x-layouts::error>

