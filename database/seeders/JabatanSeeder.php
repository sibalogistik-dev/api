<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatan = [
            "Manager Operasional",
            "Operasional",
            "IT Staff",
            "Admin Finance",
            "Manager HRD dan Operasional",
            "Admin CS Cabang",
            "MCC Admin Tracking dan Customer Service",
            "Digital Marketing Staff",
            "Admin Pusat & Vendor Tracking",
            "Marketing",
            "HRD Staff",
            "Admin Gudang",
            "Manager Marketing",
            "Administrator",
            "Manager Digital Marketing",
            "Admin Siba Box",
            "Operasional Siba Box",
            "Admin SAuto8",
            "Rider SAuto8",
            "Manager SAuto8",
            "Manager Siba Box",
            "Development",
            "Direktur Operasional dan Legal",
            "Direktur dan Koordinator Batam",
            "Direktur Business Development",
            "Supervisor Cabang Jakarta",
            "Komisaris Utama",
            "Marketing Freelance",
            "VENDOR",
            "Mabes",
            "Vendor & Tracking Staff",
            "Operasional Mabes",
            "Admin CS Pusat",
            "Ops Showroom",
            "Operasional Mekanik SAuto8",
            "Koordinator Gudang",
            "Koordinator Operasional Cabang",
            "Kepala Gudang Pusat",
            "Operasional Senior",
            "Koordinator Finance",
            "Finance Staff",
            "Admin CS SAuto8",
            "Operasional SAuto8",
            "Customer Relationship Management",
            "Sales",
            "Men Cargo",
            "DM & IT Staff",
            "Anonim",
            "Spv Operasional",
            "Purchasing",
            "Admin CS & CRM",
            "Admin Tracking",
            "Mabes Cargo",
            "Admin MBS Cargo Rental",
            "Operasional MBS Cargo",
            "Mekanik Mbs Cargo",
            "Operasional MENCARGO",
            "Accounting",
            "Admin Toko"
        ];

        $jabatanBaru = [
            'Manajer Operasional dan HRD',
            'Manajer Marketing',
            'Manajer Digital Marketing',
            'Manajer Finance',
            'Staff Operasional',
            'Staff HRD',
            'Staff IT',
            'Staff Digital Marketing',
            'Staff Branding',
            'Staff Finance',
            'Staff Accounting',
            'Staff Tracking',
            'Staff Purchasing',
            'Customer Service',
        ];

        for ($i = 0; $i < count($jabatan); $i++) {
            Jabatan::create([
                'name' => $jabatan[$i]
            ]);
        }
    }
}
