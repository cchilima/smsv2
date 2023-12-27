<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Users\{ User, UserPersonalInformation, UserType };
use App\Models\Residency\{ Country, Province, Town };
use App\Models\Profile\{ MaritalStatus, Relationship };
use App\Models\Academics\{ Program, Course, CourseLevel, Qualification, Department, School };

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

            // Seed school
            $school = School::create(['name' => 'School of Engineering & Technology', 'description' => 'lorem ipsum']);

            // Seed user types
            $userTypes = [
                ['title' => 'super_admin', 'name' => 'Super Admin', 'level' => 1]  ,
                ['title' => 'instructor', 'name' => 'Instructor', 'level' => 2],
                ['title' => 'student', 'name' => 'Student', 'level' => 3],
                ['title' => 'accounts', 'name' => 'Accounts', 'level' => 4],
                ['title' => 'hod', 'name' => 'HOD', 'level' => 5],
                ['title' => 'director', 'name' => 'Director', 'level' => 6]
            ];       
            
            // Insert data into the 'user_types' table
            UserType::insert($userTypes);

            // Seed country
            $country = Country::create([

                'country' => 'Zambia',
                'nationality' => 'Zambian',
                'alpha_2_code' => 'ZM',
                'alpha_3_code' => 'ZMB',

            ]);

            // Seed qualification
            $qualification = Qualification::create(['name' => 'Degree', 'slug' => 'degree']);

            // Seed department
            $dept = Department::create(['name' => 'Engineering & Technology', 'slug' => 'engineering-technology', 'description' => 'lorem ipsum', 'school_id' => $school->id]);

            // Seed Programs
            $programs = [

                ['code' => 'BIT', 'name' => 'Information Technology', 'department_id' => $dept->id, 'qualification_id' => $qualification->id, 'slug' => 'bit'],
                ['code' => 'BICTE', 'name' => 'ICT with Education', 'department_id' => $dept->id, 'qualification_id' => $qualification->id, 'slug' => 'bicte'],
                ['code' => 'BSE', 'name' => 'Software Engineering', 'department_id' => $dept->id, 'qualification_id' => $qualification->id, 'slug' => 'bse']

            ];

            // Insert data into the 'user_types' table
            Program::insert($programs);
          

             // Seed Course Levels
            for ($i=0; $i <=4 ; $i++) { 
                CourseLevel::create(['name' => "year $i"]);
            }

            // Seed Courses

            $courses = [

                // BSE Courses
                ["code" => "ICT 1100", "name" => "Introduction to IT"],
                ["code" => "BIT 1111", "name" => "Communication & Technical Writing"],
                ["code" => "ICT 1111", "name" => "Programming with C++"],
                ["code" => "BSE 1011", "name" => "Software requirements engineering"],
                ["code" => "BIT 1140", "name" => "Introduction to networking"],
    
                // BIT Courses
                ["code" => "BIT 1120", "name" => "Mathematics"],
                ["code" => "BIT 1160", "name" => "Introduction to systems analysis and design"],
                ["code" => "BIT 1131", "name" => "Fundamentals of Electrical and Electronics"],
    
                // BICTE Courses
                ["code" => "EDU 1120", "name" => "Education and development"],
                ["code" => "BSS 1160", "name" => "Business studies"],
                ["code" => "ICT 1110", "name" => "Introduction to programming"],
                ["code" => "ICT 1131", "name" => "Ethics and cyber law"],
            ];
    
            // Insert data into the 'courses' table
            Course::insert($courses);

            // Seed instructors

            $instructors = [
                [
                    "first_name" => "John",
                    "last_name" => "Doe",
                    "email" => "john.doe@zut.edu.zm",
                    "gender" => "male",
                    "password" => Hash::make("secret"),
                    "user_type_id" => UserType::where("title", "instructor")->first()->id,
                ],
                [
                    "first_name" => "Jane",
                    "last_name" => "Smith",
                    "email" => "jane.smith@zut.edu.zm",
                    "gender" => "female",
                    "password" => Hash::make("secret"),
                    "user_type_id" => UserType::where("title", "instructor")->first()->id,
                ],
                [
                    "first_name" => "Michael",
                    "last_name" => "Johnson",
                    "email" => "michael.johnson@zut.edu.zm",
                    "gender" => "male",
                    "password" => Hash::make("secret"),
                    "user_type_id" => UserType::where("title", "instructor")->first()->id,
                ],
                [
                    "first_name" => "Emily",
                    "last_name" => "Brown",
                    "email" => "emily.brown@zut.edu.zm",
                    "gender" => "female",
                    "password" => Hash::make("secret"),
                    "user_type_id" => UserType::where("title", "instructor")->first()->id,
                ],
                [
                    "first_name" => "David",
                    "last_name" => "Miller",
                    "email" => "david.miller@zut.edu.zm",
                    "gender" => "male",
                    "password" => Hash::make("secret"),
                    "user_type_id" => UserType::where("title", "instructor")->first()->id,
                ],
            ];
    
            // Insert data into the 'instructors' table
            User::insert($instructors);

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


            // Seed marital statuses

               $relationship = Relationship::create([
                'relationship' => 'Parent',
             ]);



            // Seed user

            $user = User::create([

                'first_name' => 'System',
                'last_name' => 'Generated',
                'email' => 'notice@zut.edu.zm',
                'user_type_id' => 1,

                'password' => bcrypt('secret'),

                 ]);



            // Seed user personal information

            $userPersonInfo = UserPersonalInformation::create([

                'date_of_birth' => '1992-08-16',
                'street_main' => 'Developers highway',
                'mobile' => '0000000000',
                'nrc' => '109100/52/1',
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






