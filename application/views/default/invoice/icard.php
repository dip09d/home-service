<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID Card - HTML to PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Single unified ID card with all information on one view */
        .id-card {
            width: 8.5cm;
            height: 5.4cm;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            position: relative;
        }

        /* Left section - Photo and Basic Info */
        .card-left {
            width: 50%;
            background: white;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-right: 3px solid #ff8c42;
        }

        .profile-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #ff8c42;
            background: #e0e0e0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .employee-name {
            font-size: 10px;
            font-weight: 700;
            color: #ff8c42;
            text-align: center;
            line-height: 1.2;
        }

        .employee-position {
            font-size: 8px;
            color: #666;
            text-align: center;
            font-weight: 500;
        }

        .employee-id {
            font-size: 7px;
            color: #333;
            font-weight: 600;
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #ff8c42;
        }

        .qr-code {
            width: 40px;
            height: 40px;
            background: #f0f0f0;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6px;
            color: #999;
            margin-top: 2px;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Right section - All company and contact info */
        .card-right {
            width: 50%;
            background: #1a2b3d;
            color: white;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .company-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ff8c42;
        }

        .company-logo {
            width: 24px;
            height: 24px;
            background: #ff8c42;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 11px;
            color: white;
            flex-shrink: 0;
        }

        .company-info {
            display: flex;
            flex-direction: column;
        }

        .company-name {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .company-tagline {
            font-size: 6px;
            color: #b0b8c1;
            letter-spacing: 0.5px;
        }

        .info-section {
            margin: 4px 0;
        }

        .info-title {
            font-size: 6px;
            font-weight: 600;
            color: #ff8c42;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 2px 0;
            font-size: 6px;
            color: #ccc;
        }

        .info-icon {
            width: 8px;
            height: 8px;
            background: #ff8c42;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .card-footer {
            border-top: 1px solid #ff8c42;
            padding-top: 4px;
            font-size: 5px;
            color: #b0b8c1;
            text-align: center;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .id-card {
                box-shadow: none;
                margin: 0;
                page-break-inside: avoid;
            }
        }

        @media screen {
            .title {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
                font-size: 18px;
                font-weight: 600;
            }
        }
    </style>
</head>
<body>
    <div class="container">
       
        
        <div class="id-card">
            <!-- Left Section -->
            <div class="card-left">
                <div class="profile-photo">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Crect fill='%23e0e0e0' width='200' height='200'/%3E%3Ccircle cx='100' cy='60' r='35' fill='%23999'/%3E%3Cellipse cx='100' cy='180' rx='80' ry='40' fill='%23999'/%3E%3C/svg%3E" alt="Profile">
                </div>
                <div class="employee-name">Martin D'Silva</div>
                <div class="employee-position">Project Manager</div>
                <div class="employee-id">ID: F05454</div>
                <div class="qr-code">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='white' width='100' height='100'/%3E%3Crect x='10' y='10' width='30' height='30' fill='black'/%3E%3Crect x='60' y='10' width='30' height='30' fill='black'/%3E%3Crect x='10' y='60' width='30' height='30' fill='black'/%3E%3Crect x='30' y='30' width='10' height='10' fill='black'/%3E%3Crect x='60' y='40' width='8' height='8' fill='black'/%3E%3C/svg%3E" alt="QR Code">
                </div>
            </div>

            <!-- Right Section -->
            <div class="card-right">
                <div>
                    <div class="company-header">
                        <div class="company-logo">M</div>
                        <div class="company-info">
                            <div class="company-name">MICROBASE</div>
                            <div class="company-tagline">TECHNOLOGIES</div>
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="info-title">Contact</div>
                        <div class="info-row">
                            <span class="info-icon"></span>
                            <span>+91 2245 6789</span>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"></span>
                            <span>martin@microbase.com</span>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"></span>
                            <span>microbastech.web</span>
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="info-title">Validity</div>
                        <div class="info-row">
                            <span class="info-icon"></span>
                            <span>Valid: 12-Feb-2023</span>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"></span>
                            <span>Expiry: 11-Feb-2026</span>
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="info-title">Address</div>
                        <div class="info-row">
                            <span class="info-icon"></span>
                            <span>3-2, Canal Road, Mumbai 400 959</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    © 2026 MICROBASE TECHNOLOGIES. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>

</body>
</html>
