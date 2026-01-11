# Comprehensive Notification System Documentation

## Overview
This notification system aggregates **system notifications**, **messages**, and **emails** into a unified Notification History view with intelligent caching and automatic cleanup.

## Features

### 1. **Unified Notification Display**
All notification types are displayed in a single, searchable interface:
- **System Notifications** (from `notifications` table)
- **Messages** (from `messages` table)
- **Emails** (from email tracking system)

### 2. **Intelligent "New" Badge Logic**
- **24-Hour Threshold**: Notifications created within the last 24 hours display a blue "New" badge
- **Automatic Removal**: The "New" badge automatically disappears after 24 hours
- **Visual Distinction**: New notifications have a blue left border for visibility

### 3. **Smart Caching System**
- **Cache Driver**: Uses database cache (perfect for shared hosting like Hostinger)
- **Cache Duration**: 1 hour per user's notification list
- **Cache Key**: `user_comprehensive_notifications_{user_id}`
- **Auto-Refresh**: Cache automatically rebuilds when expired

### 4. **Automatic Cleanup**
- **7-Week Retention**: Notifications older than 7 weeks (49 days) are automatically deleted
- **Daily Schedule**: Cleanup runs every day at midnight
- **Cache Clear**: User caches are cleared after cleanup to show updated data

### 5. **Advanced Filtering**
- **Search**: Search across titles, messages, and notification types
- **Type Filter**: Filter by System Notifications, Emails, or Messages
- **Date Range**: Filter by from/to dates
- **Status**: View all, unread only, or read only notifications

### 6. **Tab Persistence**
- **URL Parameters**: Uses `?tab=notification` to maintain active tab
- **Form Integration**: Hidden input preserves tab state during filter submissions
- **No Page Jumps**: Always stays on the correct tab after filtering

## Technical Implementation

### Cache Configuration
```php
// .env
CACHE_DRIVER=database
CACHE_STORE=database
```

The database driver is chosen because:
- ✅ Works on shared hosting (no Redis required)
- ✅ Persistent across server restarts
- ✅ No additional server configuration needed
- ✅ Reliable and battle-tested

### Cache Strategy
```php
use Illuminate\Support\Facades\Cache;

$cacheKey = 'user_comprehensive_notifications_' . auth()->id();
$cacheDuration = 3600; // 1 hour

$notifications = Cache::remember($cacheKey, $cacheDuration, function() {
    // Aggregate all notification types
    // Filter by 7-week threshold
    // Return sorted collection
});
```

### Notification Aggregation
```php
// Structure of aggregated notifications
[
    'id' => 123,
    'type' => 'notification|message|email',
    'type_label' => 'System Notification|Message|Email',
    'icon' => 'bell|comment|envelope',
    'icon_color' => 'text-blue-500|text-green-500|text-red-500',
    'title' => 'Notification Title',
    'message' => 'Full message content',
    'created_at' => Carbon instance,
    'read_at' => Carbon instance or null,
    'is_new' => true/false (< 24 hours old),
    'data' => [] // Additional notification data
]
```

### "New" Badge Logic
```php
'is_new' => $notification->created_at->gt(Carbon::now()->subHours(24))
```

This ensures:
- New notifications show the badge
- After 24 hours, badge automatically disappears
- No manual "dismiss" action needed

## Scheduled Tasks

### Setup Scheduler (Required for Auto-Cleanup)

**For Production (Hostinger)**:
```bash
# Add to crontab
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

**For Development**:
```bash
# Run manually
php artisan notifications:cleanup

# Or start scheduler
php artisan schedule:work
```

### Cleanup Command
```bash
# Manual cleanup
php artisan notifications:cleanup

# Output:
# Starting notification cleanup...
# Deleted 45 system notifications older than 7 weeks.
# Deleted 23 messages older than 7 weeks.
# Clearing notification cache...
# Notification cleanup completed successfully!
```

## Database Requirements

### Required Tables
1. **notifications** (Laravel default)
2. **cache** (for database caching)
3. **cache_locks** (for cache locking)
4. **messages** (optional, if messages feature exists)

### Run Migrations
```bash
php artisan migrate
```

This will create:
- ✅ cache table
- ✅ cache_locks table
- ✅ notifications table (if not exists)

## Usage Examples

### For Users
1. Navigate to **History Settings** page
2. Click on **Notification History** tab
3. View all notifications with "New" badges for recent items
4. Use filters to search specific notifications
5. Tab persistence ensures you stay on the notification tab

### For Administrators
- Monitor notification cleanup via logs
- Check cache status: `php artisan cache:table`
- Clear cache manually: `php artisan cache:clear`

## Performance Optimization

### Caching Benefits
- **Reduced Database Queries**: Aggregation happens once per hour per user
- **Faster Page Loads**: Cached data served instantly
- **Lower Server Load**: Especially important on shared hosting

### Cache Invalidation
Cache is automatically cleared when:
1. 1 hour expires (TTL)
2. Daily cleanup runs
3. Manual cache clear

## Shared Hosting Compatibility

### Why Database Cache?
Traditional Redis caching may not be available on shared hosting like Hostinger. The database cache driver provides:

✅ **No Special Requirements**: Works with standard MySQL/PostgreSQL  
✅ **Zero Configuration**: No server-level setup needed  
✅ **Reliable**: Uses the same database as your application  
✅ **Fast Enough**: Adequate performance for small-to-medium traffic  

### Alternative: File Cache
If database cache has issues, switch to file cache:
```php
// .env
CACHE_DRIVER=file
```

File cache stores data in `storage/framework/cache/data/`.

## Monitoring & Debugging

### Check Cache Contents
```php
// In Tinker
php artisan tinker

// Get user's cached notifications
Cache::get('user_comprehensive_notifications_1');

// Check cache expiration
Cache::has('user_comprehensive_notifications_1'); // true/false
```

### View Scheduled Tasks
```bash
php artisan schedule:list

# Output:
# 0 0 * * * notifications:cleanup ........... Next Due: 8 hours from now
```

### Debug Notification Count
```php
// In Controller or Tinker
$user = auth()->user();
$systemNotifications = $user->notifications()->count();
$messages = Message::where('user_id', $user->id)->count();

echo "System: {$systemNotifications}, Messages: {$messages}";
```

## Troubleshooting

### Issue: "New" badges not disappearing
**Solution**: Check server timezone
```php
// config/app.php
'timezone' => 'Asia/Manila', // Set correct timezone
```

### Issue: Old notifications not deleting
**Solution**: Verify scheduler is running
```bash
# Check last run
tail -f storage/logs/laravel.log | grep notifications:cleanup

# Manually run cleanup
php artisan notifications:cleanup
```

### Issue: Cache not working
**Solution**: Run migration and check permissions
```bash
php artisan migrate
chmod -R 775 storage/framework/cache
```

### Issue: Tab persistence not working
**Solution**: Verify hidden input exists in form
```html
<input type="hidden" name="tab" value="notification">
```

## Best Practices

1. **Regular Monitoring**: Check cleanup logs weekly
2. **Cache Testing**: Test cache on staging before production
3. **Timezone Accuracy**: Ensure server timezone matches business timezone
4. **Database Maintenance**: Run `php artisan optimize` monthly
5. **Backup Before Deploy**: Always backup database before deploying changes

## Future Enhancements

### Possible Additions
- [ ] Real-time notifications via Pusher/WebSockets
- [ ] Email notification tracking (sent/opened/clicked)
- [ ] Notification categories/tags
- [ ] Bulk actions (mark all as read, delete selected)
- [ ] Export notification history
- [ ] Notification preferences per user

## Security Considerations

- ✅ **User Isolation**: Each user only sees their own notifications
- ✅ **Cache Security**: Cache keys include user IDs to prevent leaks
- ✅ **SQL Injection**: Using Eloquent ORM prevents injection
- ✅ **XSS Protection**: Blade automatically escapes output

## Conclusion

This comprehensive notification system provides:
- Unified view of all notification types
- Intelligent "New" badge logic (24-hour threshold)
- Automatic cleanup (7-week retention)
- Smart caching for performance
- Shared hosting compatibility
- Tab persistence for better UX

The system is production-ready and optimized for Hostinger shared hosting environments.
