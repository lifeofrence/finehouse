<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo company
        $company = Company::create([
            'name' => 'Finehouse Headquarters',
            'contact_email' => 'hq@finehouse.com',
            'phone' => '+1234567890',
            'address' => '123 Admin Lane, Silicon Valley',
        ]);

        // Create demo properties
        $propertyA = Property::create([
            'company_id' => $company->id,
            'name' => 'Sunrise Student Lodge',
            'address' => '400 University Blvd',
            'description' => 'A cozy lodge specifically for students.',
        ]);

        $propertyB = Property::create([
            'company_id' => $company->id,
            'name' => 'Luxury Heights Apartments',
            'address' => '900 Downtown Ave',
            'description' => 'Premium apartments for professionals.',
        ]);

        // Create Rooms
        $room1 = Room::create([
            'property_id' => $propertyA->id,
            'room_number' => '101A',
            'price' => 500,
            'is_available' => true,
            'description' => 'Spacious single room.'
        ]);

        $room2 = Room::create([
            'property_id' => $propertyB->id,
            'room_number' => 'Penthouse 1',
            'price' => 1500,
            'is_available' => true,
            'description' => 'Top floor luxury penthouse.'
        ]);

        // Create Admin
        User::create(['name' => 'Admin User', 'email' => 'admin@finehouse.test', 'password' => bcrypt('password'), 'role' => 'admin', 'company_id' => $company->id]);
        
        // Create Landlord
        User::create(['name' => 'Landlord Dan', 'email' => 'landlord@finehouse.test', 'password' => bcrypt('password'), 'role' => 'landlord', 'company_id' => $company->id]);
        
        // Create Accountant
        User::create(['name' => 'Accountant Alice', 'email' => 'accountant@finehouse.test', 'password' => bcrypt('password'), 'role' => 'accountant', 'company_id' => $company->id]);
        
        // Create Caretaker (assigned to Property A)
        User::create(['name' => 'Caretaker Bob', 'email' => 'caretaker@finehouse.test', 'password' => bcrypt('password'), 'role' => 'caretaker', 'company_id' => $company->id, 'property_id' => $propertyA->id]);
        
        // Create Lodge President (assigned to Property A)
        User::create(['name' => 'President Charlie', 'email' => 'president@finehouse.test', 'password' => bcrypt('password'), 'role' => 'lodge_president', 'company_id' => $company->id, 'property_id' => $propertyA->id]);

        // Create Tenant
        User::create(['name' => 'Tenant Tom', 'email' => 'tenant@finehouse.test', 'password' => bcrypt('password'), 'role' => 'tenant']);
    }
}
