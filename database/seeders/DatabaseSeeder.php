<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Users\{User, UserPersonalInformation, UserType};
use App\Models\Residency\{Country, Province, Town};
use App\Models\Profile\{MaritalStatus, Relationship};
use App\Models\Admissions\{AcademicPeriodIntake};
use App\Models\Academics\{Program, Course, StudyMode, CourseLevel, Qualification, Department, School, AssessmentType, PeriodType, Prerequisite, ProgramCourses};
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Seed Assessment
        AssessmentType::create(['name' => 'Exam']);

        // Seed Intakes
        $intakes = [
            ['name' => 'January'],
            ['name' => 'June'],
        ];

        AcademicPeriodIntake::insert($intakes);

        // Seed study modes
        $modes = [
            ['name' => 'Day', 'description' => 'lorem ipsum'],
            ['name' => 'Evening', 'description' => 'lorem ipsum'],
            ['name' => 'ODL', 'description' => 'lorem ipsum'],
        ];

        StudyMode::insert($modes);

        // Seed period types
        PeriodType::create(['name' => 'Academic', 'description' => 'lorem ipsum']);

        // Seed school
        $school = School::create(['name' => 'School of Engineering & Technology', 'slug' => 'engineering-technology', 'description' => 'lorem ipsum']);
        $school2 = School::create(['name' => 'School of Business', 'slug' => 'business', 'description' => 'lorem ipsum']);
        $school3 = School::create(['name' => 'School of Information & Communication Technology', 'slug' => 'information-communication-technology', 'description' => 'lorem ipsum']);


        // Seed user types
        $userTypes = [
            ['title' => 'super_admin', 'name' => 'Super Admin', 'level' => 1],
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
            'dialing_code' => '+260',

        ]);

        $this->call(ResidencySeeder::class);

        // Seed qualification
        $qualification = Qualification::create(['name' => 'Degree', 'slug' => 'degree']);

        // Seed department
        $dept = Department::create(['name' => 'Engineering & Technology', 'slug' => 'engineering-technology', 'description' => 'lorem ipsum', 'school_id' => $school->id]);

        // Seed Programs
        $programs = [

            ['code' => 'BIT', 'name' => 'Information Technology', 'department_id' => $dept->id, 'qualification_id' => $qualification->id, 'slug' => 'bit'],
            ['code' => 'BICTE', 'name' => 'ICT with Education', 'department_id' => $dept->id, 'qualification_id' => $qualification->id, 'slug' => 'bicte'],
            ['code' => 'BSE', 'name' => 'Software Engineering', 'department_id' => $dept->id, 'qualification_id' => $qualification->id, 'slug' => 'bse'],
            ['code' => 'BEEE', 'name' => 'Engineering in Electrical & Electronics', 'department_id' => $dept->id, 'qualification_id' => $qualification->id, 'slug' => 'beee']

        ];

        // Insert data into the 'user_types' table
        Program::insert($programs);


        // Seed Course Levels
        for ($i = 1; $i <= 5; $i++) {
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

            // BEEE Courses

            // First Semester
            ["code" => "MAT 1010", "name" => "Mathematics"],
            ["code" => "PHY 1010", "name" => "Applied Physics"],
            ["code" => "EEE 1030", "name" => "Introduction to Electrical Engineering"],
            ["code" => "MEC 1013", "name" => "Engineering Workshop Practice"],
            ["code" => "CMS 1011", "name" => "Communication Skills"],
            ["code" => "MEC 1012", "name" => "Engineering Graphics and CAD"],
            ["code" => "ICT 1011", "name" => "Introduction to Information Technology"],

            // Second Semester
            ["code" => "EMA 2010", "name" => "Engineering Mathematics I"],
            ["code" => "EEE 2021", "name" => "Electrical Circuits"],
            ["code" => "EEE 2040", "name" => "Analog Electronics"],
            ["code" => "ICT 2010", "name" => "Introduction to Programming"],
            ["code" => "MEC 2013", "name" => "Applied Mechanics and Strength of Materials"],
            ["code" => "EEE 2011", "name" => "Electrical Measurements and Instrumentation"],

            // Third Semester
            ["code" => "EEE 3060", "name" => "Electrical Machines I"],
            ["code" => "EEE 3030", "name" => "Digital Electronics and Logic Design"],
            ["code" => "EMA 3010", "name" => "Engineering Mathematics II"],
            ["code" => "EEE 3041", "name" => "Microprocessors Microcontrollers & Embedded Systems"],
            ["code" => "EEE 3031", "name" => "Renewable Energy Systems"],
            ["code" => "EEE 3021", "name" => "Control Systems Engineering"],
            ["code" => "EEE 3081", "name" => "Electrical Engineering Design & CAM"],

            // Fourth Semester
            ["code" => "EEE 4030", "name" => "Power Systems Engineering I"],
            ["code" => "EEE 4040", "name" => "Power Electronics"],
            ["code" => "EEE 4010", "name" => "Protection Engineering"],
            ["code" => "EMA 4021", "name" => "Numerical Computation"],
            ["code" => "EEE 4060", "name" => "Electrical Machines II"],
            ["code" => "EEE 4020", "name" => "Electromagnetic Field Theory"],
            ["code" => "EEE 4031", "name" => "Industrial Training"],
            ["code" => "BME 4061", "name" => "Biomedical Instrumentation (Elective)"],
            ["code" => "EEE 4071", "name" => "Robotics Engineering (Elective)"],
            ["code" => "EEE 4081", "name" => "Energy Management & Audit (Elective)"],

            // Fifth Semester
            ["code" => "EEE 5080", "name" => "Power Systems Engineering II"],
            ["code" => "EEE 5090", "name" => "Electric Drives"],
            ["code" => "EEE 5050", "name" => "Business Management and Entrepreneurship"],
            ["code" => "EEE 5000", "name" => "Final Year Project"],
            ["code" => "EEE 5071", "name" => "Industrial Automation and SCADA Systems"],
            ["code" => "ICT 5051", "name" => "Cyber Security for Automation, Control, & SCADA Systems (Elective)"],
            ["code" => "EEE 5081", "name" => "Power Plant Engineering (Elective)"],
            ["code" => "EEE 5091", "name" => "Smart Grid Technology"],
        ];

        // Insert data into the 'courses' table
        Course::insert($courses);

        // Seed program course


        $programCourses = [

            // BIT

            ['course_level_id' => 1, 'course_id' => 1, 'program_id' => 1],
            ['course_level_id' => 1, 'course_id' => 2, 'program_id' => 1],
            ['course_level_id' => 1, 'course_id' => 5, 'program_id' => 1],
            ['course_level_id' => 1, 'course_id' => 6, 'program_id' => 1],
            ['course_level_id' => 1, 'course_id' => 7, 'program_id' => 1],
            ['course_level_id' => 1, 'course_id' => 8, 'program_id' => 1],

            // BSE
            ['course_level_id' => 1, 'course_id' => 1, 'program_id' => 3],
            ['course_level_id' => 1, 'course_id' => 2, 'program_id' => 3],
            ['course_level_id' => 1, 'course_id' => 4, 'program_id' => 3],
            ['course_level_id' => 1, 'course_id' => 5, 'program_id' => 3],
            ['course_level_id' => 1, 'course_id' => 6, 'program_id' => 3],

            // BICTE
            ['course_level_id' => 1, 'course_id' => 1, 'program_id' => 2],
            ['course_level_id' => 1, 'course_id' => 2, 'program_id' => 2],
            ['course_level_id' => 1, 'course_id' => 9, 'program_id' => 2],
            ['course_level_id' => 1, 'course_id' => 10, 'program_id' => 2],

            // BEEE
            // First Semester
            ['course_level_id' => 1, 'course_id' => 13, 'program_id' => 4],  // MAT 1010 - Mathematics
            ['course_level_id' => 1, 'course_id' => 14, 'program_id' => 4],  // PHY 1010 - Applied Physics
            ['course_level_id' => 1, 'course_id' => 15, 'program_id' => 4],  // EEE 1030 - Introduction to Electrical Engineering
            ['course_level_id' => 1, 'course_id' => 16, 'program_id' => 4],  // MEC 1013 - Engineering Workshop Practice
            ['course_level_id' => 1, 'course_id' => 17, 'program_id' => 4],  // CMS 1011 - Communication Skills
            ['course_level_id' => 1, 'course_id' => 18, 'program_id' => 4],  // MEC 1012 - Engineering Graphics and CAD
            ['course_level_id' => 1, 'course_id' => 19, 'program_id' => 4],  // ICT 1011 - Introduction to Information Technology

            // Second Semester
            ['course_level_id' => 2, 'course_id' => 20, 'program_id' => 4],  // EMA 2010 - Engineering Mathematics I
            ['course_level_id' => 2, 'course_id' => 21, 'program_id' => 4],  // EEE 2021 - Electrical Circuits
            ['course_level_id' => 2, 'course_id' => 22, 'program_id' => 4],  // EEE 2040 - Analog Electronics
            ['course_level_id' => 2, 'course_id' => 23, 'program_id' => 4],  // ICT 2010 - Introduction to Programming
            ['course_level_id' => 2, 'course_id' => 24, 'program_id' => 4],  // MEC 2013 - Applied Mechanics and Strength of Materials
            ['course_level_id' => 2, 'course_id' => 25, 'program_id' => 4],  // EEE 2011 - Electrical Measurements and Instrumentation

            // Third Semester
            ['course_level_id' => 3, 'course_id' => 26, 'program_id' => 4],  // EEE 3060 - Electrical Machines I
            ['course_level_id' => 3, 'course_id' => 27, 'program_id' => 4],  // EEE 3030 - Digital Electronics and Logic Design
            ['course_level_id' => 3, 'course_id' => 28, 'program_id' => 4],  // EMA 3010 - Engineering Mathematics II
            ['course_level_id' => 3, 'course_id' => 29, 'program_id' => 4],  // EEE 3041 - Microprocessors Microcontrollers & Embedded Systems
            ['course_level_id' => 3, 'course_id' => 30, 'program_id' => 4],  // EEE 3031 - Renewable Energy Systems
            ['course_level_id' => 3, 'course_id' => 31, 'program_id' => 4],  // EEE 3021 - Control Systems Engineering
            ['course_level_id' => 3, 'course_id' => 32, 'program_id' => 4],  // EEE 3081 - Electrical Engineering Design & CAM

            // Fourth Semester
            ['course_level_id' => 4, 'course_id' => 33, 'program_id' => 4],  // EEE 4030 - Power Systems Engineering I
            ['course_level_id' => 4, 'course_id' => 34, 'program_id' => 4],  // EEE 4040 - Power Electronics
            ['course_level_id' => 4, 'course_id' => 35, 'program_id' => 4],  // EEE 4010 - Protection Engineering
            ['course_level_id' => 4, 'course_id' => 36, 'program_id' => 4],  // EMA 4021 - Numerical Computation
            ['course_level_id' => 4, 'course_id' => 37, 'program_id' => 4],  // EEE 4060 - Electrical Machines II
            ['course_level_id' => 4, 'course_id' => 38, 'program_id' => 4],  // EEE 4020 - Electromagnetic Field Theory



            // Fifth Semester
            ['course_level_id' => 5, 'course_id' => 39, 'program_id' => 4],  // EEE 5080 - Power Systems Engineering II
            ['course_level_id' => 5, 'course_id' => 40, 'program_id' => 4],  // EEE 5090 - Electric Drives
            ['course_level_id' => 5, 'course_id' => 41, 'program_id' => 4],  // EEE 5050 - Business Management and Entrepreneurship
            ['course_level_id' => 5, 'course_id' => 42, 'program_id' => 4],  // EEE 5000 - Final Year Project
            ['course_level_id' => 5, 'course_id' => 43, 'program_id' => 4],  // EEE 5071 - Industrial Automation and SCADA Systems
            ['course_level_id' => 5, 'course_id' => 44, 'program_id' => 4],  // ICT 5051 - Cyber Security for Automation, Control, & SCADA Systems (Elective)
            ['course_level_id' => 5, 'course_id' => 45, 'program_id' => 4],  // EEE 5081 - Power Plant Engineering (Elective)
            ['course_level_id' => 5, 'course_id' => 46, 'program_id' => 4],  // EEE 5091 - Smart Grid Technology



        ];

        ProgramCourses::insert($programCourses);

        // Seed course prerequisites

        $prerequisites = [
            ['course_id' => 20, 'prerequisite_course_id' => 13],
            ['course_id' => 21, 'prerequisite_course_id' => 15],
            ['course_id' => 24, 'prerequisite_course_id' => 14],
            ['course_id' => 28, 'prerequisite_course_id' => 22],
            ['course_id' => 29, 'prerequisite_course_id' => 22],
            ['course_id' => 31, 'prerequisite_course_id' => 20],
            ['course_id' => 33, 'prerequisite_course_id' => 30],
            ['course_id' => 37, 'prerequisite_course_id' => 26],
            ['course_id' => 36, 'prerequisite_course_id' => 28],
            ['course_id' => 38, 'prerequisite_course_id' => 26],
            ['course_id' => 39, 'prerequisite_course_id' => 33],
            ['course_id' => 40, 'prerequisite_course_id' => 37],
            ['course_id' => 43, 'prerequisite_course_id' => 35],
            ['course_id' => 29, 'prerequisite_course_id' => 21],
            ['course_id' => 38, 'prerequisite_course_id' => 28],

        ];

        Prerequisite::insert($prerequisites);

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
        $province = Province::where('country_id', $country->id)->first();

        // Create "Other" province
        Province::create(['name' => 'Other', 'country_id' => null]);

        // Seed town
        $town = Town::where('province_id', $province->id)->first();

        // Create "Other" town
        Town::create(['name' => 'Other', 'province_id' => null]);

        // Seed marital statuses

        MaritalStatus::insert([
            ['status' => 'Single'],
            ['status' => 'Divorced'],
            ['status' => 'Married'],
            ['status' => 'Widowed'],
            ['status' => 'Separated'],
        ]);

        // Seed next of kin relationships

        Relationship::insert([
            ['relationship' => 'Parent'],
            ['relationship' => 'Spouse'],
            ['relationship' => 'Child'],
            ['relationship' => 'Uncle'],
            ['relationship' => 'Aunt'],
            ['relationship' => 'Nephew'],
            ['relationship' => 'Niece'],
            ['relationship' => 'Grandparent'],
            ['relationship' => 'Sibling'],
            ['relationship' => 'Cousin'],
            ['relationship' => 'Partner'],
            ['relationship' => 'Other'],
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

        $maritalStatus = MaritalStatus::first();

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
            ['type' => 'system_title', 'description' => 'ZUT-SMS'],
            ['type' => 'system_name', 'description' => 'Zambia University College of Technology'],
            ['type' => 'phone', 'description' => '+260955155756'],
            ['type' => 'po_box', 'description' => 'P.O. Box 71601'],
            ['type' => 'address', 'description' => '2893 Kalewa Road'],
            ['type' => 'township', 'description' => 'Northrise'],
            ['type' => 'town', 'description' => 'Ndola'],
            ['type' => 'province', 'description' => 'Copperbelt'],
            ['type' => 'country', 'description' => 'Zambia'],
            ['type' => 'system_email', 'description' => 'test@admin.com'],
            ['type' => 'alt_email', 'description' => ''],
            ['type' => 'email_host', 'description' => ''],
            ['type' => 'email_pass', 'description' => ''],
            ['type' => 'lock_exam', 'description' => '0'],
            ['type' => 'logo', 'description' => ''],
            ['type' => 'term_ends', 'description' => '7/10/2018'],
            ['type' => 'term_begins', 'description' => '7/10/2018'],
            ['type' => 'next_term_fees_j', 'description' => '20000'],
            ['type' => 'next_term_fees_pn', 'description' => '25000'],
            ['type' => 'next_term_fees_p', 'description' => '25000'],
            ['type' => 'next_term_fees_n', 'description' => '25600'],
            ['type' => 'next_term_fees_s', 'description' => '15600'],
            ['type' => 'next_term_fees_c', 'description' => '1600'],
        ];

        DB::table('settings')->insert($settings);

        $payment_methods_usage_instructions['airtel_money'] = "You can make payments using Airtel Money via USSD as follows: \n"
            . "Step 1: Dial *115# \n"
            . "Step 2: Select option 4 \"Make Payment\" \n"
            . "Step 3: Select option 7 \"School fee payments\" \n"
            . "Step 4: Select option 1 \"School Pay\" \n"
            . "Step 5: Select option 1 \"Pay fees\" \n"
            . "Step 6: Enter student number in the format \"ZUT-XXXXXXX\" \n"
            . "Step 7: Enter amount in Kwacha \n"
            . "Step 8: Enter your 4-digit PIN to confirm payment";

        $payment_methods_usage_instructions['indo_zambia_bank'] = "Indo Zambia Bank has three options for making payments: "
            . "Walk-in, Agency Banking and Mobile Banking. To make payments using any of the three options:\n"
            . "Step 1: Provide or enter your valid student number \n"
            . "Step 2: Confirm your student number and name \n"
            . "Step 3: Enter the amount or provide cash for payment \n"
            . "Step 4: Collect your receipt or download your e-receipt for your records";

        // Seed Payment Methods table
        $paymentMethods = [
            [
                'name' => 'Airtel Money',
                'usage_instructions' => $payment_methods_usage_instructions['airtel_money'],
            ],
            [
                'name' => 'Indo Zambia Bank',
                'usage_instructions' => $payment_methods_usage_instructions['indo_zambia_bank'],
            ]
        ];

        DB::table('payment_methods')->insert($paymentMethods);
    }
}
