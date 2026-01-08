<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\ParkingSpot;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // High-quality parking garage images - NYC theme (Reliable URLs)
        $images = [
            'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1616363088386-31c4a8414858?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1590674899484-d5640e854abe?q=80&w=1167&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1578859695220-856a4f5edd39?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1724274876097-103bb600debb?q=80&w=736&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1621929747188-0b4dc28498d2?q=80&w=1332&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1617209680736-7a6202c498a1?q=80&w=1330&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1566663589363-47477e949b94?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1597985933897-cfbf9e2464d0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
            'https://images.unsplash.com/photo-1532217635-b45271b1aab6?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
        ];

        // NYC parking garages centered around Times Square (40.7580, -73.9855)
        $nycGarages = [
            [
                'name' => 'Times Square Valet Parking',
                'description' => 'Premium valet parking service in the heart of Times Square, steps from Broadway theaters.',
                'amenities' => ['Valet Service', 'Security', '24/7 Access', 'Theater District']
            ],
            [
                'name' => 'Broadway Theater Parking',
                'description' => 'Convenient parking for theatergoers with direct access to all major Broadway venues.',
                'amenities' => ['Theater Access', 'Security', 'Covered', 'Pre-booking Available']
            ],
            [
                'name' => 'Rockefeller Center Garage',
                'description' => 'Multi-level parking facility at iconic Rockefeller Center, perfect for tourists and business travelers.',
                'amenities' => ['Premium', 'Security', 'Elevators', 'Tourist Attraction']
            ],
            [
                'name' => 'Empire State Building Parking',
                'description' => 'Secure underground parking near the Empire State Building with excellent midtown access.',
                'amenities' => ['Underground', 'Security', 'EV Charging', 'Landmark Access']
            ],
            [
                'name' => 'Hell\'s Kitchen Secure Park',
                'description' => 'Affordable neighborhood parking in trendy Hell\'s Kitchen with 24/7 surveillance.',
                'amenities' => ['Security', 'CCTV', '24/7 Access', 'Neighborhood Parking']
            ],
            [
                'name' => 'Port Authority Station Garage',
                'description' => 'Large multi-level garage serving Port Authority Bus Terminal and Penn Station commuters.',
                'amenities' => ['Transit Access', 'Security', 'Covered', 'Monthly Passes']
            ],
            [
                'name' => 'Bryant Park Underground',
                'description' => 'Underground parking facility beneath Bryant Park with easy access to Midtown offices.',
                'amenities' => ['Underground', 'Security', 'Business District', 'Park Access']
            ],
            [
                'name' => 'Midtown West Business Parking',
                'description' => 'Professional parking for business travelers with reserved corporate spots available.',
                'amenities' => ['Business District', 'Reserved Spots', 'EV Charging', 'Monthly Passes']
            ],
            [
                'name' => 'Herald Square Garage',
                'description' => 'Central parking near Macy\'s Herald Square and the Garment District shopping area.',
                'amenities' => ['Shopping Access', 'Security', 'Covered', 'Elevators']
            ],
            [
                'name' => 'Hudson Yards Premium Parking',
                'description' => 'State-of-the-art parking at Hudson Yards development with luxury amenities.',
                'amenities' => ['Premium', 'Valet Available', 'EV Charging', 'Modern Facility']
            ]
        ];

        // Times Square coordinates
        $centerLat = 40.7580;
        $centerLng = -73.9855;

        // Create parking places with clustered locations
        foreach ($nycGarages as $index => $garageData) {
            // Generate random offset (0.005 degrees max) for tight clustering around Times Square
            $latOffset = (mt_rand(1, 5) / 1000) * (mt_rand(0, 1) ? 1 : -1);
            $lngOffset = (mt_rand(1, 5) / 1000) * (mt_rand(0, 1) ? 1 : -1);
            
            // Generate realistic pricing between $5 and $20 per hour
            $pricePerHour = rand(5, 20);
            
            $place = Place::create([
                'name' => $garageData['name'],
                'description' => $garageData['description'],
                'price_per_hour' => $pricePerHour,
                'address' => 'Midtown Manhattan, New York, NY, USA',
                'lat' => $centerLat + $latOffset,
                'lng' => $centerLng + $lngOffset,
                'amenities' => $garageData['amenities'],
                'image' => $images[$index % count($images)] // Cycle through images
            ]);
            
            // Create parking spots for each place with randomized count
            $slotCount = rand(5, 25);
            for ($i = 1; $i <= $slotCount; $i++) {
                // 70% chance of being available, 30% chance of being occupied/reserved
                $isAvailable = rand(1, 100) <= 70;
                
                $place->parkingSpots()->create([
                    'title' => "A-{$i}",
                    'price' => $pricePerHour,
                    'is_available' => $isAvailable
                ]);
            }
        }
    }
}
