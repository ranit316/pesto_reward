<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = [
            [
            'company_name' => 'Ilite',
            'brand_title' => 'Ilite',
            'company_address' => 'chingrighata',
            'bank_name' => 'hdfc',
            'bank_acc_number' =>'4226555',
            'bank_ifsc' => '635656565',
            'gstin' => 'fdgd6555',
            'logo' => 'images/company/logo1.png',
            'status' => 'active',
            ],
            [
            'company_name' => 'Raju Bhai Group',
            'brand_title' => 'Raju Bhai Group',
            'company_address' => 'chinarpark',
            'bank_name' => 'icici',
            'bank_acc_number' =>'422655555',
            'bank_ifsc' => '6356565',
            'gstin' => 'fdgd6444',
            'logo' => 'images/company/logo2.png',
            'status' => 'active',
            ],
            
    ];
    
    foreach ($company as $entity)
    {
        Company::create($entity);
    }
    }
}
