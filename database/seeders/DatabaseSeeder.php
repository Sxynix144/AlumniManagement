<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\Event;
use App\Models\EventBatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin Account ─────────────────────────────────────────────────────
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@umindanao.edu.ph',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'email_verified_at' => now(),
        ]);

        // ── Event Coordinator Account ─────────────────────────────────────────
        $coordinator = User::create([
            'name'     => 'Event Coordinator',
            'email'    => 'coordinator@umindanao.edu.ph',
            'password' => Hash::make('password'),
            'role'     => 'coordinator',
            'email_verified_at' => now(),
  
        ]);

        // ── Sample Alumni (Placeholder — from old records) ────────────────────
        $placeholders = [
            ['student_number' => '2015-001', 'first_name' => 'Juan',   'last_name' => 'dela Cruz', 'graduation_year' => 2015, 'course' => 'BS Computer Science'],
            ['student_number' => '2015-042', 'first_name' => 'Maria',  'last_name' => 'Santos',    'graduation_year' => 2015, 'course' => 'BS Nursing'],
            ['student_number' => '2016-010', 'first_name' => 'Jose',   'last_name' => 'Reyes',     'graduation_year' => 2016, 'course' => 'BS Education'],
            ['student_number' => '2018-033', 'first_name' => 'Ana',    'last_name' => 'Garcia',    'graduation_year' => 2018, 'course' => 'BS Accountancy'],
            ['student_number' => '2018-077', 'first_name' => 'Pedro',  'last_name' => 'Villanueva','graduation_year' => 2018, 'course' => 'BS Civil Engineering'],
            ['student_number' => '2020-005', 'first_name' => 'Sofia',  'last_name' => 'Lim',       'graduation_year' => 2020, 'course' => 'BS Psychology'],
            ['student_number' => '2022-019', 'first_name' => 'Carlo',  'last_name' => 'Mendoza',   'graduation_year' => 2022, 'course' => 'BS Information Technology'],
        ];

        foreach ($placeholders as $data) {
            Alumni::create(array_merge($data, ['status' => 'placeholder']));
        }

      
       

     

        // ── Sample Event ──────────────────────────────────────────────────────
        $event = Event::create([
            'created_by'  => $coordinator->id,
            'title'       => 'Grand Homecoming 2025',
            'description' => 'Join us for a night of memories, reconnection, and celebration with your batchmates and the entire alumni community. Special awards ceremony included.',
            'event_date'  => now()->addMonths(3),
            'venue'       => 'University Gymnasium, Main Campus',
            'status'      => 'published',
        ]);

        EventBatch::insert([
            ['event_id' => $event->id, 'graduation_year' => 2015],
            ['event_id' => $event->id, 'graduation_year' => 2016],
            ['event_id' => $event->id, 'graduation_year' => 2017],
        ]);
    }
}
