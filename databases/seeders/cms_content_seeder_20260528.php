<?php

declare(strict_types=1);

use App\Model\Cms\CmsArticle;
use App\Model\Cms\CmsBanner;
use App\Model\Cms\CmsCategory;
use App\Model\Cms\CmsTeamMember;
use App\Model\Cms\CmsTestimonial;
use Hyperf\Database\Seeders\Seeder;

class CmsContentSeeder20260528 extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $news = CmsCategory::updateOrCreate(['slug' => 'news'], [
            'name' => 'News',
            'description' => 'Company news and Brisbane coastal lifestyle updates',
            'sort' => 0,
            'status' => CmsCategory::STATUS_ENABLED,
        ]);

        $service = CmsCategory::updateOrCreate(['slug' => 'service'], [
            'name' => 'Services',
            'description' => 'Property management, leasing and owner support in Brisbane',
            'sort' => 1,
            'status' => CmsCategory::STATUS_ENABLED,
        ]);

        foreach ($this->banners($now) as $banner) {
            CmsBanner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }

        foreach ($this->serviceArticles($service->id, $now) as $article) {
            CmsArticle::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }

        foreach ($this->newsArticles($news->id, $now) as $article) {
            CmsArticle::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }

        foreach ($this->teamMembers($now) as $member) {
            CmsTeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member
            );
        }

        foreach ($this->testimonials($now) as $testimonial) {
            CmsTestimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial
            );
        }
    }

    private function banners(string $now): array
    {
        return [
            [
                'title' => 'Brisbane Coastal Living',
                'image' => '/uploads/cms/photo-1507525428034-b723cf961d3e.jpg',
                'link' => '/',
                'description' => 'Sun, ocean air and effortless property care across Brisbane.',
                'sort' => 300,
                'status' => CmsBanner::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Short-Stay Management',
                'image' => '/uploads/cms/photo-1600607687920-4e2a09cf159d.jpg',
                'link' => '/service',
                'description' => 'Hotel-level styling, guest support and owner reporting for furnished stays.',
                'sort' => 200,
                'status' => CmsBanner::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Leasing & Owner Support',
                'image' => '/uploads/cms/photo-1560518883-ce09059eeffa.jpg',
                'link' => '/contact',
                'description' => 'Long-term leasing, sales guidance and calm Brisbane property expertise.',
                'sort' => 100,
                'status' => CmsBanner::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
    }

    private function serviceArticles(int $categoryId, string $now): array
    {
        $base = static fn(array $data): array => array_merge([
            'category_id' => $categoryId,
            'author' => 'Brissy Breeze',
            'views' => 0,
            'status' => CmsArticle::STATUS_PUBLISHED,
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ], $data);

        return [
            $base([
                'slug' => 'short-term-rental-management',
                'title' => 'Short-Term Rental Management',
                'summary' => 'Fully furnished short-stay management across Airbnb and major booking platforms.',
                'cover' => '/uploads/cms/photo-1600607687920-4e2a09cf159d.jpg',
                'content' => '<p>We manage fully furnished short-stay properties across major platforms.</p><p><strong>Services include:</strong></p><ul><li>Property setup, furnishing and interior styling</li><li>Brissy Breeze branded furniture, linen and hospitality essentials</li><li>Listing creation and management across major short-stay platforms including Airbnb</li><li>Co-host services for short-stay properties</li><li>24/7 guest and property support with fast response times</li><li>Guest communication and booking coordination</li><li>Cleaning, maintenance and property care coordination</li><li>Property inspections and condition reporting</li><li>Development Application (DA) support and local council permit coordination</li><li>Financial reporting and performance updates for property owners</li></ul>',
                'sort' => 400,
                'seo_title' => 'Short-Term Rental Management Brisbane',
                'seo_description' => 'Airbnb and short-stay property management with styling, guest support and owner reporting.',
            ]),
            $base([
                'slug' => 'leasing-services',
                'title' => 'Leasing Services',
                'summary' => 'Professional long-term rental management for Brisbane landlords.',
                'cover' => '/uploads/cms/photo-1560518883-ce09059eeffa.jpg',
                'content' => '<p>We support landlords with professional long-term rental management.</p><p><strong>Services include:</strong></p><ul><li>Property marketing and advertising</li><li>Tenant screening and selection</li><li>Lease preparation and documentation</li><li>Entry condition reports</li><li>Tenant and landlord communication</li><li>Maintenance coordination</li><li>RTA compliance support</li></ul>',
                'sort' => 300,
                'seo_title' => 'Leasing Services Brisbane',
                'seo_description' => 'Tenant screening, lease documentation and compliant long-term rental management.',
            ]),
            $base([
                'slug' => 'property-sales-support',
                'title' => 'Property Sales Support',
                'summary' => 'Appraisal, campaign coordination and sales guidance for Brisbane owners.',
                'cover' => '/uploads/cms/photo-1582407947304-fd86f028f716.jpg',
                'content' => '<p>For owners considering selling their property, we provide guidance and support throughout the process.</p><p><strong>Services include:</strong></p><ul><li>Property appraisal and market insights</li><li>Sales campaign coordination</li><li>Buyer enquiry management</li><li>Sales process assistance</li></ul>',
                'sort' => 200,
                'seo_title' => 'Property Sales Support Brisbane',
                'seo_description' => 'Market insights and sales campaign support for Brisbane property owners.',
            ]),
            $base([
                'slug' => 'financial-guidance',
                'title' => 'Financial Guidance',
                'summary' => 'Rental return analysis and investment performance planning for owners.',
                'cover' => '/uploads/cms/photo-1554224155-6726b3ff858f.jpg',
                'content' => '<p>We help property owners understand and optimise their investment performance.</p><p><strong>Services include:</strong></p><ul><li>Rental return analysis</li><li>Property investment insights</li><li>Cash-flow and cost planning</li><li>Performance reporting for short-stay and long-term portfolios</li></ul>',
                'sort' => 100,
                'seo_title' => 'Property Financial Guidance Brisbane',
                'seo_description' => 'Investment insights, cash-flow planning and rental return analysis.',
            ]),
        ];
    }

    private function newsArticles(int $categoryId, string $now): array
    {
        $items = [
            ['slug' => 'brisbane-coastal-living-guide', 'title' => 'Brisbane Coastal Living Guide', 'summary' => 'How river walks, beach weekends and city-fringe homes define the Brisbane lifestyle.', 'cover' => '/uploads/cms/photo-1507525428034-b723cf961d3e.jpg', 'days' => 1],
            ['slug' => 'short-stay-trends-2032', 'title' => 'Short-Stay Trends Ahead of 2032', 'summary' => 'What Olympic-era demand means for furnished rental owners in inner Brisbane.', 'cover' => '/uploads/cms/photo-1522708323590-d24dbb6b0267.jpg', 'days' => 2],
            ['slug' => 'styled-apartments-higher-returns', 'title' => 'Why Styled Apartments Earn More', 'summary' => 'Hotel-like details, linen programs and photography that lift nightly rates.', 'cover' => '/uploads/cms/photo-1600585154340-be6162a9a0a0.jpg', 'days' => 3],
            ['slug' => 'south-bank-weekend-itinerary', 'title' => 'A Slow South Bank Weekend', 'summary' => 'Morning markets, river sunsets and easy stays near the cultural precinct.', 'cover' => '/uploads/cms/photo-1506905925346-21bda4d32df4.jpg', 'days' => 4],
            ['slug' => 'new-farm-river-walks', 'title' => 'New Farm River Walks We Love', 'summary' => 'Green bridges, café stops and the calm rhythm of Brisbane River living.', 'cover' => '/uploads/cms/photo-1441974231531-c6227db76b6e.jpg', 'days' => 5],
            ['slug' => 'airbnb-host-checklist-brisbane', 'title' => 'Brisbane Airbnb Host Checklist', 'summary' => 'Permits, styling, guest messaging and turnover routines that scale.', 'cover' => '/uploads/cms/photo-1600566753190-17f0baa2a6c3.jpg', 'days' => 6],
            ['slug' => 'winter-coastal-weekends', 'title' => 'Winter Coastal Weekends from Brisbane', 'summary' => 'Sunlit escapes to the Gold Coast and Sunshine Coast without the summer rush.', 'cover' => '/uploads/cms/photo-1476514525535-07fb3b4dce5f.jpg', 'days' => 7],
            ['slug' => 'property-care-between-guests', 'title' => 'Property Care Between Guests', 'summary' => 'Inspection routines that protect finishes, linen and five-star reviews.', 'cover' => '/uploads/cms/photo-1600210492486-724fe5c67fb0.jpg', 'days' => 8],
            ['slug' => 'long-term-leasing-update', 'title' => 'Long-Term Leasing Market Update', 'summary' => 'Tenant demand, presentation tips and pricing for Brisbane apartments.', 'cover' => '/uploads/cms/photo-1560448204-e02f11c3d0e2.jpg', 'days' => 9],
            ['slug' => 'holiday-rental-photography', 'title' => 'Holiday Rental Photography Tips', 'summary' => 'Natural light, wide angles and coastal styling that convert browsers to bookings.', 'cover' => '/uploads/cms/photo-1616486338812-3dadae4b4ace.jpg', 'days' => 10],
            ['slug' => 'bulimba-village-living', 'title' => 'Bulimba Village Living', 'summary' => 'Cinema nights, river breezes and family-friendly streets close to the city.', 'cover' => '/uploads/cms/photo-1605276374101-de4c0a8a9858.jpg', 'days' => 11],
            ['slug' => 'guest-experience-playbook', 'title' => 'Our Guest Experience Playbook', 'summary' => 'Welcome guides, local picks and 24/7 support that feel effortless.', 'cover' => '/uploads/cms/photo-1631049307264-da0ec9d70304.jpg', 'days' => 12],
            ['slug' => 'olympic-infrastructure-spillover', 'title' => 'Olympic Infrastructure and Owner Opportunity', 'summary' => 'Transport upgrades and visitor inflows shaping Brisbane accommodation demand.', 'cover' => '/uploads/cms/photo-1514565131-fce0801e5785.jpg', 'days' => 13],
            ['slug' => 'river-view-apartments', 'title' => 'River View Apartments on the Rise', 'summary' => 'Why outlook, cross-breeze and walkability matter for premium stays.', 'cover' => '/uploads/cms/photo-1600047509807-ba8f99d2cdde.jpg', 'days' => 14],
            ['slug' => 'sustainable-short-stay', 'title' => 'Sustainable Short-Stay Operations', 'summary' => 'Low-waste amenities, efficient turnover and durable finishes for busy homes.', 'cover' => '/uploads/cms/photo-1600607687644-c7171b42498f.jpg', 'days' => 15],
            ['slug' => 'paddington-terrace-stays', 'title' => 'Paddington Terrace Stays', 'summary' => 'Heritage façades, contemporary interiors and walk-to-café convenience.', 'cover' => '/uploads/cms/photo-1600585154526-990dced4db0d.jpg', 'days' => 16],
            ['slug' => 'owner-reporting-dashboard', 'title' => 'Owner Reporting That Actually Helps', 'summary' => 'Occupancy, revenue and maintenance summaries owners can act on quickly.', 'cover' => '/uploads/cms/photo-1460925895917-afdab827c52f.jpg', 'days' => 17],
            ['slug' => 'family-holiday-homes', 'title' => 'Designing Family Holiday Homes', 'summary' => 'Flexible sleeping, safe outdoor space and storage that guests notice.', 'cover' => '/uploads/cms/photo-1600210492496-724fe5c67fb0.jpg', 'days' => 18],
            ['slug' => 'brisbane-coffee-crawl', 'title' => 'Brisbane Coffee Crawl for Guests', 'summary' => 'Local roasters and river-side cafés we recommend in every welcome guide.', 'cover' => '/uploads/cms/photo-1495474472287-4d71bcdd2085.jpg', 'days' => 19],
            ['slug' => 'year-in-coastal-property', 'title' => 'A Year in Coastal Property Management', 'summary' => 'Seasonality, events and the calm, polished service owners expect year-round.', 'cover' => '/uploads/cms/photo-1505693416388-ac5ce068fe85.jpg', 'days' => 20],
        ];

        $articles = [];
        foreach ($items as $index => $item) {
            $publishedAt = date('Y-m-d H:i:s', strtotime("-{$item['days']} days"));
            $articles[] = [
                'category_id' => $categoryId,
                'slug' => $item['slug'],
                'title' => $item['title'],
                'summary' => $item['summary'],
                'content' => '<p>' . htmlspecialchars($item['summary'], ENT_QUOTES, 'UTF-8') . '</p><p>Brissy Breeze shares practical insights for owners, guests and investors exploring Brisbane coastal living — from styling and guest care to market updates and neighbourhood guides.</p>',
                'cover' => $item['cover'],
                'author' => 'Brissy Breeze',
                'views' => 120 + ($index * 17),
                'sort' => 1000 - $index,
                'status' => CmsArticle::STATUS_PUBLISHED,
                'published_at' => $publishedAt,
                'seo_title' => $item['title'],
                'seo_description' => $item['summary'],
                'created_at' => $publishedAt,
                'updated_at' => $now,
            ];
        }

        return $articles;
    }

    private function teamMembers(string $now): array
    {
        return [
            [
                'name' => 'Emily Carter',
                'position' => 'Managing Director',
                'phone' => '+61 7 3123 4501',
                'avatar' => '/uploads/cms/photo-1573496359142-b8d87734a5a2.jpg',
                'sort' => 500,
                'status' => CmsTeamMember::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'James Mitchell',
                'position' => 'Head of Short-Stay Operations',
                'phone' => '+61 7 3123 4502',
                'avatar' => '/uploads/cms/photo-1560250097-0b93528c311a.jpg',
                'sort' => 400,
                'status' => CmsTeamMember::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sophie Nguyen',
                'position' => 'Guest Experience Lead',
                'phone' => '+61 7 3123 4503',
                'avatar' => '/uploads/cms/photo-1580489944761-15a19d654956.jpg',
                'sort' => 300,
                'status' => CmsTeamMember::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Daniel Brooks',
                'position' => 'Leasing & Compliance Manager',
                'phone' => '+61 7 3123 4504',
                'avatar' => '/uploads/cms/photo-1472099645785-5658abf4ff4e.jpg',
                'sort' => 200,
                'status' => CmsTeamMember::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Olivia Turner',
                'position' => 'Owner Relations Specialist',
                'phone' => '+61 7 3123 4505',
                'avatar' => '/uploads/cms/photo-1438761681033-6461ffad8d80.jpg',
                'sort' => 100,
                'status' => CmsTeamMember::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
    }

    private function testimonials(string $now): array
    {
        return [
            [
                'name' => 'Christine Walsh',
                'position' => 'Property Owner, New Farm',
                'avatar' => '/uploads/cms/photo-1544005313-94ddf0286df2.jpg',
                'content' => 'Brissy Breeze transformed our apartment into a polished short-stay home. Occupancy and reviews improved within the first season.',
                'sort' => 800,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Michael Tan',
                'position' => 'Investor, South Brisbane',
                'avatar' => '/uploads/cms/photo-1507003211169-0a1dd7228f2d.jpg',
                'content' => 'Clear reporting, fast maintenance coordination and a team that understands Brisbane guests. Exactly the calm support we wanted.',
                'sort' => 700,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sarah Bennett',
                'position' => 'Holiday Guest',
                'avatar' => '/uploads/cms/photo-1494790108377-be9c29b29330.jpg',
                'content' => 'The apartment felt sunlit, thoughtful and easy from check-in to checkout. Local recommendations made our river weekend unforgettable.',
                'sort' => 600,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'David O\'Connor',
                'position' => 'Landlord, Bulimba',
                'avatar' => '/uploads/cms/photo-1500648767791-00dcc994a43e.jpg',
                'content' => 'Leasing was handled professionally from marketing to entry reports. Communication with tenants has been seamless.',
                'sort' => 500,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Amelia Rossi',
                'position' => 'Owner, Kangaroo Point',
                'avatar' => '/uploads/cms/photo-1534528741775-53994a69daeb.jpg',
                'content' => 'Styling, photography and guest messaging all feel hotel-quality. Our nightly rate finally reflects the view we offer.',
                'sort' => 400,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Henry Clarke',
                'position' => 'Corporate Traveller',
                'avatar' => '/uploads/cms/photo-1519345182560-3f2917c472ef.jpg',
                'content' => 'Reliable Wi-Fi, smooth self check-in and a space that actually feels like a coastal retreat after long meetings.',
                'sort' => 300,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Priya Sharma',
                'position' => 'Property Owner, West End',
                'avatar' => '/uploads/cms/photo-1531123897720-8f129e1688ce.jpg',
                'content' => 'They handled DA guidance and turnover routines without me chasing updates. Professional from day one.',
                'sort' => 200,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Lucas Meyer',
                'position' => 'Family Guest',
                'avatar' => '/uploads/cms/photo-1506794778202-cad84cf45f1d.jpg',
                'content' => 'Spacious, clean and close to the river — the welcome guide made travelling with kids surprisingly easy.',
                'sort' => 100,
                'status' => CmsTestimonial::STATUS_ENABLED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
    }
}
