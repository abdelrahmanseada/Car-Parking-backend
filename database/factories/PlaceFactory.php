<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition()
    {
        // البيانات الأمريكية التي تريدها
        $usParkingSpots = [
            [
                'name' => 'Times Square Parking Garage',
                'street' => '255 W 43rd St',
                'description' => 'Multi-level parking garage in the heart of Times Square.',
                'price' => 55.00,
                'lat' => 40.7580,
                'lng' => -73.9855,
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'full_address' => '255 W 43rd St, New York, NY 10036, USA',
                'hourly_rate' => 12.50,
                'capacity' => 350,
                'parking_type' => 'Garage'
            ],
            [
                'name' => 'Santa Monica Pier Parking Lot',
                'street' => '1550 Pacific Coast Hwy',
                'description' => 'Open air parking lot right next to Santa Monica Pier.',
                'price' => 25.00,
                'lat' => 34.0105,
                'lng' => -118.4962,
                'city' => 'Santa Monica',
                'state' => 'CA',
                'country' => 'USA',
                'full_address' => '1550 Pacific Coast Hwy, Santa Monica, CA 90401, USA',
                'hourly_rate' => 5.00,
                'capacity' => 200,
                'parking_type' => 'Lot'
            ],
            [
                'name' => 'Millennium Park Garage',
                'street' => '5 S Columbus Dr',
                'description' => 'Underground parking beneath Millennium Park.',
                'price' => 42.00,
                'lat' => 41.8827,
                'lng' => -87.6233,
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'full_address' => '5 S Columbus Dr, Chicago, IL 60601, USA',
                'hourly_rate' => 9.75,
                'capacity' => 450,
                'parking_type' => 'Garage'
            ],
            [
                'name' => 'Fishermans Wharf Valet Parking',
                'street' => '2801 Leavenworth St',
                'description' => 'Premium valet parking service at Fishermans Wharf.',
                'price' => 45.00,
                'lat' => 37.8095,
                'lng' => -122.4101,
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'full_address' => '2801 Leavenworth St, San Francisco, CA 94133, USA',
                'hourly_rate' => 18.00,
                'capacity' => 120,
                'parking_type' => 'Valet'
            ],
            [
                'name' => 'Miami Beach Parking Structure',
                'street' => '1111 Lincoln Rd',
                'description' => 'Architectural parking structure designed by Herzog & de Meuron.',
                'price' => 35.00,
                'lat' => 25.7907,
                'lng' => -80.1370,
                'city' => 'Miami Beach',
                'state' => 'FL',
                'country' => 'USA',
                'full_address' => '1111 Lincoln Rd, Miami Beach, FL 33139, USA',
                'hourly_rate' => 8.50,
                'capacity' => 300,
                'parking_type' => 'Garage'
            ],
            [
                'name' => 'Las Vegas Strip Surface Lot',
                'street' => '3570 S Las Vegas Blvd',
                'description' => 'Economical surface parking near MGM Grand.',
                'price' => 20.00,
                'lat' => 36.1069,
                'lng' => -115.1719,
                'city' => 'Las Vegas',
                'state' => 'NV',
                'country' => 'USA',
                'full_address' => '3570 S Las Vegas Blvd, Las Vegas, NV 89109, USA',
                'hourly_rate' => 4.00,
                'capacity' => 500,
                'parking_type' => 'Lot'
            ],
            [
                'name' => 'Seattle Center Parking Garage',
                'street' => '305 Harrison St',
                'description' => 'Parking garage serving Seattle Center and Space Needle.',
                'price' => 32.00,
                'lat' => 47.6225,
                'lng' => -122.3542,
                'city' => 'Seattle',
                'state' => 'WA',
                'country' => 'USA',
                'full_address' => '305 Harrison St, Seattle, WA 98109, USA',
                'hourly_rate' => 7.50,
                'capacity' => 400,
                'parking_type' => 'Garage'
            ],
            [
                'name' => 'Boston Common Underground Garage',
                'street' => '0 Charles St',
                'description' => 'Underground parking beneath Boston Common.',
                'price' => 38.00,
                'lat' => 42.3569,
                'lng' => -71.0667,
                'city' => 'Boston',
                'state' => 'MA',
                'country' => 'USA',
                'full_address' => '0 Charles St, Boston, MA 02114, USA',
                'hourly_rate' => 9.00,
                'capacity' => 380,
                'parking_type' => 'Garage'
            ]
        ];
    }
}
