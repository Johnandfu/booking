<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsArticle;
use App\Model\Cms\CmsBanner;
use App\Model\Cms\CmsCategory;
use App\Model\Cms\CmsContactMessage;
use App\Model\Cms\CmsPage;
use App\Repository\Cms\CmsArticleRepository;
use App\Repository\Cms\CmsBannerRepository;
use App\Repository\Cms\CmsCategoryRepository;
use App\Repository\Cms\CmsContactMessageRepository;
use App\Repository\Cms\CmsFrontendI18nRepository;
use App\Repository\Cms\CmsMapSettingRepository;
use App\Repository\Cms\CmsPageRepository;
use Hyperf\Collection\Collection;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Throwable;

final class CmsContentService
{
    public const FRONTEND_I18N_DEFAULTS = [
        'en' => [
            'siteName' => 'Brissy Breeze',
            'description' => 'Boutique property management, short-term rental management and accommodation in Brisbane',
            'contactEmail' => 'contact@example.com',
            'contactPhone' => '+61 7 1234 5678',
            'home' => 'Home',
            'about' => 'About',
            'contact' => 'Contact',
            'portal' => 'Client Portal',
            'book' => 'Book direct',
            'signin' => 'Sign in',
            'navToggle' => 'Toggle navigation',
            'paginationLabel' => 'Pagination',
            'socialMedia' => 'Social media',
            'socialInstagramLabel' => 'Instagram',
            'socialFacebookLabel' => 'Facebook',
            'socialLinkedinLabel' => 'LinkedIn',
            'socialYoutubeLabel' => 'YouTube',
            'socialXLabel' => 'X',
            'footerCopyrightSuffix' => 'All rights reserved.',
            'footerText' => 'Boutique property management, accommodation and direct booking support in Brisbane.',
            'footerServices' => 'Brisbane · Short-Term Rental Management · Leasing Services',
            'socialInstagramUrl' => '',
            'socialFacebookUrl' => '',
            'socialLinkedinUrl' => '',
            'socialYoutubeUrl' => '',
            'socialXUrl' => '',
            'heroKicker' => 'Brissy Breeze',
            'heroTitle' => 'Boutique Property Management & Accommodation',
            'heroText' => 'For Brisbane owners, tenants and travellers, we provide short-term rental management, leasing services and property sales support.',
            'directBooking' => 'Direct Booking',
            'learnStory' => 'Learn Our Story',
            'why' => 'Why Brisbane',
            'growth' => '2032 Olympic growth market.',
            'growthText' => 'As Brisbane grows toward 2032, we help owners navigate both short-term and long-term rental opportunities.',
            'homeHeroKicker' => 'Brisbane Coastal Lifestyle',
            'homeHeroTitle' => 'Brisbane Coastal Living',
            'homeHeroSubtitle' => 'Sun, Ocean, Freedom',
            'homeHeroCta' => 'Explore Living',
            'homeScrollLabel' => 'Scroll to features',
            'homeFeaturesKicker' => 'Curated Experiences',
            'homeFeaturesTitle' => 'Designed for sunlit days and effortless stays.',
            'homeFeatureBeachTitle' => 'Beach Lifestyle',
            'homeFeatureBeachText' => 'Soft mornings, ocean air and relaxed coastal rhythms shaped around everyday comfort.',
            'homeFeatureUrbanTitle' => 'Urban Luxury',
            'homeFeatureUrbanText' => 'Premium city-fringe living with refined spaces, warm details and hotel-like ease.',
            'homeFeatureOutdoorTitle' => 'Outdoor Culture',
            'homeFeatureOutdoorText' => 'From river walks to weekend escapes, Brisbane invites a slower, brighter way to live.',
            'homeAboutKicker' => 'Slow Luxury',
            'homeAboutTitle' => 'A coastal city lifestyle made calm, polished and easy.',
            'homeAboutText' => '{siteName} brings together the warmth of beachside travel, the confidence of property expertise and the freedom of Queensland living. Every detail is shaped for comfort, clarity and sunlit simplicity.',
            'homeAboutImageAlt' => 'Sunlit coastal living',
            'homeAboutCta' => 'Our Story',
            'homeGalleryKicker' => 'Gallery',
            'homeGalleryTitle' => 'Quiet luxury, open skies and coastal texture.',
            'homeGalleryBeachAlt' => 'Beach sunrise',
            'homeGalleryHolidayAlt' => 'Coastal holiday',
            'homeGalleryWavesAlt' => 'Ocean waves',
            'homeGalleryPoolAlt' => 'Resort pool',
            'keyword' => 'Keyword',
            'category' => 'Category',
            'categoryKicker' => 'Editorial Category',
            'categoryNews' => 'News',
            'categoryNewsDescription' => 'Company news and product updates',
            'categoryService' => 'Services',
            'categoryServiceDescription' => 'What we provide for owners, tenants and guests',
            'categoryEmpty' => 'No articles in this category yet.',
            'location' => 'Brisbane, QLD',
            'listingLocation' => 'Brisbane Coast',
            'listingBeachfront' => 'Beachfront Villas',
            'listingRiverView' => 'River View Homes',
            'listingCityCoastal' => 'City Coastal Apartments',
            'viewDetails' => 'View Details',
            'mapLocation' => '-27.4698,153.0251',
            'search' => 'Search',
            'propertyByRequirement' => 'Property By Requirement',
            'exploreTypes' => 'Explore Apartment Types',
            'typesText' => 'Showcase core property types and service entrances with a boutique real-estate template structure.',
            'introTitle' => 'Where Brisbane property feels easy, professional and sustainable',
            'introText' => 'Brissy Breeze is a Brisbane-based property management and accommodation company specialising in furnished short-term rentals, leasing services and property sales support.',
            'services' => 'Our Services',
            'whatWeDo' => 'What We Do',
            'news' => 'Blog & News',
            'latest' => 'Our Latest News Update',
            'newsText' => 'Insights about Brisbane property management, accommodation and local market trends.',
            'newsHeroKicker' => 'Coastal Journal',
            'newsHeroTitle' => 'Brisbane Coastal Living',
            'newsHeroText' => 'Find your perfect coastal lifestyle through curated stories, local insight and sunlit Brisbane inspiration.',
            'newsFilterLocationLabel' => 'Location',
            'newsFilterBudgetLabel' => 'Budget Mood',
            'newsTagBeachfront' => 'Beachfront',
            'newsTagRiverView' => 'River View',
            'newsTagCityCoastal' => 'City Coastal',
            'newsFeaturedKicker' => 'Featured Edit',
            'newsFeaturedTitle' => 'A magazine-like edit of Brisbane coastal life.',
            'newsDefaultSummary' => 'Explore sun, ocean air and the Brisbane way of living.',
            'newsEmpty' => 'No news yet.',
            'newsGridKicker' => 'Lifestyle Listings',
            'newsGridTitle' => 'Curated coastal inspiration.',
            'newsLoadMore' => 'Load more',
            'newsLoadMoreLoading' => 'Loading...',
            'newsLoadMoreFailed' => 'Retry loading',
            'more' => 'More',
            'empty' => 'No articles yet. Please publish content from the admin portal.',
            'serviceHeroKicker' => 'Premium Service Edit',
            'serviceHeroTitle' => 'Brisbane Coastal Living',
            'serviceHeroText' => 'Find your perfect coastal lifestyle through refined accommodation, property care and owner support.',
            'serviceFilterBudgetLabel' => 'Budget slider',
            'serviceFeaturedKicker' => 'Featured Services',
            'serviceFeaturedTitle' => 'Not a service list, a coastal lifestyle portfolio.',
            'serviceCardEyebrow' => 'Premium enquiry',
            'serviceDefaultSummary' => 'A polished Brisbane coastal service experience shaped around ease and detail.',
            'serviceGridKicker' => 'Listings Grid',
            'serviceGridTitle' => 'Selected service portfolios for owners and travellers.',
            'articleAuthor' => 'Author',
            'articleDefaultAuthor' => 'Admin',
            'articlePublished' => 'Published',
            'articleViews' => 'Views',
            'articleEmptyContent' => 'No content yet',
            'pageKicker' => 'Company Page',
            'pageEmptyContent' => 'No content yet',
            'notFoundText' => 'The page does not exist or has been unpublished.',
            'notFoundBackHome' => 'Back to home',
            'aboutKicker' => 'About Us',
            'aboutTitle' => 'Brissy Breeze makes property returns and accommodation effortless',
            'aboutLead' => 'We are a Brisbane-based property management and accommodation company specialising in furnished short-term rentals, leasing services and property sales support.',
            'aboutBody' => 'We help owners improve rental returns while providing comfortable, stylish homes for guests and tenants. As Brisbane continues to grow toward the 2032 Olympic Games, we support owners across both short-term and long-term rental markets.',
            'aboutImageAlt' => 'Brisbane coastal lifestyle',
            'aboutSupport' => 'What We Support',
            'aboutShort' => 'Short-term rental and accommodation operations',
            'aboutLeasing' => 'Long-term leasing services and property care',
            'aboutSales' => 'Property sales support and return guidance',
            'findUs' => 'Find Us',
            'mapTitlePrefix' => 'Google Map',
            'mapTitle' => 'Brisbane, Queensland, Australia',
            'mapText' => 'The map points to Brisbane, helping clients understand Brissy Breeze local service area.',
            'contactUs' => 'Contact Us',
            'ready' => 'Ready to work with Brissy Breeze?',
            'contactIntro' => 'Tell us about your property, rental goals or accommodation needs and our Brisbane team will respond soon.',
            'address' => 'Address',
            'emailBody' => 'We reply to every enquiry within one business day.',
            'hotBody' => 'Call us for urgent property or booking support.',
            'hours' => 'Business Hours',
            'hoursBody' => 'Mon–Fri 9:00–17:00 · Sat 10:00–14:00',
            'contactBook' => 'Book a Consultation',
            'send' => 'Send a Message',
            'messageIntro' => 'Share your property goals and we will follow up with tailored Brisbane advice.',
            'contactServicePropertyTitle' => 'Property Management',
            'contactServicePropertyText' => 'Short-term rental and leasing services',
            'contactServiceAccommodationTitle' => 'Accommodation',
            'contactServiceAccommodationText' => 'Furnished stays for guests and tenants',
            'contactServiceOwnerTitle' => 'Owner Support',
            'contactServiceOwnerText' => 'Rental return and sales support',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'message' => 'Message',
            'submit' => 'Submit',
        ],
        'zh' => [
            'siteName' => 'Brissy Breeze',
            'description' => '布里斯班精品物业管理、短租托管与住宿服务',
            'contactEmail' => 'contact@example.com',
            'contactPhone' => '+61 7 1234 5678',
            'home' => '首页',
            'about' => '关于我们',
            'contact' => '联系我们',
            'portal' => '客户门户',
            'book' => '直接预订',
            'signin' => '登录',
            'navToggle' => '切换导航',
            'paginationLabel' => '分页',
            'socialMedia' => '社交媒体',
            'socialInstagramLabel' => 'Instagram',
            'socialFacebookLabel' => 'Facebook',
            'socialLinkedinLabel' => 'LinkedIn',
            'socialYoutubeLabel' => 'YouTube',
            'socialXLabel' => 'X',
            'footerCopyrightSuffix' => '版权所有。',
            'footerText' => '布里斯班精品物业管理、住宿与直订支持。',
            'footerServices' => '布里斯班 · 短租管理 · 租赁服务',
            'socialInstagramUrl' => '',
            'socialFacebookUrl' => '',
            'socialLinkedinUrl' => '',
            'socialYoutubeUrl' => '',
            'socialXUrl' => '',
            'heroKicker' => 'Brissy Breeze',
            'heroTitle' => '精品物业管理与住宿服务',
            'heroText' => '为布里斯班业主、租客与旅客提供短租管理、租赁服务与房产销售支持。',
            'directBooking' => '直接预订',
            'learnStory' => '了解品牌故事',
            'why' => '为什么选择布里斯班',
            'growth' => '2032 奥运增长市场',
            'growthText' => '随着布里斯班迈向 2032 奥运，我们帮助业主把握短租与长租机会。',
            'homeHeroKicker' => '布里斯班海岸生活方式',
            'homeHeroTitle' => '布里斯班海岸生活',
            'homeHeroSubtitle' => '阳光、海洋、自由',
            'homeHeroCta' => '探索生活方式',
            'homeScrollLabel' => '滚动到特色内容',
            'homeFeaturesKicker' => '精选体验',
            'homeFeaturesTitle' => '为阳光假日与轻松旅居而设计。',
            'homeFeatureBeachTitle' => '海岸生活',
            'homeFeatureBeachText' => '柔和清晨、海风与放松节奏，围绕日常舒适展开。',
            'homeFeatureUrbanTitle' => '城市轻奢',
            'homeFeatureUrbanText' => '优质城市边缘生活，兼具精致空间、温暖细节与酒店式便利。',
            'homeFeatureOutdoorTitle' => '户外文化',
            'homeFeatureOutdoorText' => '从河岸步道到周末短途，布里斯班邀请你以更明亮从容的方式生活。',
            'homeAboutKicker' => '慢奢生活',
            'homeAboutTitle' => '让海岸城市生活更从容、精致、轻松。',
            'homeAboutText' => '{siteName} 融合海岸旅行的温度、物业服务的安心与昆士兰生活的自由，每个细节都围绕舒适、清晰与阳光感展开。',
            'homeAboutImageAlt' => '阳光下的海岸生活',
            'homeAboutCta' => '了解我们',
            'homeGalleryKicker' => '画廊',
            'homeGalleryTitle' => '静谧轻奢、开阔天空与海岸质感。',
            'homeGalleryBeachAlt' => '海滩日出',
            'homeGalleryHolidayAlt' => '海岸假日',
            'homeGalleryWavesAlt' => '海浪',
            'homeGalleryPoolAlt' => '度假泳池',
            'keyword' => '关键词',
            'category' => '分类',
            'categoryKicker' => '内容栏目',
            'categoryNews' => '新闻动态',
            'categoryNewsDescription' => '公司新闻与产品更新',
            'categoryService' => '服务项目',
            'categoryServiceDescription' => '为业主、租客与旅客提供的服务',
            'categoryEmpty' => '该栏目暂无文章。',
            'location' => '布里斯班',
            'listingLocation' => '布里斯班海岸',
            'listingBeachfront' => '海滨别墅',
            'listingRiverView' => '河景住宅',
            'listingCityCoastal' => '城市海岸公寓',
            'viewDetails' => '查看详情',
            'mapLocation' => '-27.4698,153.0251',
            'search' => '搜索',
            'propertyByRequirement' => '按需求找房',
            'exploreTypes' => '探索房源类型',
            'typesText' => '展示核心房源类型与服务入口，呈现精品地产模板结构。',
            'introTitle' => '让布里斯班房产管理更轻松、专业、可持续',
            'introText' => 'Brissy Breeze 专注布里斯班精品短租、租赁服务与房产销售支持。',
            'services' => '我们的服务',
            'whatWeDo' => '我们在做什么',
            'news' => '博客与新闻',
            'latest' => '最新资讯',
            'newsText' => '布里斯班物业管理、住宿与本地市场洞察。',
            'newsHeroKicker' => '海岸生活杂志',
            'newsHeroTitle' => '布里斯班海岸生活',
            'newsHeroText' => '通过精选故事、本地洞察与阳光感灵感，发现理想的海岸生活方式。',
            'newsFilterLocationLabel' => '位置',
            'newsFilterBudgetLabel' => '预算氛围',
            'newsTagBeachfront' => '海滨',
            'newsTagRiverView' => '河景',
            'newsTagCityCoastal' => '城市海岸',
            'newsFeaturedKicker' => '精选画册',
            'newsFeaturedTitle' => '像翻阅一本布里斯班海岸生活杂志。',
            'newsDefaultSummary' => '探索阳光、海风与布里斯班生活方式。',
            'newsEmpty' => '暂无新闻。',
            'newsGridKicker' => '生活方式列表',
            'newsGridTitle' => '精选海岸灵感。',
            'newsLoadMore' => '加载更多',
            'newsLoadMoreLoading' => '加载中...',
            'newsLoadMoreFailed' => '加载失败，重试',
            'more' => '更多',
            'empty' => '暂无文章，请先在后台发布内容。',
            'serviceHeroKicker' => '高端服务精选',
            'serviceHeroTitle' => '布里斯班海岸生活',
            'serviceHeroText' => '通过精品住宿、物业维护与业主支持，发现理想海岸生活方式。',
            'serviceFilterBudgetLabel' => '预算滑块',
            'serviceFeaturedKicker' => '精选服务',
            'serviceFeaturedTitle' => '不是服务列表，而是海岸生活解决方案。',
            'serviceCardEyebrow' => '高端咨询',
            'serviceDefaultSummary' => '以轻奢旅行体验重新定义布里斯班海岸生活服务。',
            'serviceGridKicker' => '服务列表',
            'serviceGridTitle' => '为业主与旅客精选的服务组合。',
            'articleAuthor' => '作者',
            'articleDefaultAuthor' => '管理员',
            'articlePublished' => '发布',
            'articleViews' => '阅读',
            'articleEmptyContent' => '暂无正文',
            'pageKicker' => '公司页面',
            'pageEmptyContent' => '暂无内容',
            'notFoundText' => '页面不存在或内容已下线',
            'notFoundBackHome' => '返回首页',
            'aboutKicker' => '关于我们',
            'aboutTitle' => 'Brissy Breeze 让房产回报与住宿更轻松',
            'aboutLead' => '我们专注布里斯班精品短租、租赁服务与房产销售支持。',
            'aboutBody' => '帮助业主提升回报，为旅客与租客提供舒适时尚的住所。随着布里斯班迈向 2032 奥运，我们覆盖短租与长租市场。',
            'aboutImageAlt' => '布里斯班海岸生活方式',
            'aboutSupport' => '我们支持',
            'aboutShort' => '短租与住宿运营',
            'aboutLeasing' => '长租租赁与物业维护',
            'aboutSales' => '房产销售与回报指导',
            'findUs' => '找到我们',
            'mapTitlePrefix' => 'Google 地图',
            'mapTitle' => '澳洲昆士兰 Brisbane',
            'mapText' => '地图定位澳洲布里斯班，方便客户了解 Brissy Breeze 的本地服务区域。',
            'contactUs' => '联系我们',
            'ready' => '准备好与 Brissy Breeze 合作了吗？',
            'contactIntro' => '告诉我们您的房产、租赁或住宿需求，布里斯班团队会尽快回复。',
            'address' => '地址',
            'emailBody' => '我们会在一个工作日内回复每一封邮件。',
            'hotBody' => '紧急物业或预订问题欢迎致电。',
            'hours' => '营业时间',
            'hoursBody' => '周一至周五 9:00–17:00 · 周六 10:00–14:00',
            'contactBook' => '预约咨询',
            'send' => '发送留言',
            'messageIntro' => '告诉我们您的目标，我们会提供针对性的布里斯班建议。',
            'contactServicePropertyTitle' => '物业管理',
            'contactServicePropertyText' => '短租管理与租赁服务',
            'contactServiceAccommodationTitle' => '住宿服务',
            'contactServiceAccommodationText' => '为旅客与租客提供带家具住宿',
            'contactServiceOwnerTitle' => '业主支持',
            'contactServiceOwnerText' => '租金回报与销售支持',
            'name' => '姓名',
            'email' => '邮箱',
            'phone' => '电话',
            'message' => '留言',
            'submit' => '提交留言',
        ],
    ];

    public function __construct(
        private readonly CmsCategoryRepository $categoryRepository,
        private readonly CmsArticleRepository $articleRepository,
        private readonly CmsBannerRepository $bannerRepository,
        private readonly CmsPageRepository $pageRepository,
        private readonly CmsContactMessageRepository $contactMessageRepository,
        private readonly CmsFrontendI18nRepository $frontendI18nRepository,
        private readonly CmsMapSettingRepository $mapSettingRepository,
    ) {}

    /**
     * @return Collection<int, CmsCategory>
     */
    public function navCategories(): Collection
    {
        return $this->categoryRepository->listEnabled();
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function latestArticles(int $limit = 6): Collection
    {
        return $this->articleRepository->latestPublished($limit);
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function serviceArticles(int $limit = 4): Collection
    {
        return $this->categoryArticles('service', $limit);
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function categoryArticles(string $slug, int $limit = 10): Collection
    {
        $category = $this->categoryBySlug($slug);
        if ($category === null) {
            return new Collection();
        }

        return $this->articleRepository->latestPublishedByCategory($category->id, $limit);
    }

    /**
     * @return Collection<int, CmsBanner>
     */
    public function banners(): Collection
    {
        try {
            return $this->bannerRepository->listEnabled();
        } catch (Throwable) {
            return new Collection();
        }
    }

    public function categoryBySlug(string $slug): ?CmsCategory
    {
        return $this->categoryRepository->findBySlug($slug);
    }

    public function articleBySlug(string $slug): ?CmsArticle
    {
        return $this->articleRepository->findPublishedBySlug($slug);
    }

    public function pageBySlug(string $slug): ?CmsPage
    {
        return $this->pageRepository->findEnabledBySlug($slug);
    }

    public function articlesByCategory(int $categoryId, int $page, int $pageSize = 10): LengthAwarePaginatorInterface
    {
        return $this->articleRepository->paginatePublishedByCategory($categoryId, $page, $pageSize);
    }

    public function recordArticleView(CmsArticle $article): void
    {
        $this->articleRepository->incrementViews($article->id);
    }

    public function createContactMessage(array $data): CmsContactMessage
    {
        return $this->contactMessageRepository->create($data);
    }

    public function mapSetting(?string $locale = null): array
    {
        $locale = $locale === 'zh' ? 'zh' : 'en';
        $copy = $this->frontendI18n($locale);

        $setting = [
            'title' => $copy['mapTitle'] ?? 'Brisbane, Queensland, Australia',
            'address' => $copy['mapText'] ?? 'Brisbane QLD Australia',
            'zoom' => 14,
        ];

        try {
            $stored = $this->mapSettingRepository->first();
            if ($stored !== null) {
                $setting['title'] = $stored->title ?: $setting['title'];
                $setting['address'] = $stored->address ?: $setting['address'];
                $setting['zoom'] = min(20, max(1, (int) $stored->zoom));
                if ($stored->latitude !== null && $stored->longitude !== null) {
                    $query = $stored->latitude . ',' . $stored->longitude;
                } else {
                    $query = $stored->address;
                }
                $setting['embed_url'] = sprintf(
                    'https://www.google.com/maps?q=%s&z=%d&output=embed',
                    rawurlencode((string) $query),
                    $setting['zoom']
                );

                return $setting;
            }
        } catch (Throwable) {
            // Fall through to i18n coordinates.
        }

        $coordinates = trim((string) ($copy['mapLocation'] ?? '-27.4698,153.0251'));
        $setting['embed_url'] = sprintf(
            'https://www.google.com/maps?q=%s&z=%d&output=embed',
            rawurlencode($coordinates),
            $setting['zoom']
        );

        return $setting;
    }

    public function frontendI18n(string $locale): array
    {
        $locale = $locale === 'zh' ? 'zh' : 'en';
        $messages = self::FRONTEND_I18N_DEFAULTS[$locale];

        try {
            foreach ($this->frontendI18nRepository->listEnabled() as $item) {
                $value = trim((string) ($item->{$locale} ?? ''));
                if ($value !== '') {
                    $messages[$item->key] = $value;
                }
            }
        } catch (Throwable) {
            return $messages;
        }

        return $messages;
    }
}
