<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 20px 10px 40px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

                    <tr>
                        <td style="padding: 40px 30px 40px 30px;">
                            <h2 style="color: #333333; font-size: 22px; margin-bottom: 20px;">Hello</h2>

                            <p style="color: red; font-size: 16px; line-height: 1.6; font-style:bold;">
                                Never Share this code with any body.
                            </p>

                            <p style="color: #666666; font-size: 16px; line-height: 1.6;">
                                Your Password Reset code is {{ $code }}.
                            </p>

                            <p style="color: #666666; font-size: 16px; line-height: 1.6;">
                                If you did not initiate this, kindly ignore this.
                            </p>

                            <p style="color: #666666; font-size: 14px; line-height: 1.6; margin-top: 40px;">
                                Thank you for choosing us.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 20px 30px 20px 30px; border-top: 1px solid #eeeeee;">
                            <p style="margin: 0; color: #999999; font-size: 12px;">
                                &copy; {{ date('Y') }} {{ config("app.name") }}. All rights reserved.
                            </p>
                            <p style="margin: 5px 0 0 0; color: #999999; font-size: 12px;">
                                [ 123 Financial St., Favours, Ibadan.]
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
