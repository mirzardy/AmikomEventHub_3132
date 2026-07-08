<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>

    <style>
        body {
            margin: 0;
            padding: 40px 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #4f46e5;
            color: #ffffff;
        }

        .container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }

        .header-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-text h1 {
            margin: 0 0 10px;
            font-size: 28px;
            font-weight: 900;
        }

        .header-text p {
            margin: 0;
            color: #e0e7ff;
        }

        .ticket-card {
            overflow: hidden;
            background-color: #ffffff;
            color: #0f172a;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .ticket-top {
            padding: 30px;
            text-align: center;
            background-color: #eef2ff;
            border-bottom: 2px dashed #c7d2fe;
        }

        .ticket-top p {
            margin: 0 0 10px;
            color: #4f46e5;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .ticket-top h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 900;
        }

        .ticket-body {
            padding: 30px;
        }

        .grid {
            width: 100%;
            display: block;
            margin-bottom: 20px;
        }

        .grid-item {
            display: inline-block;
            width: 45%;
            margin-bottom: 20px;
            vertical-align: top;
        }

        .label {
            margin: 0 0 5px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .value {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .qr-section {
            margin-top: 10px;
            padding: 25px;
            text-align: center;
            background-color: #f8fafc;
            border-radius: 20px;
        }

        .qr-container {
            display: inline-block;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .footer {
            padding: 0 30px 30px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }

        .order-id {
            margin: 0;
            color: #1e293b;
            font-family: monospace;
            font-weight: bold;
        }

        .copyright {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Header -->
        <div class="header-text">
            <h1>Pembayaran Berhasil!</h1>
            <p>Tiket Anda telah terbit dan siap digunakan.</p>
        </div>

        <!-- Ticket -->
        <div class="ticket-card">

            <!-- Ticket Header -->
            <div class="ticket-top">
                <p>E-Ticket Resmi</p>
                <h2>{{ $transaction->event->title }}</h2>
            </div>

            <!-- Ticket Body -->
            <div class="ticket-body">

                <div class="grid">
                    <div class="grid-item">
                        <p class="label">Nama Pembeli</p>
                        <p class="value">{{ $transaction->customer_name }}</p>
                    </div>

                    <div class="grid-item">
                        <p class="label">Tanggal & Waktu</p>
                        <p class="value">
                            {{ \Carbon\Carbon::parse($transaction->event->date)->format('d M, H:i') }}
                        </p>
                    </div>

                    <div class="grid-item">
                        <p class="label">Order ID</p>
                        <p class="value">{{ $transaction->order_id }}</p>
                    </div>

                    <div class="grid-item">
                        <p class="label">Lokasi</p>
                        <p class="value">{{ $transaction->event->location }}</p>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="qr-section">
                    <p class="label" style="margin-bottom: 15px;">
                        Scan QR untuk Check-in
                    </p>

                    <div class="qr-container">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($transaction->order_id) }}"
                            alt="QR Code"
                            width="150"
                            height="150"
                            style="display: block;"
                        >
                    </div>

                    <p class="order-id">
                        {{ $transaction->order_id }}
                    </p>
                </div>

            </div>

            <!-- Footer -->
            <div class="footer">
                <p>
                    Mohon tunjukkan E-Ticket ini saat memasuki area acara.
                </p>

                <p class="copyright">
                    &copy; {{ date('Y') }} AmikomEventHub.
                </p>
            </div>

        </div>

    </div>
</body>
</html>