<?php

namespace Database\Seeders;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use App\Models\WidgetSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create user
        $user = User::create([
            'name' => 'Jason Frye',
            'email' => 'jason@jasonfrye.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create creator profile with Pro plan
        $creator = Creator::create([
            'user_id' => $user->id,
            'collection_url' => 'jason-frye',
            'display_name' => 'Jason Frye',
            'website' => 'https://jasonfrye.com',
            'plan' => 'pro',
            'subscription_status' => 'active',
            'show_branding' => false,
        ]);

        // Create widget settings with carousel layout
        WidgetSetting::create([
            'creator_id' => $creator->id,
            'theme' => 'light',
            'primary_color' => '#4f46e5',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'border_radius' => '8',
            'layout' => 'carousel',
            'limit' => 20,
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_branding' => false,
        ]);

        // Create 20 diverse reviews
        $reviews = [
            [
                'author_name' => 'Sarah Johnson',
                'author_title' => 'Product Manager',
                'content' => 'Working with Jason has been an absolute game-changer for our business. His attention to detail and ability to deliver exactly what we needed on time was impressive.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Michael Chen',
                'author_title' => 'CEO at TechStart',
                'content' => 'Exceptional work! Jason understood our requirements perfectly and delivered a solution that exceeded our expectations. Highly recommend!',
                'rating' => 5,
            ],
            [
                'author_name' => 'Emily Rodriguez',
                'author_title' => 'Marketing Director',
                'content' => 'Jason is a true professional. The project was completed ahead of schedule and the quality was outstanding. Will definitely work with him again.',
                'rating' => 5,
            ],
            [
                'author_name' => 'David Thompson',
                'author_title' => 'Founder',
                'content' => 'Great experience overall! Jason was responsive, knowledgeable, and delivered exactly what we discussed. Very happy with the results.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Lisa Anderson',
                'author_title' => 'Operations Manager',
                'content' => 'I was impressed by Jason\'s problem-solving skills. When we encountered unexpected challenges, he found creative solutions quickly.',
                'rating' => 5,
            ],
            [
                'author_name' => 'James Wilson',
                'author_title' => 'CTO',
                'content' => 'Excellent technical expertise combined with great communication skills. Jason made the entire process smooth and stress-free.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Maria Garcia',
                'author_title' => 'Design Lead',
                'content' => 'Jason has an eye for detail and really cares about delivering quality work. Our collaboration was seamless and productive.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Robert Taylor',
                'author_title' => 'Project Manager',
                'content' => 'Fantastic work! Jason was proactive in identifying potential issues and suggesting improvements. True partnership approach.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Amanda White',
                'author_title' => 'VP of Product',
                'content' => 'I\'ve worked with many developers, but Jason stands out for his professionalism and quality of work. Highly skilled and reliable.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Christopher Lee',
                'author_title' => 'Business Owner',
                'content' => 'Jason delivered exactly what we needed. The communication was clear, and the final product works perfectly. Very satisfied!',
                'rating' => 5,
            ],
            [
                'author_name' => 'Jessica Brown',
                'author_title' => 'Head of Engineering',
                'content' => 'Working with Jason was a pleasure. His technical knowledge is solid and he\'s great at explaining complex concepts in simple terms.',
                'rating' => 4,
            ],
            [
                'author_name' => 'Daniel Martinez',
                'author_title' => 'Product Owner',
                'content' => 'Very good experience. Jason was responsive to feedback and made adjustments quickly. The end result met all our requirements.',
                'rating' => 4,
            ],
            [
                'author_name' => 'Rachel Kim',
                'author_title' => 'Tech Lead',
                'content' => 'Jason did a great job on our project. Professional, skilled, and easy to work with. Would recommend to others.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Kevin O\'Brien',
                'author_title' => 'Startup Founder',
                'content' => 'Solid work from start to finish. Jason took the time to understand our needs and delivered accordingly. Happy with the outcome!',
                'rating' => 5,
            ],
            [
                'author_name' => 'Nicole Adams',
                'author_title' => 'Marketing Manager',
                'content' => 'Jason was great to work with. He brought valuable insights to the table and helped us avoid potential pitfalls. Much appreciated!',
                'rating' => 5,
            ],
            [
                'author_name' => 'Brandon Scott',
                'author_title' => 'Engineering Manager',
                'content' => 'Excellent communication throughout the project. Jason kept us updated regularly and was always available to answer questions.',
                'rating' => 5,
            ],
            [
                'author_name' => 'Olivia Turner',
                'author_title' => 'Product Designer',
                'content' => 'Jason has a great work ethic and delivers high-quality results. Our project turned out better than we imagined. Thank you!',
                'rating' => 5,
            ],
            [
                'author_name' => 'William Harris',
                'author_title' => 'CEO',
                'content' => 'Professional, knowledgeable, and efficient. Jason completed the project on time and within budget. Couldn\'t ask for more!',
                'rating' => 5,
            ],
            [
                'author_name' => 'Sophia Clark',
                'author_title' => 'Digital Director',
                'content' => 'Working with Jason was straightforward and productive. He delivered quality work and was open to feedback throughout the process.',
                'rating' => 4,
            ],
            [
                'author_name' => 'Andrew Lewis',
                'author_title' => 'Innovation Lead',
                'content' => 'Jason brought creative solutions to our challenges. His expertise and dedication really showed in the final deliverable. Highly recommend!',
                'rating' => 5,
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create([
                'creator_id' => $creator->id,
                'author_name' => $reviewData['author_name'],
                'author_title' => $reviewData['author_title'],
                'content' => $reviewData['content'],
                'rating' => $reviewData['rating'],
                'status' => 'approved',
                'is_private_feedback' => false,
                'ip_address' => '127.0.0.1',
            ]);
        }

        $this->command->info('✅ Demo data created successfully!');
        $this->command->info('📧 Email: jason@jasonfrye.com');
        $this->command->info('🔑 Password: password');
        $this->command->info('🎨 Collection URL: /collection/jason-frye');
        $this->command->info('📊 Created 20 approved reviews');
    }
}
