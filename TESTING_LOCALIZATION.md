# Testing & Validation Guide - Laravel Localization

## Automated Testing

### 1. Test Middleware

Create `tests/Feature/LocalizationMiddlewareTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalizationMiddlewareTest extends TestCase
{
    public function test_can_access_with_id_locale()
    {
        $response = $this->get('/id/');
        $this->assertEquals('id', app()->getLocale());
        $response->assertStatus(200);
    }

    public function test_can_access_with_en_locale()
    {
        $response = $this->get('/en/');
        $this->assertEquals('en', app()->getLocale());
        $response->assertStatus(200);
    }

    public function test_redirects_to_default_locale_when_no_prefix()
    {
        $response = $this->get('/');
        // Should set locale to default (id)
        $this->assertEquals('id', app()->getLocale());
    }

    public function test_locale_saved_in_session()
    {
        $this->get('/en/');
        $this->assertEquals('en', session('locale'));
    }

    public function test_unsupported_locale_uses_fallback()
    {
        $response = $this->get('/fr/');
        // Should fallback to 'id'
        $this->assertEquals('id', app()->getLocale());
    }
}
```

Run tests:
```bash
php artisan test --filter LocalizationMiddlewareTest
```

### 2. Test Helper Functions

Create `tests/Unit/LocalizationServiceTest.php`:

```php
<?php

namespace Tests\Unit;

use App\Services\LocalizationService;
use Tests\TestCase;

class LocalizationServiceTest extends TestCase
{
    public function test_get_current_locale()
    {
        app()->setLocale('en');
        $this->assertEquals('en', LocalizationService::getCurrentLocale());
    }

    public function test_get_supported_locales()
    {
        $locales = LocalizationService::getSupportedLocales();
        $this->assertContains('id', $locales);
        $this->assertContains('en', $locales);
    }

    public function test_get_locale_name()
    {
        $this->assertEquals('Bahasa Indonesia', LocalizationService::getLocaleName('id'));
        $this->assertEquals('English', LocalizationService::getLocaleName('en'));
    }

    public function test_get_locale_flag()
    {
        $this->assertEquals('🇮🇩', LocalizationService::getLocaleFlag('id'));
        $this->assertEquals('🇬🇧', LocalizationService::getLocaleFlag('en'));
    }

    public function test_url_generation()
    {
        app()->setLocale('id');
        $this->assertEquals('/id/portfolio', LocalizationService::url('portfolio'));
        $this->assertEquals('/en/portfolio', LocalizationService::url('portfolio', 'en'));
    }

    public function test_is_locale_supported()
    {
        $this->assertTrue(LocalizationService::isLocaleSupported('id'));
        $this->assertTrue(LocalizationService::isLocaleSupported('en'));
        $this->assertFalse(LocalizationService::isLocaleSupported('fr'));
    }
}
```

Run tests:
```bash
php artisan test --filter LocalizationServiceTest
```

## Manual Testing Checklist

### Basic Functionality

- [ ] Access `/id/` → Shows Indonesian locale
- [ ] Access `/en/` → Shows English locale
- [ ] Access `/` (no prefix) → Auto redirects/sets to default locale
- [ ] URL shows in address bar with locale prefix

### Language Switching

- [ ] Language switcher component renders
- [ ] Active language button highlighted
- [ ] Click on language button → Redirects to same page with new locale
- [ ] Back button works after switching languages

### Session & Persistence

- [ ] Open browser console → Check cookies for `locale`
- [ ] Refresh page → Language preference preserved
- [ ] Open new tab with same domain → Language preference maintained
- [ ] Clear cookies → Reset to default language

### Helper Functions

Open `php artisan tinker`:

```php
>>> get_current_locale()
=> "id"

>>> locale_url('portfolio')
=> "/id/portfolio"

>>> locale_url('portfolio', 'en')
=> "/en/portfolio"

>>> locale_switch('en')
=> "/en/"

>>> get_supported_locales()
=> array:2 [
     0 => "id"
     1 => "en"
   ]

>>> get_locale_name('id')
=> "Bahasa Indonesia"

>>> get_locale_flag('en')
=> "🇬🇧"
```

### Route Generation

In Blade template:
```blade
{{ locale_route('dashboard') }}  // Should output: /id/dashboard atau /en/dashboard
{{ locale_route('project.show', ['id' => 1]) }}  // /id/project/1
```

### Translations

1. Check translation files exist:
   - `resources/lang/id/navigation.php`
   - `resources/lang/en/navigation.php`

2. In Blade template:
   ```blade
   {{ __('navigation.home') }}  // Should show "Beranda" atau "Home"
   ```

### Performance Testing

Measure middleware overhead:

```php
// In tinker or test
$start = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    $locale = get_current_locale();
}
$time = microtime(true) - $start;
echo "Time for 1000 calls: " . ($time * 1000) . "ms";  // Should be < 10ms
```

## Browser Testing

### Chrome DevTools

1. **Check Cookies:**
   - Open DevTools → Application → Cookies
   - Look for `XSRF-TOKEN`, `laravel_session`, `locale`
   - Switch language and see cookie update

2. **Check Network:**
   - Click language link
   - Should see request to `/id/page` or `/en/page`
   - Status 200 OK

3. **Check Console:**
   - Should be no errors
   - Check `app()->getLocale()` in PHP if using server render

### Mobile Testing

Test on mobile devices:
- [ ] Language switcher visible and clickable
- [ ] Touch/swipe language switcher works
- [ ] Responsive design maintained
- [ ] Language preference persisted

## SEO Testing

### URL Structure
- [ ] URLs properly formatted with locale prefix
- [ ] Canonical tags present (if implemented)
- [ ] No duplicate content between `/id/` and `/en/`

### Google Search Console
1. Add property for each locale variant
2. Check indexing status:
   ```
   https://website.com/id/page
   https://website.com/en/page
   ```

### hreflang Implementation (Future)
```blade
<!-- In header -->
@foreach(get_all_locale_urls() as $locale => $url)
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ url($url) }}" />
@endforeach
```

## Edge Cases to Test

### 1. Nested Routes
```
✓ /id/admin/users
✓ /en/admin/users
✓ /id/admin/users/1/edit
```

### 2. API Routes
```
✓ /api/posts (no locale prefix)
✓ /api/v1/posts (no locale prefix)
```

### 3. Static Files
```
✓ /css/app.css (no locale prefix)
✓ /js/app.js (no locale prefix)
✓ /images/logo.png (no locale prefix)
```

### 4. Special Characters in URLs
```
✓ /id/search?q=test+query
✓ /en/portfolio?sort=latest&filter=web
```

### 5. Route Parameters
```
✓ /id/project/1
✓ /en/user/john-doe/profile
✓ /id/category/web-development
```

## Debugging Tips

### Enable Debug Mode
Edit `.env`:
```
APP_DEBUG=true
```

### Check Locale Value
In any view:
```blade
<!-- Debug: current locale -->
<!-- Current: {{ get_current_locale() }} -->
```

### Log Locale Changes
Add to LocalizationMiddleware.php:
```php
Log::info('Locale set to: ' . app()->getLocale());
```

Check logs:
```bash
tail -f storage/logs/laravel.log | grep "Locale set"
```

### Test with HTTP Client
```bash
# Test Indonesian
curl -b "PHPSESSID=test123" http://localhost:8000/id/dashboard

# Test English
curl -b "PHPSESSID=test123" http://localhost:8000/en/dashboard

# Check cookies in response
curl -i http://localhost:8000/id/dashboard
```

## Performance Benchmarks

Target metrics:
- [ ] Helper function call: < 1ms
- [ ] Middleware overhead: < 2ms per request
- [ ] URL generation: < 0.5ms
- [ ] Translation lookup: < 0.5ms

## Common Issues & Solutions

### Issue: Locale not persisting
**Solution:** Check session middleware is enabled
```php
// bootstrap/app.php
$middleware->web() // Should include session
```

### Issue: Routes showing without locale prefix
**Solution:** Middleware might not be applied. Check:
```php
// bootstrap/app.php - verify middleware is registered
$middleware->web(append: [LocalizationMiddleware::class])
```

### Issue: Static files affected by locale prefix
**Solution:** Should be excluded. Check patterns in middleware:
```php
if (preg_match('/\.(css|js|png|jpg|...)$/i', $request->path())) {
    // Skip localization
}
```

### Issue: Helper functions undefined
**Solution:** Run composer dump-autoload
```bash
composer dump-autoload
```

## Load Testing

For high-traffic scenarios:

```bash
# Test with ApacheBench
ab -n 1000 -c 10 http://localhost:8000/id/

# Test with wrk
wrk -t4 -c100 -d30s http://localhost:8000/id/

# Results should show:
# - Requests/sec: > 1000 req/s
# - Latency: < 50ms avg
```

---

For more details, see:
- `LOCALIZATION_SETUP.md` - Complete setup documentation
- `QUICK_START_LOCALIZATION.md` - Quick start guide
