<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Service;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if the settings table exists and is empty before seeding
        if (Schema::hasTable('settings') && Setting::count() === 0) {
            Setting::factory()->create();
        }

        // Check if the users table exists and is empty before creating user, permissions, and roles
        if (Schema::hasTable('users') && User::count() === 0) {
            $user = $this->createInitialUserWithPermissions();
            $this->createCategoriesAndServices($user);
        }
    }

    protected function createInitialUserWithPermissions()
    {
        // Define permissions list
        $permissions = [
            // Permission Management
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Appointment Management
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.delete',

            // Category Management
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Service Management
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',

            // Settings
            'settings.edit'
        ];

        // Create each permission if it doesn't exist
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Create roles if they do not exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $subscriberRole = Role::firstOrCreate(['name' => 'subscriber']);

        // Assign all permissions to the 'admin' role
        $adminRole->syncPermissions(Permission::all());

        // Create the initial admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'status' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
        ]);

        // Assign specific permissions to the 'moderator' role
        $moderatorPermissions = [
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.delete',

            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
        ];

        $moderatorRole->syncPermissions(Permission::whereIn('name', $moderatorPermissions)->get());

        // Assign the 'admin' role to the user
        $user->assignRole($adminRole);



        // Create admin as employee with additional details
        $employee = Employee::create([
            'user_id' => $user->id,
            'days' => [
                "monday" => ["06:00-22:00"],
                "tuesday" => ["06:00-15:00", "16:00-22:00"],
                "wednesday" => ["09:00-12:00", "14:00-23:00"],
                "thursday" => ["09:00-20:00"],
                "friday" => ["06:00-17:00"],
                "saturday" => ["05:00-18:00"]
            ],
            'slot_duration' => 30
        ]);

        return $user;
    }

    protected function createCategoriesAndServices(User $user)
    {
        // Create categories
        $categories = [
            [
                'title' => 'Recording',
                'slug' => 'recording',
                'body' => 'Înregistrare audio profesională. Captarea sunetelor vocale și instrumentale utilizând echipamente de înaltă fidelitate.'
            ],
            [
                'title' => 'Mix & Mastering',
                'slug' => 'mix-mastering',
                'body' => 'Crearea unui mixaj echilibrat și aplicarea tehnicilor de masterizare pentru pregătirea pieselor finale.'
            ],
            [
                'title' => 'Producție muzicală',
                'slug' => 'productie-muzicala',
                'body' => 'Dezvoltarea conceptelor muzicale, de la compoziție până la aranjament.'
            ]
        ];

        $services = [];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create 2 services for each category
            switch ($category->title) {
                case 'Recording':
                    $services = [
                        [
                            'title' => 'Înregistrare Vocală',
                            'slug' => 'inregistrare-vocala',
                            'price' => 999,
                            'excerpt' => 'Captarea vocii cu microfoane profesionale, într-un mediu acustic optimizat pentru claritate și detaliu.'
                        ],
                        [
                            'title' => 'Înregistrare Instrumentală',
                            'slug' => 'inregistrare-instrumentala',
                            'price' => 899,
                            'excerpt' => 'Înregistrarea instrumentelor acustice sau electrice folosind echipamente de ultimă generație pentru un sunet autentic.'
                        ]
                    ];
                    break;

                case 'Mix & Mastering':
                    $services = [
                        [
                            'title' => 'Mixaj profesional',
                            'slug' => 'mixaj-profesional',
                            'price' => 1299,
                            'excerpt' => 'Procesarea și echilibrarea tuturor elementelor audio pentru a obține un sunet coerent și clar.'
                        ],
                        [
                            'title' => 'Mastering audio',
                            'slug' => 'mastering-audio',
                            'price' => 1499,
                            'excerpt' => 'Optimizarea finală a piesei pentru distribuție, asigurând volum, claritate și compatibilitate pe toate platformele.'
                        ]
                    ];
                    break;

                case 'Producție muzicală':
                    $services = [
                        [
                            'title' => 'Compoziție muzicală',
                            'slug' => 'compozitie-muzicala',
                            'price' => 1499,
                            'excerpt' => 'Crearea de melodii originale și linii melodice adaptate stilului dorit.'
                        ],
                        [
                            'title' => 'Aranjamente muzicale',
                            'slug' => 'aranjamente-muzicale',
                            'price' => 1999,
                            'excerpt' => 'Dezvoltarea și structurarea pieselor muzicale pentru a evidenția fiecare element sonor.'
                        ]
                    ];
                    break;
            }

            foreach ($services as $serviceData) {
                Service::create([
                    'title' => $serviceData['title'],
                    'slug' => $serviceData['slug'],
                    'price' => $serviceData['price'],
                    'excerpt' => $serviceData['excerpt'],
                    'category_id' => $category->id
                ]);
            }
        }

        // Attach all services to the employee (not directly to user)
        if ($user->employee) {
            $allServices = Service::all();
            $user->employee->services()->sync($allServices->pluck('id'));
        }
    }
}
