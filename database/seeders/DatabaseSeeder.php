<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;
use App\Models\Users\{ User, UserPersonalInformation, UserType };
use App\Models\Residency\{ Country, Province, Town };
use App\Models\Profile\{ MaritalStatus };

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

            // Seed country

            $country = Country::create([

                'country' => 'Zambia',
                'nationality' => 'Zambian',
                'alpha_2_code' => 'ZM',
                'alpha_3_code' => 'ZMB',

            ]);

            
            // Seed province

            $province = Province::create([

                'name' => 'Copperbelt',
                'country_id' => $country->id

            ]);

            
    
            // Seed town

            $town = Town::create([

                'name' => 'Kitwe',
                'province_id' => $province->id

            ]);

            
            // Seed marital statuses

               $maritalStatus = MaritalStatus::create([
                    'status' => 'Single',
                 ]);

    
            // Seed user type

            $userType = UserType::create([

                'title' => 'super_admin',
                'name' => 'Super Admin',
                'level' => 1,

            ]);


            // Seed user

            $user = User::create([

                'first_name' => 'System',
                'last_name' => 'Generated',
                'email' => 'notice@zut.edu.zm',
                'user_type_id' => $userType->id, 
                
                'password' => bcrypt('secret'), 

                 ]);


    
            // Seed user personal information

            $userPersonInfo = UserPersonalInformation::create([

                'date_of_birth' => '1992-08-16',
                'street_main' => 'Developers highway',
                'mobile' => '0000000000',
                'marital_status_id' => $maritalStatus->id, 
                'town_id' => $town->id, 
                'province_id' => $province->id, 
                'country_id' => $country->id,
                'user_id' => $user->id

            ]);


            // Seed Settings table

            $settings = [
                ['type' => 'current_session', 'description' => '2022-2023'],
                ['type' => 'system_title', 'description' => 'ZUTS'],
                ['type' => 'system_name', 'description' => 'Zambia University College'],
                ['type' => 'term_ends', 'description' => '7/10/2018'],
                ['type' => 'term_begins', 'description' => '7/10/2018'],
                ['type' => 'phone', 'description' => '123456789'],
                ['type' => 'address', 'description' => 'school test address'],
                ['type' => 'system_email', 'description' => 'test@admin.com'],
                ['type' => 'alt_email', 'description' => ''],
                ['type' => 'email_host', 'description' => ''],
                ['type' => 'email_pass', 'description' => ''],
                ['type' => 'lock_exam', 'description' => '0'],
                ['type' => 'logo', 'description' => ''],
                ['type' => 'next_term_fees_j', 'description' => '20000'],
                ['type' => 'next_term_fees_pn', 'description' => '25000'],
                ['type' => 'next_term_fees_p', 'description' => '25000'],
                ['type' => 'next_term_fees_n', 'description' => '25600'],
                ['type' => 'next_term_fees_s', 'description' => '15600'],
                ['type' => 'next_term_fees_c', 'description' => '1600'],
            ];
    
            DB::table('settings')->insert($settings);
        
    
    

    }
}






