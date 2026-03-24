<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'EarnRol' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f6fa;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f6fa;padding:40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#1a2535;padding:30px 40px;border-radius:16px 16px 0 0;text-align:center;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto;">
                                <tr>
                                    <td style="width:36px;height:36px;background-color:#e05a3a;border-radius:8px;text-align:center;vertical-align:middle;">
                                        <span style="color:#ffffff;font-weight:bold;font-size:18px;line-height:36px;">E</span>
                                    </td>
                                    <td style="padding-left:12px;">
                                        <span style="color:#ffffff;font-weight:bold;font-size:22px;">{{ \App\Models\Setting::get('app_name', 'EarnRol') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background-color:#ffffff;padding:40px;border-left:1px solid #e8eaf0;border-right:1px solid #e8eaf0;">
                            <div style="color:#1a1a2e;font-size:15px;line-height:1.7;">
                                {!! nl2br(e($body)) !!}
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f9fafb;padding:24px 40px;border-radius:0 0 16px 16px;border:1px solid #e8eaf0;border-top:none;text-align:center;">
                            <p style="color:#9ca3af;font-size:12px;margin:0;">
                                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('app_name', 'EarnRol') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
