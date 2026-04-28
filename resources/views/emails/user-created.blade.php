<body style="
  margin:0; 
  padding:0; 
  background: linear-gradient(to bottom, #9b90bc, #f8a5c2);
  background-repeat: no-repeat;
  background-size: cover;
  font-family: Arial, Helvetica, sans-serif;
">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding:70px 50px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff; border-radius:20px; overflow:hidden;">

                    <tr>
                        <td align="center" style="padding:30px 20px 10px 20px;">
                            <img src="{{ config('app.url') }}/public/images/logo.png" width="200" style="display:block;">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:20px;">
                            <img src="{{ config('app.url') }}/public/images/email.png" width="180" style="display:block;">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 30px;">
                            <h2 style="margin:0; font-size:28px; color:#1e1e2f;">
                                Welcome to {{ $company }} 🎉
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:15px 40px;">
                            <p style="margin:0; font-size:16px; color:#444; line-height:24px;">
                                Hello <b>{{ $user->name ?? 'User' }}</b>, <br><br>
                                Your account has been successfully created. Below are your login details:
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 40px 5px 40px;">
                            <p style="margin:0; font-size:15px; color:#666;">
                                Email: <b>{{ $user->email }}</b>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 40px 10px 40px;">
                            <p style="margin:0; font-size:15px; color:#666;">
                                Password: <b>{{ $password }}</b>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:10px 40px;">
                            <p style="margin:0; font-size:13px; color:red;">
                                ⚠️ For security reasons, please change your password after first login.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:20px;">
                            <a href="{{ config('app.url') }}" style="
                                background:#FF3C7E; 
                                color:#ffffff; 
                                text-decoration:none; 
                                padding:14px 35px; 
                                border-radius:30px; 
                                font-size:16px; 
                                font-weight:bold; 
                                display:inline-block;">
                                Login Now
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 40px 20px 40px;">
                            <p style="margin:0; font-size:14px; color:#666;">
                                If you did not create this account, please contact our support team immediately.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size:14px; color:#555; padding-bottom:10px;">
                            © {{ date('Y') }} <b style="color:#0C2340;">{{ $company }}</b> – All rights reserved
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:15px; text-align:center">

                            <a href="#" style="margin:0 8px; display:inline-block;">
                                <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" width="30">
                            </a>

                            <a href="#" style="margin:0 8px; display:inline-block;">
                                <img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" width="30">
                            </a>

                            <a href="#" style="margin:0 8px; display:inline-block;">
                                <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" width="30">
                            </a>

                            <a href="#" style="margin:0 8px; display:inline-block;">
                                <img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" width="30">
                            </a>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>