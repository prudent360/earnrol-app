<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(string $tab = 'general')
    {
        // Get all settings for the current group/tab
        $settings = Setting::where('group', $tab)->get()->pluck('value', 'key')->toArray();

        // Specific handling for Email/SMTP tab
        if ($tab === 'smtp') {
            $settings = array_merge([
                'mail_driver' => 'smtp',
                'mail_host' => '',
                'mail_port' => '587',
                'mail_encryption' => 'tls',
                'mail_username' => '',
                'mail_password' => '',
                'mail_from_name' => 'Reenite',
                'mail_from_address' => 'hello@reenite.com',
            ], $settings);
        }

        return view('admin.settings.index', compact('tab', 'settings'));
    }

    /**
     * Update settings for a specific group.
     */
    public function update(Request $request, string $tab)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Determine type (simple logic for now)
            $type = 'string';
            if (is_bool($value)) $type = 'boolean';
            if (is_numeric($value) && !is_string($value)) $type = 'integer';
            if (is_array($value)) $type = 'json';

            Setting::set($key, $value, $type, $tab);
        }

        return redirect()->route('admin.settings.index', ['tab' => $tab])
            ->with('success', ucfirst($tab) . ' settings updated successfully.');
    }

    /**
     * Send a test email to verify SMTP configuration.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            // In a real application, you would use a Mailable class.
            // For now, we use Mail::raw to verify the connection.
            \Illuminate\Support\Facades\Mail::raw('This is a test email to verify your SMTP configuration in EarnRol.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('SMTP Verification - EarnRol');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $request->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
}
