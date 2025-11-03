<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user IDs (patients)
        $userIds = DB::table('users')->where('usertype', 'user')->pluck('id')->toArray();
        
        // Get dentist IDs
        $dentistIds = DB::table('users')->where('usertype', 'admin')->pluck('id')->toArray();

        if (empty($userIds) || empty($dentistIds)) {
            $this->command->warn('No users or dentists found. Please run UserSeeder first.');
            return;
        }

        $reviews = [
            [
                'rating' => 5,
                'message' => 'Excellent service! Dr. Smith was very professional and gentle. The root canal treatment was painless and the staff were very accommodating. Highly recommended!',
            ],
            [
                'rating' => 5,
                'message' => 'Best dental clinic in town! Very clean facility and modern equipment. The dentist explained everything clearly and made me feel comfortable throughout the procedure.',
            ],
            [
                'rating' => 4,
                'message' => 'Great experience overall. The teeth cleaning was thorough and the hygienist was very knowledgeable. Only minor wait time, but worth it!',
            ],
            [
                'rating' => 5,
                'message' => 'I was nervous about getting my wisdom tooth extracted, but the team made it so easy. Quick procedure, minimal discomfort, and great post-op care instructions.',
            ],
            [
                'rating' => 5,
                'message' => 'Amazing teeth whitening results! My teeth are several shades whiter and the treatment was quick. The staff were friendly and professional.',
            ],
            [
                'rating' => 4,
                'message' => 'Very satisfied with my composite filling. The tooth looks natural and the dentist was meticulous with the work. Reasonable pricing too.',
            ],
            [
                'rating' => 5,
                'message' => 'The clinic is very hygienic and follows strict sanitation protocols. I felt safe during my visit. The dental check-up was comprehensive and professional.',
            ],
            [
                'rating' => 4,
                'message' => 'Good service and caring staff. The dentist took time to explain my treatment options. The only downside was the appointment scheduling could be improved.',
            ],
            [
                'rating' => 5,
                'message' => 'Fantastic experience! The cavity filling was done perfectly and painlessly. I appreciate how gentle and patient the dentist was with me.',
            ],
            [
                'rating' => 5,
                'message' => 'Highly professional dental clinic! The crown installation was perfect and comfortable. The dentist has excellent attention to detail.',
            ],
            [
                'rating' => 4,
                'message' => 'Very pleased with the gum treatment results. My gums are healthier now and the bleeding has stopped. The oral hygiene instructions were very helpful.',
            ],
            [
                'rating' => 5,
                'message' => 'Outstanding service from start to finish! The orthodontic consultation was thorough and the treatment plan was clearly explained. Looking forward to my braces!',
            ],
            [
                'rating' => 5,
                'message' => 'The best dental care I have ever received! The staff are so friendly and welcoming. The clinic is clean, modern, and well-equipped.',
            ],
            [
                'rating' => 4,
                'message' => 'Good dental clinic with experienced dentists. The root canal treatment was successful and healing well. Would recommend to friends and family.',
            ],
            [
                'rating' => 5,
                'message' => 'Exceptional dental care! The dentist was very gentle and made sure I was comfortable during the entire procedure. The results exceeded my expectations.',
            ],
            [
                'rating' => 5,
                'message' => 'I love this clinic! They really care about their patients. The dental cleaning was thorough and my teeth feel amazing. Will definitely come back!',
            ],
            [
                'rating' => 4,
                'message' => 'Professional and courteous staff. The dental X-rays were quick and the dentist explained everything in detail. Very satisfied with the service.',
            ],
            [
                'rating' => 5,
                'message' => 'Best decision to come here! The tooth extraction was quick and virtually painless. The aftercare instructions were clear and healing went smoothly.',
            ],
            [
                'rating' => 5,
                'message' => 'Top-notch dental clinic! The teeth whitening treatment gave me the confidence to smile again. Thank you to the wonderful team!',
            ],
            [
                'rating' => 4,
                'message' => 'Great dental care at reasonable prices. The filling work was excellent and the dentist was very skilled. Minor wait but overall good experience.',
            ],
        ];

        // Insert reviews
        foreach ($reviews as $review) {
            DB::table('ratings')->insert([
                'rating' => $review['rating'],
                'message' => $review['message'],
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'updated_at' => now(),
            ]);
        }
    }
}
