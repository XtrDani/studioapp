<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ModeratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moderators = [
            [
                'name' => 'Moderator One',
                'email' => 'moderator1@example.com',
                'password' => Hash::make('password1'),
            ],
            [
                'name' => 'Moderator Two',
                'email' => 'moderator2@example.com',
                'password' => Hash::make('password2'),
            ],
            [
                'name' => 'Moderator Three',
                'email' => 'moderator3@example.com',
                'password' => Hash::make('password3'),
            ],
            [
                'name' => 'Moderator Four',
                'email' => 'moderator4@example.com',
                'password' => Hash::make('password4'),
            ],
        ];

        foreach ($moderators as $moderator) {
            $user = User::updateOrCreate(
                ['email' => $moderator['email']],
                [
                    'name' => $moderator['name'],
                    'password' => $moderator['password'],
                ]
            );
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('moderator');
            }
        }
    }
}
