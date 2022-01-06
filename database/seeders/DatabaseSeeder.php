<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // task statuses
        DB::table('task_statuses')->truncate();
        DB::statement('ALTER TABLE task_statuses AUTO_INCREMENT=1;');
        DB::table('task_statuses')->insert([
            'status' => 'in_process',
            'description' => 'Procesā'
        ]);
        DB::table('task_statuses')->insert([
            'status' => 'pending',
            'description' => 'Gaida'
        ]);
        DB::table('task_statuses')->insert([
            'status' => 'testing',
            'description' => 'Tiek testēts'
        ]);
        DB::table('task_statuses')->insert([
            'status' => 'done',
            'description' => 'Izpildīts'
        ]);

        // request statuses
        DB::table('request_statuses')->truncate();
        DB::statement('ALTER TABLE request_statuses AUTO_INCREMENT=1;');
        DB::table('request_statuses')->insert([
            'status' => 'pending',
            'description' => 'Gaida'
        ]);
        DB::table('request_statuses')->insert([
            'status' => 'completed',
            'description' => 'Izpildīts'
        ]);
        DB::table('request_statuses')->insert([
            'status' => 'declined',
            'description' => 'Atteikts'
        ]);

        // user statuses
        DB::table('user_statuses')->truncate();
        DB::statement('ALTER TABLE user_statuses AUTO_INCREMENT=1;');
        DB::table('user_statuses')->insert([
            'status' => 'admin',
            'description' => 'Administrator of the whole system. Has the biggest privileges.'
        ]);
        DB::table('user_statuses')->insert([
            'status' => 'project_manager',
            'description' => 'Project Manager has the access to managing projects.'
        ]);
        DB::table('user_statuses')->insert([
            'status' => 'regular',
            'description' => 'Regular User.'
        ]);

        // users
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'John',
            'surname' => 'Dow',
            'email' => 'admin@pavelsivanovs.id.lv',
            'password' => Hash::make('password'),
            'status' => 1,
            'telephone_number' => '23456789'
        ]);
        DB::table('users')->insert([
            'name' => 'James',
            'surname' => 'Snow',
            'email' => 'pm@pavelsivanovs.id.lv',
            'password' => Hash::make('password'),
            'status' => 2,
            'telephone_number' => '98765432'
        ]);
        DB::table('users')->insert([
            'name' => 'Rosa',
            'surname' => 'Row',
            'email' => 'regular@pavelsivanovs.id.lv',
            'password' => Hash::make('password'),
            'status' => 3,
            'telephone_number' => '22334455'
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
