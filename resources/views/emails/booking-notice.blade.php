<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <!--[if mso]>
    <xml>
        <o:officedocumentsettings>
            <o:pixelsperinch>96</o:pixelsperinch>
        </o:officedocumentsettings>
    </xml>
    <![endif]-->
    <title>Welcome to PixInvent 👋</title>
    <link
            href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700"
            rel="stylesheet" media="screen"
    >
    <style>
        .hover-underline:hover {
            text-decoration: underline !important;
        }

        @media (max-width: 600px) {
            .sm-w-full {
                width: 100% !important;
            }

            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }

            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }

            .sm-leading-32 {
                line-height: 32px !important;
            }
        }
    </style>
</head>
<body
        style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #eceff1;"
>
<div role="article" aria-roledescription="email" aria-label="Welcome to PixInvent 👋" lang="en"
     style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;"
>
    <table style="width: 100%; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0"
           cellspacing="0" role="presentation"
    >
        <tr>
            <td align="center"
                style="mso-line-height-rule: exactly; background-color: #eceff1; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;"
            >
                <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td align="center" class="sm-px-24"
                            style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;"
                        >
                            <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="sm-px-24"
                                        style="mso-line-height-rule: exactly; border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 16px; line-height: 24px; color: #626262;"
                                    >
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-bottom: 0; font-size: 20px; font-weight: 600;">
                                            Hi,</p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-top: 24px; margin-bottom: 24px;">
                                            Thank you for choosing our travel agency. Your booking has been
                                            successfully!
                                        </p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-top: 24px; margin-bottom: 24px;">
                                            We are thrilled to be a part of your journey and are now diligently
                                            processing your booking. If you have any questions or concerns, please feel
                                            free to contact us at any time. Your satisfaction is our top priority
                                        </p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-bottom: 0; font-size: 20px; font-weight: 600;">
                                            Booking Details:
                                        </p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-top: 24px; margin-bottom: 24px;">
                                            Order No: {{$booking?->order_no}} <br>
                                            Travel Date: {{$booking?->travel_date}} <br>
                                            Number of Adults: {{$booking?->adult_count}} <br>
                                            Number of Children: {{$booking?->child_count}} <br>
                                        </p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-bottom: 0; font-size: 20px; font-weight: 600;">
                                            Price Details:
                                        </p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-top: 24px; margin-bottom: 24px;">
                                            Total Price: {{$booking?->total_price}} <br>
                                            Total Tax: {{$booking?->total_tax}} <br>
                                            Total Discount: {{$booking?->total_discount}} <br>
                                            Net Amount: {{$booking?->net_amount}} <br>
                                        </p>
                                        <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-top: 32px; padding-bottom: 32px;">
                                                    <div
                                                            style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 1px; background-color: #eceff1; line-height: 1px;"
                                                    >
                                                        &zwnj;
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;">
                                            Regards, <br>{{ config('app.name') }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 20px;"></td>
                                </tr>
                                <tr>
                                    <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 16px;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>

