<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Vehicle Assigned</title>


</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif; color: #333333;">


    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color: #f4f6f8; padding: 40px 15px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 10px; overflow: hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #1f2937; padding: 25px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                                Vehicle Assigned
                            </h1>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 35px 30px;">

                            <h2 style="margin-top: 0; font-size: 20px; color: #1f2937;">
                                Hello {{ $user->name }},
                            </h2>

                            <p style="font-size: 15px; line-height: 1.7; color: #555555;">
                                A vehicle has been successfully assigned to you.
                                Below are the details of your assigned vehicle:
                            </p>

                            {{-- Vehicle Details --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin-top: 25px; border: 1px solid #e5e7eb; border-radius: 8px;">

                                <tr>
                                    <td
                                        style="padding: 14px 16px; border-bottom: 1px solid #e5e7eb; font-weight: bold;">
                                        Vehicle Model
                                    </td>

                                    <td
                                        style="padding: 14px 16px; border-bottom: 1px solid #e5e7eb; text-align: right;">
                                        {{ $vehicle->model }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding: 14px 16px; border-bottom: 1px solid #e5e7eb; font-weight: bold;">
                                        Plate Number
                                    </td>

                                    <td
                                        style="padding: 14px 16px; border-bottom: 1px solid #e5e7eb; text-align: right;">
                                        {{ $vehicle->plate_number }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 14px 16px; font-weight: bold;">
                                        Vehicle ID
                                    </td>

                                    <td style="padding: 14px 16px; text-align: right;">
                                        {{ $vehicle->id }}
                                    </td>
                                </tr>

                            </table>

                            <p style="margin-top: 25px; font-size: 15px; line-height: 1.7; color: #555555;">
                                Please make sure you review the vehicle information
                                before starting your assigned duties.
                            </p>

                            <p style="font-size: 15px; line-height: 1.7; color: #555555;">
                                If you believe this assignment was made by mistake,
                                please contact the administrator.
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 30px; text-align: center;">

                            <p style="margin: 0; font-size: 13px; color: #9ca3af;">
                                This is an automated message. Please do not reply directly to this email.
                            </p>

                            <p style="margin: 8px 0 0; font-size: 13px; color: #9ca3af;">
                                &copy; {{ date('Y') }} Fleet Tracking System. All rights reserved.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>


</body>

</html>
