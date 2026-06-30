<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class NotificationController extends Controller
{
    /** Halaman daftar semua notifikasi milik user yang login */
    public function index(Request $request): View
    {
        $user          = auth()->user();
        $filterType    = $request->get('type');
        $filterStatus  = $request->get('status', 'all'); // all | unread | read

        $query = $user->notifications();

        if ($filterType) {
            $query->where('data->type', $filterType);
        }

        if ($filterStatus === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filterStatus === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->latest()->paginate(20);
        $unreadCount   = $user->unreadNotifications()->count();

        // Tipe notif unik untuk filter dropdown
        $types = $user->notifications()
            ->reorder()
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.type')) as type")
            ->distinct()
            ->pluck('type')
            ->filter()
            ->values();

        return view('page.notifications.index', [
            'activeNav'     => 'notifications',
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
            'filterType'    => $filterType,
            'filterStatus'  => $filterStatus,
            'types'         => $types,
        ]);
    }

    /** Tandai satu notifikasi sebagai sudah dibaca */
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /** Tandai semua notifikasi sebagai sudah dibaca */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /** Hapus satu notifikasi */
    public function destroy(string $id): RedirectResponse
    {
        auth()->user()->notifications()->findOrFail($id)->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    /** Hapus semua notifikasi yang sudah dibaca */
    public function clearRead(): RedirectResponse
    {
        auth()->user()->readNotifications()->delete();

        return back()->with('success', 'Notifikasi yang sudah dibaca dihapus.');
    }
}
