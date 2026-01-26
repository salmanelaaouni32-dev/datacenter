<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\Category;
use App\Models\User;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        // 1. Get Categories
        $serverCat = Category::firstOrCreate(['name' => 'Serveurs'], ['description' => 'Serveurs Physiques Rackables']);
        $storageCat = Category::firstOrCreate(['name' => 'Stockage'], ['description' => 'Baies de stockage SAN/NAS']);
        $netCat = Category::firstOrCreate(['name' => 'Réseau'], ['description' => 'Switchs et Routeurs']);
        $vmCat = Category::firstOrCreate(['name' => 'Machines Virtuelles'], ['description' => 'Instances Cloud']);

        // 2. Get Manager
        $manager = User::whereHas('role', function($q) {
            $q->where('name', 'Responsable Technique');
        })->first();

        // 3. Create Realistic Resources
        $resources = [
            // SERVERS
            [
                'name' => 'Dell PowerEdge R750',
                'description' => 'Serveur Rack 2U, Double Intel Xeon Gold 6330, Idéal pour la virtualisation et VDI.',
                'location' => 'Baie A - U10-U11',
                'status' => 'active',
                'category_id' => $serverCat->id,
                'cpu_cores' => 56,
                'ram_gb' => 256,
                'storage_tb' => 4,
                'os_name' => 'VMware ESXi 7.0',
                'manager_id' => $manager?->id
            ],
            [
                'name' => 'HPE ProLiant DL380 Gen10',
                'description' => 'Serveur polyvalent, Intel Xeon Silver 4208, Pour bases de données moyennes.',
                'location' => 'Baie A - U12-U13',
                'status' => 'active',
                'category_id' => $serverCat->id,
                'cpu_cores' => 16,
                'ram_gb' => 64,
                'storage_tb' => 2,
                'os_name' => 'Debian 12',
                'manager_id' => $manager?->id
            ],
            // STORAGE
            [
                'name' => 'NetApp AFF A250',
                'description' => 'Baie de stockage All-Flash pour haute performance I/O.',
                'location' => 'Baie B - U20',
                'status' => 'active',
                'category_id' => $storageCat->id,
                'cpu_cores' => 0,
                'ram_gb' => 64,
                'storage_tb' => 35,
                'os_name' => 'ONTAP 9.9',
                'manager_id' => $manager?->id
            ],
            // NETWORK
            [
                'name' => 'Cisco Catalyst 9300',
                'description' => 'Switch 48 ports PoE+, Layer 3.',
                'location' => 'Baie A - Top of Rack',
                'status' => 'active',
                'category_id' => $netCat->id,
                'cpu_cores' => 4,
                'ram_gb' => 8,
                'storage_tb' => 0,
                'os_name' => 'IOS XE',
                'manager_id' => $manager?->id
            ],
            // VMs
            [
                'name' => 'VM-Dev-Cluster-01',
                'description' => 'Cluster Kubernetes Dev (3 nodes)',
                'location' => 'Virtual Cluster A',
                'status' => 'active',
                'category_id' => $vmCat->id,
                'cpu_cores' => 12,
                'ram_gb' => 48,
                'storage_tb' => 1,
                'os_name' => 'Ubuntu 22.04 LTS',
                'manager_id' => $manager?->id // Manager can manage VMs too
            ],
            [
                'name' => 'VM-Win-SQL-01',
                'description' => 'Serveur SQL Server 2019 Standard',
                'location' => 'Virtual Cluster B',
                'status' => 'maintenance',
                'category_id' => $vmCat->id,
                'cpu_cores' => 8,
                'ram_gb' => 32,
                'storage_tb' => 1,
                'os_name' => 'Windows Server 2019',
                'manager_id' => $manager?->id
            ]
        ];

        foreach ($resources as $res) {
            Resource::create($res);
        }
    }
}
