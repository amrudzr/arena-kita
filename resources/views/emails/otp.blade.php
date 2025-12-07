<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">
        /* RESET STYLES */
        body { margin: 0; padding: 0; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table { border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; display: block; }
        a { text-decoration: none; color: inherit; }

        /* GENERAL STYLES */
        body, td, th { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333; line-height: 1.6; }

        /* BUTTON STYLE */
        .btn {
            background-color: #000000;
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            mso-padding-alt: 0;
            text-align: center;
        }

        /* RESPONSIVE STYLES */
        @media screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 0 !important; }
            .content-padding { padding: 20px !important; }
            .mobile-font { font-size: 16px !important; }
        }
    </style>
</head>
<body style="background-color: #f4f4f4; margin: 0; padding: 0;">

<!-- HIDDEN PREHEADER TEXT (Text yang muncul di preview inbox sebelum email dibuka) -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    Kode verifikasi Anda adalah {{ $otpCode }}. Jangan bagikan kode ini kepada siapapun.
</div>

<!-- MAIN WRAPPER -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
    <tr>
        <td align="center" style="background-color: #f4f4f4; padding: 40px 0;">

            <!-- EMAIL CONTAINER -->
            <table border="0" cellpadding="0" cellspacing="0" width="600" class="container" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden;" role="presentation">

                <!-- HEADER / LOGO -->
                <tr>
                    <td align="center" style="padding: 40px 0 20px 0;">
                        <!-- Ganti src dengan URL Logo Anda -->
                        <h1 style="margin: 0; font-size: 24px; letter-spacing: 2px; text-transform: uppercase;">{{ config('app.name') }}</h1>
                    </td>
                </tr>

                <!-- CONTENT BODY -->
                <tr>
                    <td class="content-padding" style="padding: 40px; text-align: center;">
                        <h2 style="margin: 0 0 20px 0; font-size: 22px; font-weight: 600; color: #111111;">Verifikasi Akun Anda</h2>

                        <p class="mobile-font" style="margin: 0 0 30px 0; font-size: 16px; color: #555555;">
                            Gunakan kode One-Time Password (OTP) di bawah ini untuk melanjutkan proses verifikasi akun Anda.
                        </p>

                        <!-- OTP CODE BLOCK -->
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; width: 100%;">
                            <tr>
                                <td align="center">
                                    <div style="background-color: #f8f8f8; border: 1px dashed #cccccc; border-radius: 6px; padding: 20px; display: inline-block; min-width: 200px;">
                                        <span style="font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #000000; display: block;">{{ $otpCode }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <br><br>

                        <p class="mobile-font" style="margin: 0 0 10px 0; font-size: 14px; color: #dc2626; font-weight: bold;">
                            ⚠️ Jangan bagikan kode ini kepada siapapun.
                        </p>
                        <p class="mobile-font" style="margin: 0 0 30px 0; font-size: 14px; color: #999999;">
                            Kode ini akan kadaluwarsa dalam 5 menit.
                        </p>

                        <p class="mobile-font" style="margin: 0; font-size: 14px; color: #555555;">
                            Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.
                        </p>
                    </td>
                </tr>
            </table>
            <!-- END EMAIL CONTAINER -->

            <!-- SPACER FOOTER -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                <tr>
                    <td height="40" style="font-size: 0; line-height: 0;">&nbsp;</td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
</html>
