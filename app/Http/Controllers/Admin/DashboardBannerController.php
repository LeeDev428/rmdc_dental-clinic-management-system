<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DashboardBanner;

class DashboardBannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // AdminMiddleware is applied in routes where needed
    }

    public function index()
    {
        $banner = DashboardBanner::latest()->first();
        return view('admin.dashboard_banner', compact('banner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
            $dest = public_path('uploads/dashboard_banners');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $fileName);
            $path = 'uploads/dashboard_banners/' . $fileName;

            $banner = DashboardBanner::latest()->first();
            if ($banner) {
                // Optionally delete previous file if exists
                if ($banner->image_path && file_exists(public_path($banner->image_path))) {
                    @unlink(public_path($banner->image_path));
                }
                $banner->update(['image_path' => $path]);
            } else {
                DashboardBanner::create(['image_path' => $path]);
            }

            return redirect()->back()->with('success', 'Banner updated successfully.');
        }

        return redirect()->back()->with('error', 'No image uploaded.');
    }
}
