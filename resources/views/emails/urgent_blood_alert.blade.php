<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urgent Blood Alert</title>
</head>
<body style="margin: 0; padding: 0; background-color: #faf6f6; font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1d1012;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #faf6f6; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(110,0,0,0.12); border: 1px solid #eee3e4;">
                    <!-- Emergency Header -->
                    <tr>
                        <td bgcolor="#b30000" style="background-color: #b30000; background: radial-gradient(ellipse at 50% 40%, #d40000 0%, #b30000 45%, #6e0000 100%); padding: 28px 28px 34px; color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size: 12px; font-weight: 800; letter-spacing: 3px; color: #ffffff;">&#9679; BE.DONOR</td>
                                    <td align="right" style="font-size: 10px; letter-spacing: 3px; color: #ffd6d6; text-transform: uppercase;">Urgent alert</td>
                                </tr>
                            </table>
                            <div style="height: 26px; line-height: 26px;">&nbsp;</div>
                            <div style="display: inline-block; border: 1px solid rgba(255,255,255,0.6); padding: 6px 14px; border-radius: 20px; font-size: 10px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 14px;">
                                🚨 Urgent Emergency Request
                            </div>
                            <h1 style="margin: 0; font-size: 30px; font-weight: 800; line-height: 1.1; text-transform: uppercase; letter-spacing: 0.5px;">
                                Blood Needed in {{ $bloodRequest->city }}
                            </h1>
                            <p style="margin: 12px 0 0; font-size: 14px; line-height: 1.7; color: #ffecec;">
                                A patient urgently requires <strong>{{ $bloodRequest->blood_group }}</strong> blood.
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <p style="font-size: 15px; margin: 0 0 14px; line-height: 1.6;">
                                Respected <strong>{{ $donor->name }}</strong>,
                            </p>
                            <p style="font-size: 14px; margin: 0 0 26px; color: #5c4f52; line-height: 1.8;">
                                An urgent blood requirement has been reported in your city (<strong>{{ $bloodRequest->city }}</strong>) that matches your blood group (<strong>{{ $donor->blood_group }}</strong>). Your immediate donation could save a life today!
                            </p>

                            <!-- Request Details Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: #fff1f1; border-left: 3px solid #b30000; border-radius: 0 14px 14px 0; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="7">
                                            <tr>
                                                <td width="38%" style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Blood Group:</td>
                                                <td width="62%" style="font-size: 18px; color: #b30000; font-weight: 800;">
                                                    <span style="background: #b30000; color: #ffffff; padding: 4px 14px; border-radius: 20px; display: inline-block;">
                                                        {{ $bloodRequest->blood_group }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Patient Name:</td>
                                                <td style="font-size: 14px; color: #1d1012; font-weight: 600;">{{ $bloodRequest->patient_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Units Needed:</td>
                                                <td style="font-size: 14px; color: #1d1012; font-weight: 600;">{{ $bloodRequest->units_needed }} {{ Str::plural('bottle', $bloodRequest->units_needed) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Hospital / Address:</td>
                                                <td style="font-size: 14px; color: #1d1012; font-weight: 600;">{{ $bloodRequest->hospital }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">City:</td>
                                                <td style="font-size: 14px; color: #1d1012; font-weight: 600;">{{ $bloodRequest->city }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Urgency:</td>
                                                <td style="font-size: 14px; color: #b30000; font-weight: 800;">{{ $bloodRequest->urgency_level }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Contact Person:</td>
                                                <td style="font-size: 14px; color: #1d1012; font-weight: 600;">{{ $bloodRequest->contact_name }}</td>
                                            </tr>
                                            @if($bloodRequest->notes)
                                            <tr>
                                                <td style="font-size: 10px; color: #76666a; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; vertical-align: top;">Additional Note:</td>
                                                <td style="font-size: 13px; color: #4a3d40; font-style: italic;">"{{ $bloodRequest->notes }}"</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Immediate Action Buttons -->
                            <p style="text-align: center; font-size: 10px; font-weight: bold; letter-spacing: 3px; color: #76666a; margin: 0 0 16px;">
                                PLEASE CONTACT THE PATIENT'S ATTENDANT DIRECTLY:
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <table role="presentation" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="border-radius: 30px; background-color: #b30000; text-align: center; margin-right: 10px; display: inline-block;">
                                                    <a href="tel:{{ $bloodRequest->contact_phone }}" style="background-color: #b30000; border: 1px solid #b30000; border-radius: 30px; color: #ffffff; display: inline-block; font-size: 12px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; line-height: 46px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;">
                                                        📞 Call Requester
                                                    </a>
                                                </td>
                                                <td style="width: 12px; display: inline-block;"></td>
                                                <td style="border-radius: 30px; background-color: #17935a; text-align: center; display: inline-block;">
                                                    <a href="https://wa.me/{{ $bloodRequest->whatsapp_number }}?text={{ urlencode('Assalam-o-Alaikum, I received your urgent blood request for ' . $bloodRequest->blood_group . ' in ' . $bloodRequest->city . '. I am a registered volunteer donor.') }}" style="background-color: #17935a; border: 1px solid #17935a; border-radius: 30px; color: #ffffff; display: inline-block; font-size: 12px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; line-height: 46px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;">
                                                        💬 WhatsApp Message
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; color: #76666a; line-height: 1.5; margin: 24px 0 0; text-align: center;">
                                Phone number: <strong style="color: #1d1012;">{{ $bloodRequest->contact_phone }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#6e0000" style="background-color: #6e0000; padding: 22px 24px; text-align: center; font-size: 11px; line-height: 1.7; color: #f3c9c9;">
                            <p style="margin: 0 0 6px;">
                                You are receiving this notification because you signed up as a volunteer donor on <strong style="color: #ffffff;">Blood Donor System</strong> and enabled emergency email alerts.
                            </p>
                            <p style="margin: 0;">
                                Every drop counts. Thank you for your kindness and dedication to saving lives.
                            </p>
                            @if($donor->donor_token)
                            <p style="margin: 10px 0 0;">
                                <a href="{{ route('donors.alerts.disable', $donor->donor_token) }}" style="color: #f3c9c9; text-decoration: underline;">
                                    Turn off emergency email alerts
                                </a>
                            </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
