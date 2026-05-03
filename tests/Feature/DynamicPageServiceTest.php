<?php

namespace Tests\Feature;

use App\Interfaces\DynamicPageServiceInterface;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DynamicPageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_single_dynamic_page_with_multiple_translations(): void
    {
        $english = Language::create([
            'name' => 'English',
            'code' => 'en',
            'status' => 'active',
        ]);

        $arabic = Language::create([
            'name' => 'Arabic',
            'code' => 'ar',
            'status' => 'active',
        ]);

        $service = app(DynamicPageServiceInterface::class);

        $page = $service->create([
            'translations' => [
                [
                    'language_id' => $english->id,
                    'page_title' => 'About Us',
                    'page_content' => '<p>English content</p>',
                ],
                [
                    'language_id' => $arabic->id,
                    'page_title' => 'من نحن',
                    'page_content' => '<p>Arabic content</p>',
                ],
            ],
        ]);

        $this->assertDatabaseCount('pages', 1);
        $this->assertDatabaseCount('page_translations', 2);
        $this->assertFalse(Schema::hasColumn('pages', 'page_title'));
        $this->assertFalse(Schema::hasColumn('pages', 'page_content'));
        $this->assertFalse(Schema::hasColumn('pages', 'language_id'));
        $this->assertSame('About Us', $page->page_title);
        $this->assertSame('من نحن', $page->useLanguage('ar')->page_title);
    }

    public function test_it_updates_dynamic_page_translations_by_language(): void
    {
        $english = Language::create([
            'name' => 'English',
            'code' => 'en',
            'status' => 'active',
        ]);

        $arabic = Language::create([
            'name' => 'Arabic',
            'code' => 'ar',
            'status' => 'active',
        ]);

        $page = Page::create([
            'status' => 'active',
        ]);

        $page->translations()->create([
            'language_id' => $english->id,
            'page_title' => 'Terms',
            'page_content' => '<p>Old english content</p>',
        ]);

        $service = app(DynamicPageServiceInterface::class);

        $updatedPage = $service->update($page->id, [
            'translations' => [
                [
                    'language_id' => $english->id,
                    'page_title' => 'Terms & Conditions',
                    'page_content' => '<p>Updated english content</p>',
                ],
                [
                    'language_id' => $arabic->id,
                    'page_title' => 'الشروط والأحكام',
                    'page_content' => '<p>Arabic content</p>',
                ],
            ],
        ]);

        $this->assertDatabaseCount('pages', 1);
        $this->assertDatabaseCount('page_translations', 2);
        $this->assertSame('Terms & Conditions', $updatedPage->useLanguage('en')->page_title);
        $this->assertSame('الشروط والأحكام', $updatedPage->useLanguage('ar')->page_title);
    }
}
