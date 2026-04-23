<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use App\Models\Chat;
use App\Models\Report;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // log mail deliveries so we can debug delivery timing
        Event::listen(MessageSent::class, function (MessageSent $event) {
            $to = implode(',', array_keys($event->message->getTo() ?? []));
            Log::info("Mail sent to {$to} subject={$event->message->getSubject()}");
        });

        // Inject jumlah unread chat ke sidebar guru secara otomatis
        View::composer(
            'layouts.partials.teacher-sidebar-content',
            function (\Illuminate\View\View $view) {
                $totalUnreadChats = 0;

                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->isTeacher()) {
                        // Hitung total pesan murid yang belum dibaca,
                        // hanya untuk laporan yang diklaim oleh guru ini
                    $totalUnreadChats = Chat::where('sender_type', 'student')
                            ->where('is_read', false)
                            ->whereHas('report', function ($q) use ($user) {
                                $q->whereNotNull('email_verified_at')
                                  ->where('claimed_by', $user->id);
                            })
                            ->count();
                    }
                }

                $view->with('totalUnreadChats', $totalUnreadChats);
            }
        );
    }
}
