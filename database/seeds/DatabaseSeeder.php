<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param \Faker\Generator $faker
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class,50)->create();



//        DB::table('weights')->insert([
//            [
//                'category' => 'Homework',
//                'section_id' => 14,
//                'student_id' => 3,
//                'points' => 90,
//            ],
//            [
//                'category' => 'Test',
//                'section_id' => 14,
//                'student_id' => 3,
//                'points' => 100,
//            ],
//            [
//                'category' => 'Participation',
//                'section_id' => 14,
//                'student_id' => 3,
//                'points' => 10,
//            ]
//        ]);

//
//        DB::table('grades')->insert([
//            [
//                'weight_id' => 1,
//                'student_id' => 3,
//                'grade' => 70,
//                'assignment' => 'Btree'
//            ],
//            [
//                'weight_id' => 1,
//                'student_id' => 3,
//                'grade' => 100,
//                'assignment' => 'HashMap'
//            ],
//
//
//            [
//                'weight_id' => 2,
//                'student_id' => 3,
//                'grade' => 100,
//                'assignment' => 'Exam 1'
//            ],
//            [
//                'weight_id' => 2,
//                'student_id' => 3,
//                'grade' => 100,
//                'assignment' => 'Exam 2'
//            ],
//
//            [
//                'weight_id' => 3,
//                'student_id' => 3,
//                'grade' => 20,
//                'assignment' => 'First Entry'
//            ]
//        ]);

    }
}
