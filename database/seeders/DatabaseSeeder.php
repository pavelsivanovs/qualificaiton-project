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
            'status' => 'Administrators',
            'description' => 'Administrator of the whole system. Has the biggest privileges.'
        ]);
        DB::table('user_statuses')->insert([
            'status' => 'Projekta vadītājs',
            'description' => 'Project Manager has the access to managing projects.'
        ]);
        DB::table('user_statuses')->insert([
            'status' => 'Parasts lietotājs',
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

        // projects
        DB::table('projects')->insert([
            'title' => 'Kvalifikācijas darbs',
            'description' => 'Janvāri mums ir jāsagatavo kvalifikācijas darbs. Laiks iet. Vajag saorganizēt darbu.
            Cik labi, ka kāds LU DF students izveidoja informācijas sistēmu projektu pārvaldībai. Izmantosim to!',
            'project_manager' => '2',
            'accent_color' => 'A69888'
        ]);
        DB::table('projects')->insert([
            'title' => 'Ekonomikas rehabilitācija',
            'description' => 'Inflācija, enerģētiskā krīze, pandēmija! Kaut kas ir jādara ar šo. Te izveidosim uzdevumus un tālāk lēmsis, ko mēs visi ar šo darīsim',
            'project_manager' => '2',
            'accent_color' => 'db877f'
        ]);
        DB::table('projects')->insert([
            'title' => 'Testēšana',
            'description' => 'Viena no darba lielām daļām ir projekta testēšana. Mums ir jānodrošina kvalitāte!',
            'project_manager' => '2',
            'accent_color' => '8b861b'
        ]);
        DB::table('projects')->insert([
            'title' => 'Personīgais pet-project',
            'description' => 'Pirms sūtīt savu CV, es izveidošu nelielu projektiņu priekš portfolio. Plānojam pabeigt janvārī.',
            'project_manager' => '2',
            'accent_color' => '683718'
        ]);

        // tasks
        DB::table('tasks')->insert([
            'title' => 'Izveidot lietotāju saskarņu projektējumus',
            'description' => 'Piektajā daļā nepieiešams iekļaut dažas lietotāju saskarnes. Ir jāizveido projektējums uzdevuma veidošanas un uzdevuma skatīšanas saskarnēm. Ja ir kādi jautājumi, tad lūdzu, vēršaties. Paldies.',
            'project' => '1',
            'status' => '2',
            'assignee' => '3',
            'deadline' => '2022-01-10'
        ]);
        DB::table('tasks')->insert([
            'title' => 'Noformēt dokumentāciju',
            'description' => 'Saskaņa ar Latvijas Universitātes noteikumiem, gala darbiem ir jābūt atbilstoši noformētiem. Lūdzu aplūkot noformēju vadlīnijas (https://estudijas.lu.lv/pluginfile.php/228929/mod_resource/content/0/2012/PrasibasNoslegumaDarbuIzstrade_2012.pdf) un noformet dokumentāciju atbilstoši tām. Paldies!',
            'project' => '1',
            'status' => '2',
            'assignee' => '3'
        ]);
        DB::table('tasks')->insert([
            'title' => 'Notestēt Autentifikācijas moduli',
            'description' => 'Ir nepieciešams izveidot testa piemērus autentifikācijas moduļa funkcijām, un atbilstoši tiem notestēt visas funkcijas. Gala rezultātus pierakstīt dokumentācijā ar atbilstošu noformējumu. Paldies!',
            'project' => '3',
            'status' => '2',
            'assignee' => '2'
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
