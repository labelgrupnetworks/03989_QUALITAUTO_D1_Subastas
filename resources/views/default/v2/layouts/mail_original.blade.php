<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>

<head>

</head>

<body>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;

        }

        p.user {
            font-weight: 900;
            font-style: italic;
        }

        p.title_adj {
            font-size: 20px;
            text-align: center;
            font-weight: 900;
        }

        .img-email {
            max-height: 100%;
        }
    </style>

    <table border="0" width="600">
        <tr style="background-color: #white;">
            <th style="padding-top:30px;padding-bottom:15px;" height="67" colspan="5">
                <a href="{{ Config::get('app.url') }}">
                    <img class="img-responsive img-email"
                        src="{{ Config::get('app.url') }}/themes/{{ Config::get('app.theme') }}/assets/img/logo.png"
                        alt="Header">
                </a>
            </th>
        </tr>
        <tr>
            <td style="padding:20px 10px;font-family:'Helvetica',Arial,sans-serif;font-size:14px;color: #6e6d6d;"
                colspan="5" bgcolor="white">
                @yield('content')
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <table border="0" width="600" bgcolor="#D2E2F2">
                    <tr>
                        <th
                            style="color:#fff;font-size:11px;font-family:'Helvetica',Arial,sans-serif;font-weight:normal;padding-top:16px;padding-bottom:16px;">
                            &copy; <?= \Config::get('app.name') ?> <?= date('Y') ?>
                        </th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
