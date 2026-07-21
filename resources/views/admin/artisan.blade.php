<x-admin-layout>
    <x-slot name="title">Artisan</x-slot>

    <div class="card p-5 max-w-lg">
        <form method="POST" class="flex gap-2">
            @csrf
            <select name="cmd" class="input-field flex-1" required>
                <option value="migrate">migrate (tambah tabel baru)</option>
                <option value="db:seed">db:seed (semua seeder)</option>
                <option value="db:seed --class=CustomerSeeder">Seeder: Customer</option>
                <option value="db:seed --class=VehicleSeeder">Seeder: Vehicle</option>
                <option value="db:seed --class=MechanicSeeder">Seeder: Mechanic</option>
                <option value="db:seed --class=RepairOrderSeeder">Seeder: RepairOrder</option>
                <option value="db:seed --class=CategorySeeder">Seeder: Category</option>
                <option value="db:seed --class=ServiceSeeder">Seeder: Service</option>
                <option value="db:seed --class=BrandPartnerSeeder">Seeder: BrandPartner</option>
                <option value="db:seed --class=ProductSeeder">Seeder: Product</option>
                <option value="migrate:fresh">⚠️ migrate:fresh (hapus semua data!)</option>
            </select>
            <button type="submit" class="btn-primary shrink-0">Jalankan</button>
        </form>
        <p class="text-xs text-brand-ink-faint mt-3">Pilih perintah lalu klik Jalankan. Hasil akan tampil di halaman baru.</p>
    </div>
</x-admin-layout>
