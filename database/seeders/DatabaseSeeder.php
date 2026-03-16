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

        // Create Super Admin (Global, no company)
        User::create(['name' => 'Super Admin', 'email' => 'super@finehouse.test', 'password' => bcrypt('password'), 'role' => 'super_admin']);

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
        $tenantUser = User::create(['name' => 'Tenant Tom', 'email' => 'tenant@finehouse.test', 'password' => bcrypt('password'), 'role' => 'tenant']);

        // Create Tenant Profile
        \App\Models\TenantProfile::create([
            'user_id' => $tenantUser->id,
            'phone_number' => '+1987654321',
            'gender' => 'male',
            'dob' => '1995-05-15',
            'religion' => 'Christian',
            'state_of_origin' => 'California',
            'lga' => 'Los Angeles',
            'address' => '789 Hope St, LA',
            'type' => 'student',
            'university' => 'Stanford University',
            'matric_number' => 'STU-2024-001',
            'level' => '400',
            'department' => 'Computer Science',
            'faculty' => 'Engineering',
            'course' => 'Software Engineering',
            'next_of_kin' => 'Jane Tom',
            'next_of_kin_phone' => '+1000999888',
            'rent_commencement_date' => now()->startOfMonth(),
            'rent_expiry_date' => now()->startOfMonth()->addYear()->subDay(),
        ]);

        // Create Booking
        $booking = \App\Models\Booking::create([
            'user_id' => $tenantUser->id,
            'room_id' => $room1->id,
            'status' => 'confirmed',
            'interview_date' => now()->addDays(2),
            'interview_location' => 'Finehouse Headquarters',
        ]);

        // Mark room as occupied
        $room1->update(['is_available' => false]);

        // Create Payment
        \App\Models\Payment::create([
            'user_id' => $tenantUser->id,
            'property_id' => $propertyA->id,
            'room_id' => $room1->id,
            'amount' => 500,
            'status' => 'verified',
            'verified_by' => 1, // First admin
        ]);

        // Create Maintenance Request
        \App\Models\MaintenanceRequest::create([
            'user_id' => $tenantUser->id,
            'property_id' => $propertyA->id,
            'room_id' => $room1->id,
            'issue_description' => 'The kitchen faucet is leaking since yesterday.',
            'status' => 'pending',
        ]);

        // Create Announcement
        \App\Models\Announcement::create([
            'user_id' => 1, // First admin
            'property_id' => $propertyA->id,
            'title' => 'Scheduled Maintenance',
            'message' => 'Water supply will be temporarily cut off on Saturday from 10 AM to 2 PM for pipe repairs.',
        ]);
    }
}
