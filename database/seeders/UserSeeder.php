<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $adminRole = Role::where('name', 'admin')->first();
    $user = User::updateOrCreate([
      'email' => 'admin@app.com',
    ], [
      'name' => 'Admin',
      'email' => 'admin@app.com',
      'password' => Hash::make('password'),
    ]);
    $user->assignRole($adminRole);
  }
}
