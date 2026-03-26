<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #1a1a2e;
        }
        .certificate {
            width: 100%;
            height: 100%;
            position: relative;
            padding: 60px 80px;
            box-sizing: border-box;
        }
        .border-frame {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #1a2535;
            border-radius: 8px;
        }
        .border-inner {
            position: absolute;
            top: 26px;
            left: 26px;
            right: 26px;
            bottom: 26px;
            border: 1px solid #e05a3a;
            border-radius: 4px;
        }
        .content {
            position: relative;
            z-index: 1;
            text-align: center;
        }
        .app-name {
            font-size: 16px;
            font-weight: 700;
            color: #e05a3a;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        .title {
            font-size: 38px;
            font-weight: 300;
            color: #1a2535;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 40px;
        }
        .presented-to {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .student-name {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            border-bottom: 2px solid #e05a3a;
            display: inline-block;
            padding-bottom: 8px;
            margin-bottom: 30px;
        }
        .description {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.8;
            max-width: 500px;
            margin: 0 auto 40px;
        }
        .cohort-name {
            font-weight: 700;
            color: #1a2535;
        }
        .footer {
            margin-top: 30px;
        }
        .footer-row {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-row td {
            width: 33%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .footer-label {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .footer-value {
            font-size: 12px;
            color: #1a1a2e;
            font-weight: 600;
        }
        .verify-url {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-frame"></div>
        <div class="border-inner"></div>

        <div class="content">
            <div class="app-name">{{ $appName }}</div>

            <div class="title">Certificate</div>
            <div class="subtitle">of Completion</div>

            <div class="presented-to">This is proudly presented to</div>
            <div class="student-name">{{ $user->name }}</div>

            <div class="description">
                For successfully completing the cohort<br>
                <span class="cohort-name">"{{ $cohort->title }}"</span>
            </div>

            <div class="footer">
                <table class="footer-row">
                    <tr>
                        <td>
                            <div class="footer-label">Date Issued</div>
                            <div class="footer-value">{{ $certificate->issued_at->format('F d, Y') }}</div>
                        </td>
                        <td>
                            <div class="footer-label">Certificate No.</div>
                            <div class="footer-value">{{ $certificate->certificate_number }}</div>
                        </td>
                        <td>
                            <div class="footer-label">Issued By</div>
                            <div class="footer-value">{{ $appName }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="verify-url">
                Verify at: {{ $verifyUrl }}
            </div>
        </div>
    </div>
</body>
</html>
