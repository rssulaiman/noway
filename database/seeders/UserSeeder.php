<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // FirstOrCreate permet de vérifier si l'utilisateur existe déjà, et si l'utilisateur n'existe pas, on le crée
        $admin = User::firstOrCreate([
            'login' => 'admin@admin.com',
            'nom' => 'Admin',
            'prenom' => 'Adminstrateur',
            'type' => 'admin'
        ]);

        if (!$admin->mdp) {
            $admin->update([
                'mdp' => Hash::make('admin')
            ]);
        }


        if (User::where('type', 'etudiant')->count() < 3) {
            for ($i = 0; $i < 3; $i++) {
                User::create([
                    'login' => "etudiant$i@etudiant.com",
                    'nom' => 'Etudiant' . $i,
                    'prenom' => "$i - $i",
                    'type' => 'etudiant',
                    'mdp' => Hash::make('etudiant')
                ]);
            }
        }

        if (User::where('type', 'enseignant')->count() < 3) {
            for ($i = 0; $i < 3; $i++) {
                User::create([
                    'login' => "enseignant$i@enseignant.com",
                    'nom' => 'Enseignant' . $i,
                    'prenom' => "$i - $i",
                    'type' => 'enseignant',
                    'mdp' => Hash::make('enseignant')
                ]);
            }
        }

        //User::factory(5)->create();
    }
}
