<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "
            INSERT INTO `membership_levels` (`id`, `name`, `parent_id`, `auto_discount`, `eligible_for_discount`, `required_textbox`, `created_at`, `updated_at`) VALUES
            (1,	'Professional',	0,	0,	0,	0,	NULL,	NULL),
            (2,	'Science Professional',	1,	0,	0,	0,	NULL,	NULL),
            (3,	'Medical / Health Care Professional',	1,	0,	0,	0,	NULL,	NULL),
            (4,	'Scientist (Asst/Assoc/Full Professor)',	2,	1,	1,	0,	NULL,	NULL),
            (5,	'Physician',	3,	0,	1,	0,	NULL,	NULL),
            (6,	'Physician\'s Assistant',	3,	0,	1,	0,	NULL,	NULL),
            (7,	'Pharmacist',	3,	0,	1,	0,	NULL,	NULL),
            (8,	'Nurse / Nurse Practitioner',	3,	0,	1,	0,	NULL,	NULL),
            (9,	'Education Professional',	1,	0,	0,	0,	NULL,	NULL),
            (10,	'Coroporate Professional',	1,	0,	0,	0,	NULL,	NULL),
            (11,	'College / University Instructor',	9,	1,	1,	0,	NULL,	NULL),
            (12,	'Elementary / Intermediate / High School Teacher',	9,	1,	1,	0,	NULL,	NULL),
            (13,	'Postdoctoral / Resident',	0,	1,	1,	0,	NULL,	NULL),
            (14,	'Student',	0,	0,	0,	0,	NULL,	NULL),
            (15,	'Graduate Student',	14,	1,	1,	0,	NULL,	NULL),
            (16,	'Medical Student',	14,	1,	1,	0,	NULL,	NULL),
            (17,	'Physician Asst Student',	14,	1,	1,	0,	NULL,	NULL),
            (18,	'Pharmacy Student',	14,	1,	1,	0,	NULL,	NULL),
            (19,	'Nursing Student',	14,	1,	1,	0,	NULL,	NULL),
            (20,	'Scientist Professional: Other',	2,	0,	1,	1,	NULL,	NULL),
            (21,	'Medical Professional: Other',	3,	0,	1,	1,	NULL,	NULL),
            (22,	'Education Professional: Other',	9,	0,	1,	1,	NULL,	NULL),
            (23,	'Coroporate Professional type',	10,	0,	1,	1,	NULL,	NULL),
            (24,	'Other',	14,	0,	0,	1,	NULL,	NULL);";
        DB::unprepared($sql);
    }
}
