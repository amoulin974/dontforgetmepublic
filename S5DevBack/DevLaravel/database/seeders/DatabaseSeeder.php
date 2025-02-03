<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Reservation;
use App\Models\Creneau;
use App\Models\Notification;
use App\Models\Plage;
use App\Models\Activite;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'numTel' => '0000000000',
            'password' => 'abcd',
            'typeNotif' => 'test',
            'delaiAvantNotif' => '2000-01-01T00:00:00Z',
            'superadmin' => 1,
        ]);

        User::factory()->create([
            'nom' => 'GUIHENEUF',
            'prenom' => 'Mattin',
            'email' => 'mguiheneuf@iutbayonne.univ-pau.fr',
            'numTel' => '0000000000',
            'password' => 'mattinAdmin',
            'typeNotif' => 'email',
            'delaiAvantNotif' => '2000-01-01T00:30:00Z',
            'superadmin' => 1,
        ]);

        User::factory()->create([
            'nom' => 'VICTORAS',
            'prenom' => 'Dylan',
            'email' => 'dvictoras@iutbayonne.univ-pau.fr',
            'numTel' => '01234567',
            'password' => 'dylanAdmin',
            'typeNotif' => 'email',
            'delaiAvantNotif' => '2000-01-01T00:30:00Z',
            'superadmin' => 1,
        ]);

        User::factory()->create([
            'nom' => 'MOURGUE',
            'prenom' => 'Clément',
            'email' => 'cmourgue@iutbayonne.univ-pau.fr',
            'numTel' => '01234567',
            'password' => 'clementAdmin',
            'typeNotif' => 'email',
            'delaiAvantNotif' => '2000-01-01T01:00:00Z',
            'superadmin' => 1,
        ]);

        Entreprise::factory(10)->create();

        Entreprise::factory()->create([
            'libelle' => fake()->company,
            'siren' => fake()->numerify('######### #####'),
            'adresse' => fake()->address(),
            'metier' => fake()->city(),
            'description' => fake()->state(),
            'type' => fake()->postcode(),
            'numTel' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'cheminImg' => json_encode(['https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg']),
            'publier' => 1,
            'typeRdv' => json_encode(['Test']),
            'idCreateur' => 1,
        ]);

        Reservation::factory(10)->create();

        Creneau::factory(2)->create();

        Creneau::factory()->create([
            'dateC' => '2024-12-19T00:00:00Z',
            'heureDeb' => fake()->time('H:i:s'),
            'heureFin' => fake()->time('H:i:s'),
        ]);

        Notification::factory(10)->create();

        Reservation::factory()->create([
            'dateRdv' => '2000-01-01T00:00:00Z',
            'heureDeb' => fake()->time('H:i:s'),
            'heureFin' => fake()->time('H:i:s'),
            'nbPersonnes' => 3,
        ]);

        Notification::factory()->create([
            'categorie' => 'UneCatNotif',
            'delai' => fake()->randomElement([24, 48, 168]),
            'etat' => 0,
            'contenu' => 'UnContenuTest',
            'reservation_id' => 1,
        ]);

        Plage::factory(10)->create();

        Activite::factory(10)->create(); */

        /*---------------------------- USER ---------------------------*/

        // Données réalistes basées sur des informations courantes sur Internet
        $users = [
            [
                'email' => 'alice.martin@example.com',
                'password' => Hash::make('alice123'),
                'numTel' => '0612345678',
                'nom' => 'Martin',
                'prenom' => 'Alice',
                'typeNotif' => 'email',
                'delaiAvantNotif' => fake()->dateTimeBetween('-1 week', '+1 week'),
                'superadmin' => 0,
            ],
            [
                'email' => 'bob.dupont@example.com',
                'password' => Hash::make('bob123'),
                'numTel' => '0623456789',
                'nom' => 'Dupont',
                'prenom' => 'Bob',
                'typeNotif' => 'sms',
                'delaiAvantNotif' => fake()->dateTimeBetween('-1 week', '+1 week'),
                'superadmin' => 0,
            ],
            [
                'email' => 'charlotte.durand@example.com',
                'password' => Hash::make('charlotte123'),
                'numTel' => '0634567890',
                'nom' => 'Durand',
                'prenom' => 'Charlotte',
                'typeNotif' => 'push',
                'delaiAvantNotif' => fake()->dateTimeBetween('-1 week', '+1 week'),
                'superadmin' => 1, // Superadmin
            ],
            [
                'email' => 'daniel.leclerc@example.com',
                'password' => Hash::make('daniel123'),
                'numTel' => '0645678901',
                'nom' => 'Leclerc',
                'prenom' => 'Daniel',
                'typeNotif' => 'email',
                'delaiAvantNotif' => fake()->dateTimeBetween('-1 week', '+1 week'),
                'superadmin' => 0,
            ],
            [
                'email' => 'emilie.robert@example.com',
                'password' => Hash::make('emilie123'),
                'numTel' => '0656789012',
                'nom' => 'Robert',
                'prenom' => 'Emilie',
                'typeNotif' => 'sms',
                'delaiAvantNotif' => fake()->dateTimeBetween('-1 week', '+1 week'),
                'superadmin' => 0,
            ],
            // Vous pouvez ajouter plus d'utilisateurs en suivant ce modèle
        ];

        // Insérer les utilisateurs dans la base de données
        foreach ($users as $user) {
            User::create($user);
        }


        /*---------------------------- ENTREPRISE ---------------------------*/

        $entreprises = [
            [
                'libelle' => 'TechCorp',
                'siren' => '123456789',
                'adresse' => '123 rue de la Tech, Paris',
                'metier' => 'Développement de logiciels',
                'description' => 'Entreprise spécialisée dans le développement de solutions logicielles.',
                'numTel' => '0123456789',
                'email' => 'contact@techcorp.fr',
                'cheminImg' => json_encode(['https://alliance-sciences-societe.fr/wp-content/uploads/2019/10/lorganisation-entreprise-optimiser-activites-comment.jpg','https://img.freepik.com/vecteurs-libre/illustration-concept-entreprise_114360-2581.jpg']),
                'publier' => 1,
                'typeRdv' => json_encode(['en ligne', 'en personne']),
                'idCreateur' => 1,
            ],
            [
                'libelle' => 'Innovative Solutions',
                'siren' => '987654321',
                'adresse' => '456 avenue de l\'innovation, Lyon',
                'metier' => 'Conseil en stratégie',
                'description' => 'Cabinet de conseil spécialisé dans la stratégie d\'innovation.',
                'numTel' => '0987654321',
                'email' => 'contact@innovativesolutions.fr',
                'cheminImg' => json_encode(['https://alliance-sciences-societe.fr/wp-content/uploads/2019/10/lorganisation-entreprise-optimiser-activites-comment.jpg']),
                'publier' => 1,
                'typeRdv' => json_encode(['en ligne']),
                'idCreateur' => 2,
            ],
            [
                'libelle' => 'HealthCare Pro',
                'siren' => '112233445',
                'adresse' => '789 boulevard de la santé, Marseille',
                'metier' => 'Soins de santé',
                'description' => 'Cliniques et services de soins médicaux.',
                'numTel' => '0654321098',
                'email' => 'contact@healthcarepro.fr',
                'cheminImg' => json_encode(['https://img.freepik.com/vecteurs-libre/illustration-concept-entreprise_114360-2581.jpg']),
                'publier' => 1,
                'typeRdv' => json_encode(['en personne']),
                'idCreateur' => 3,
            ],
            [
                'libelle' => 'Green Energy Co.',
                'siren' => '556677889',
                'adresse' => '321 avenue des énergies renouvelables, Nantes',
                'metier' => 'Énergie verte',
                'description' => 'Fournisseur de solutions énergétiques durables.',
                'numTel' => '0246801357',
                'email' => 'contact@greenenergyco.fr',
                'cheminImg' => json_encode(['https://img.freepik.com/vecteurs-libre/illustration-concept-entreprise_114360-2581.jpg','https://alliance-sciences-societe.fr/wp-content/uploads/2019/10/lorganisation-entreprise-optimiser-activites-comment.jpg']),
                'publier' => 1,
                'typeRdv' => json_encode(['en ligne', 'en personne']),
                'idCreateur' => 4,
            ],
            [
                'libelle' => 'EduTech Solutions',
                'siren' => '667788990',
                'adresse' => '555 avenue des écoles, Bordeaux',
                'metier' => 'Technologie éducative',
                'description' => 'Solutions technologiques pour l\'éducation.',
                'numTel' => '0369123456',
                'email' => 'contact@edutechsolutions.fr',
                'cheminImg' => json_encode(['https://wallpaperaccess.com/full/3915891.jpg','https://www.cours-gratuit.com/images/480/11327/id-11327-01.png']),
                'publier' => 1,
                'typeRdv' => json_encode(['en ligne']),
                'idCreateur' => 5,
            ],
        ];

        // Insérer les entreprises dans la base de données
        foreach ($entreprises as $entrepriseData) {
            $entreprise = Entreprise::create($entrepriseData);
        }

        /*---------------------------- ACTIVITE ---------------------------*/

        $activites = [
            [
                'libelle' => 'Développement Web Frontend',
                'duree' => '01:30:00',
                'idEntreprise' => 1, // TechCorp
            ],
            [
                'libelle' => 'Consultation en Stratégie Digitale',
                'duree' => '01:30:00',
                'idEntreprise' => 2, // Innovative Solutions
            ],
            [
                'libelle' => 'Soin Médical (Consultation)',
                'duree' => '01:30:00',
                'idEntreprise' => 3, // HealthCare Pro
            ],
            [
                'libelle' => 'Installation de Panneaux Solaires',
                'duree' => '01:30:00',
                'idEntreprise' => 4, // Green Energy Co.
            ],
            [
                'libelle' => 'Formation en Programmation Python',
                'duree' => '01:30:00',
                'idEntreprise' => 5, // TechCorp
            ],
            [
                'libelle' => 'Réparation Informatique',
                'duree' => '01:30:00',
                'idEntreprise' => 1, // TechCorp
            ],
            [
                'libelle' => 'Consultation en Énergie Durable',
                'duree' => '01:30:00',
                'idEntreprise' => 4, // Green Energy Co.
            ],
            [
                'libelle' => 'Coaching Professionnel',
                'duree' => '01:30:00',
                'idEntreprise' => 2, // Innovative Solutions
            ],
            [
                'libelle' => 'Suivi Préventif Santé',
                'duree' => '01:30:00',
                'idEntreprise' => 3, // HealthCare Pro
            ],
            [
                'libelle' => 'Développement d\'Applications Mobiles',
                'duree' => '01:30:00',
                'idEntreprise' => 1, // TechCorp
            ],
        ];

        // Insérer les activités dans la base de données
        foreach ($activites as $activiteData) {
            $activite = Activite::create($activiteData);

            // Optionnel : Lier chaque activité à l'utilisateur créateur de l'entreprise
            $createur = User::find($activite->entreprise->idCreateur);
            $activite->travailler_users()->attach($createur,['idEntreprise' => $activite->idEntreprise, 'statut' => 'Admin']);

        }

        /*---------------------------- PLAGE ---------------------------*/

        // Liste de 15 plages à insérer
        $plages = [
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '09:00:00',
                'heureFin' => '12:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1', 'table2', 'table3']),
                'entreprise_id' => 1, // TechCorp
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '14:00:00',
                'heureFin' => '17:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1', 'table2']),
                'entreprise_id' => 2, // Innovative Solutions
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '08:00:00',
                'heureFin' => '12:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1', 'table3']),
                'entreprise_id' => 3, // HealthCare Pro
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '10:00:00',
                'heureFin' => '13:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table2', 'table4']),
                'entreprise_id' => 4, // Green Energy Co.
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '15:00:00',
                'heureFin' => '18:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1', 'table5']),
                'entreprise_id' => 1, // TechCorp
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '09:30:00',
                'heureFin' => '12:30:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1', 'table3']),
                'entreprise_id' => 2, // Innovative Solutions
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '08:30:00',
                'heureFin' => '12:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table4', 'table6']),
                'entreprise_id' => 3, // HealthCare Pro
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '13:00:00',
                'heureFin' => '17:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1', 'table2']),
                'entreprise_id' => 4, // Green Energy Co.
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '10:00:00',
                'heureFin' => '14:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table3', 'table5']),
                'entreprise_id' => 1, // TechCorp
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '14:00:00',
                'heureFin' => '17:30:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table2', 'table6']),
                'entreprise_id' => 2, // Innovative Solutions
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '08:00:00',
                'heureFin' => '11:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1']),
                'entreprise_id' => 3, // HealthCare Pro
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '16:00:00',
                'heureFin' => '19:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table3', 'table4']),
                'entreprise_id' => 4, // Green Energy Co.
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '11:00:00',
                'heureFin' => '14:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table5', 'table6']),
                'entreprise_id' => 1, // TechCorp
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '09:00:00',
                'heureFin' => '12:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table1']),
                'entreprise_id' => 2, // Innovative Solutions
            ],
            [
                'datePlage' => fake()->dateTimeThisMonth(),
                'heureDeb' => '10:00:00',
                'heureFin' => '13:00:00',
                'interval' => '01:30:00',
                'planTables' => json_encode(['table2', 'table6']),
                'entreprise_id' => 3, // HealthCare Pro
            ],
        ];

        // Insérer les plages dans la base de données
        foreach ($plages as $plageData) {
            $plage = Plage::create($plageData);

            // Optionnel : Lier chaque plage à des activités
            $activites = Activite::inRandomOrder()->take(2)->pluck('id'); // Lier aléatoirement 2 activités
            $plage->activites()->attach($activites);
        }
    }
}
