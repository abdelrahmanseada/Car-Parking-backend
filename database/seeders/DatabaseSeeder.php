<?php

namespace Database\Seeders;

use App\Models\Place;
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
        // User::factory(10)->create();
        $usParkingSpots = [
            [
                'name' => 'Times Square Parking Garage',
                'street' => '255 W 43rd St',
                'description' => 'Multi-level parking garage in the heart of Times Square.',
                'price' => 55.00,
                'lat' => 40.7580,
                'lng' => -73.9855,
                'city' => 'New York',
                'country' => 'USA',
                'full_address' => '255 W 43rd St, New York, NY 10036, USA',
                'google_maps_url' => 'https://maps.google.com/?q=40.7580,-73.9855'
            ],
            [
                'name' => 'Santa Monica Pier Parking Lot',
                'street' => '1550 Pacific Coast Hwy',
                'description' => 'Open air parking lot right next to Santa Monica Pier.',
                'price' => 25.00,
                'lat' => 34.0105,
                'lng' => -118.4962,
                'city' => 'Santa Monica',
                'country' => 'USA',
                'full_address' => '1550 Pacific Coast Hwy, Santa Monica, CA 90401, USA',
                'google_maps_url' => 'https://maps.google.com/?q=34.0105,-118.4962'
            ],
            [
                'name' => 'Millennium Park Garage',
                'street' => '5 S Columbus Dr',
                'description' => 'Underground parking beneath Millennium Park.',
                'price' => 42.00,
                'lat' => 41.8827,
                'lng' => -87.6233,
                'city' => 'Chicago',
                'country' => 'USA',
                'full_address' => '5 S Columbus Dr, Chicago, IL 60601, USA',
                'google_maps_url' => 'https://maps.google.com/?q=41.8827,-87.6233'
            ],
            [
                'name' => 'Fishermans Wharf Valet Parking',
                'street' => '2801 Leavenworth St',
                'description' => 'Premium valet parking service at Fishermans Wharf.',
                'price' => 45.00,
                'lat' => 37.8095,
                'lng' => -122.4101,
                'city' => 'San Francisco',
                'country' => 'USA',
                'full_address' => '2801 Leavenworth St, San Francisco, CA 94133, USA',
                'google_maps_url' => 'https://maps.google.com/?q=37.8095,-122.4101'
            ],
            [
                'name' => 'Miami Beach Parking Structure',
                'street' => '1111 Lincoln Rd',
                'description' => 'Architectural parking structure designed by Herzog & de Meuron.',
                'price' => 35.00,
                'lat' => 25.7907,
                'lng' => -80.1370,
                'city' => 'Miami Beach',
                'country' => 'USA',
                'full_address' => '1111 Lincoln Rd, Miami Beach, FL 33139, USA',
                'google_maps_url' => 'https://maps.google.com/?q=25.7907,-80.1370'
            ],
            [
                'name' => 'Las Vegas Strip Surface Lot',
                'street' => '3570 S Las Vegas Blvd',
                'description' => 'Economical surface parking near MGM Grand.',
                'price' => 20.00,
                'lat' => 36.1069,
                'lng' => -115.1719,
                'city' => 'Las Vegas',
                'country' => 'USA',
                'full_address' => '3570 S Las Vegas Blvd, Las Vegas, NV 89109, USA',
                'google_maps_url' => 'https://maps.google.com/?q=36.1069,-115.1719'
            ],
            [
                'name' => 'Seattle Center Parking Garage',
                'street' => '305 Harrison St',
                'description' => 'Parking garage serving Seattle Center and Space Needle.',
                'price' => 32.00,
                'lat' => 47.6225,
                'lng' => -122.3542,
                'city' => 'Seattle',
                'country' => 'USA',
                'full_address' => '305 Harrison St, Seattle, WA 98109, USA',
                'google_maps_url' => 'https://maps.google.com/?q=47.6225,-122.3542'
            ],
            [
                'name' => 'Boston Common Underground Garage',
                'street' => '0 Charles St',
                'description' => 'Underground parking beneath Boston Common.',
                'price' => 38.00,
                'lat' => 42.3569,
                'lng' => -71.0667,
                'city' => 'Boston',
                'country' => 'USA',
                'full_address' => '0 Charles St, Boston, MA 02114, USA',
                'google_maps_url' => 'https://maps.google.com/?q=42.3569,-71.0667'
            ]
        ];

        // لإنشاء البيانات الأمريكية
        foreach ($usParkingSpots as $spot) {
            Place::create($spot);
        }

    }
}
