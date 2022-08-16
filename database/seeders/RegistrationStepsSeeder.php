<?php

namespace Database\Seeders;

use App\Models\RegistrationStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationStepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegistrationStep::create([
            'short_name' => 'approved_to_start',
            'company_visible' => 0,
            'company_name' => 'Approved To Start',
            'admin_name' => 'Approved To Start'
        ]);
        RegistrationStep::create([
            'short_name' => 'pc_submit_audit',
            'company_visible' => 1,
            'company_name' => 'Powder Coater To Submit Audit',
            'admin_name' => 'Powder Coater To Submit Audit'
        ]);
        RegistrationStep::create([
            'short_name' => 'audit_approved_by_am',
            'company_visible' => 1,
            'company_name' => 'Audit Approved By AM',
            'admin_name' => 'Audit Approved By AM'
        ]);
        RegistrationStep::create([
            'short_name' => 'audit_approved_by_ts',
            'company_visible' => 1,
            'company_name' => 'Audit Approved By Tech Service',
            'admin_name' => 'Audit Approved By Tech Service'
        ]);
        RegistrationStep::create([
            'short_name' => 'submit_testing_sample',
            'company_visible' => 1,
            'company_name' => 'Powder Coater To Submit Samples',
            'admin_name' => 'Powder Coater To Submit Samples'
        ]);
        RegistrationStep::create([
            'short_name' => 'samples_in_testing',
            'company_visible' => 1,
            'company_name' => 'Samples In Testing At Dulux',
            'admin_name' => 'Samples In Testing At Dulux'
        ]);
        RegistrationStep::create([
            'short_name' => 'samples_approved_by_dulux',
            'company_visible' => 1,
            'company_name' => 'Samples Approved By Dulux',
            'admin_name' => 'Samples Approved By Dulux'
        ]);

        RegistrationStep::create([
            'short_name' => 'sign_member_agreement',
            'company_visible' => 1,
            'company_name' => 'Powder Coater To Sign Agreement',
            'admin_name' => 'Powder Coater To Sign Agreement'
        ]);
    }
}
