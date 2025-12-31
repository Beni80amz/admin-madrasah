<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Madrasah Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a5f5f 0%, #0d3d3d 50%, #0a2e2e 100%);
            position: relative;
            overflow: hidden;
        }

        /* Animated gears background */
        .gears-container {
            position: absolute;
            right: 5%;
            top: 50%;
            transform: translateY(-50%);
            width: 500px;
            height: 500px;
            opacity: 0.9;
        }

        .gear {
            position: absolute;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gear::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: currentColor;
            border-radius: 50%;
            clip-path: polygon(50% 0%, 61% 10%, 75% 5%, 80% 20%, 95% 25%, 90% 40%,
                    100% 50%, 90% 60%, 95% 75%, 80% 80%, 75% 95%, 61% 90%,
                    50% 100%, 39% 90%, 25% 95%, 20% 80%, 5% 75%, 10% 60%,
                    0% 50%, 10% 40%, 5% 25%, 20% 20%, 25% 5%, 39% 10%);
        }

        .gear::after {
            content: '';
            position: absolute;
            width: 35%;
            height: 35%;
            background: #1a5f5f;
            border-radius: 50%;
            z-index: 1;
        }

        .gear-1 {
            width: 180px;
            height: 180px;
            top: 80px;
            right: 120px;
            color: #4db6ac;
            animation: rotate 8s linear infinite;
        }

        .gear-2 {
            width: 120px;
            height: 120px;
            top: 20px;
            right: 280px;
            color: #26a69a;
            animation: rotate 6s linear infinite reverse;
        }

        .gear-3 {
            width: 100px;
            height: 100px;
            top: 200px;
            right: 20px;
            color: #cddc39;
            animation: rotate 5s linear infinite;
        }

        .gear-4 {
            width: 150px;
            height: 150px;
            top: 280px;
            right: 150px;
            color: #8bc34a;
            animation: rotate 7s linear infinite reverse;
        }

        .gear-5 {
            width: 80px;
            height: 80px;
            top: 350px;
            right: 50px;
            color: #00bcd4;
            animation: rotate 4s linear infinite;
        }

        .gear-6 {
            width: 60px;
            height: 60px;
            top: 150px;
            right: 320px;
            color: #ffeb3b;
            animation: rotate 3s linear infinite reverse;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Content */
        .content {
            position: relative;
            z-index: 10;
            text-align: left;
            padding: 2rem;
            max-width: 600px;
            margin-left: 10%;
        }

        .title {
            font-size: 4rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .message {
            font-size: 1.1rem;
            color: #b2dfdb;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .contact {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .contact strong {
            color: #80cbc4;
        }

        /* Floating particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        .particle:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            left: 20%;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            left: 30%;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            left: 40%;
            animation-delay: 6s;
        }

        .particle:nth-child(5) {
            left: 50%;
            animation-delay: 8s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
                transform: translateY(80vh) scale(1);
            }

            90% {
                opacity: 0.5;
            }

            100% {
                transform: translateY(-10vh) scale(0.5);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .gears-container {
                width: 300px;
                height: 300px;
                right: 2%;
                opacity: 0.5;
            }

            .gear-1 {
                width: 100px;
                height: 100px;
                top: 50px;
                right: 60px;
            }

            .gear-2 {
                width: 70px;
                height: 70px;
                top: 10px;
                right: 150px;
            }

            .gear-3 {
                width: 60px;
                height: 60px;
                top: 120px;
                right: 10px;
            }

            .gear-4 {
                width: 90px;
                height: 90px;
                top: 160px;
                right: 80px;
            }

            .gear-5 {
                width: 50px;
                height: 50px;
                top: 200px;
                right: 20px;
            }

            .gear-6 {
                width: 40px;
                height: 40px;
                top: 80px;
                right: 180px;
            }

            .content {
                margin-left: 5%;
                max-width: 90%;
            }

            .title {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 640px) {
            .gears-container {
                display: none;
            }

            .content {
                text-align: center;
                margin: 0 auto;
                padding: 1rem;
            }

            .title {
                font-size: 2rem;
            }

            .message {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Floating particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Animated gears -->
    <div class="gears-container">
        <div class="gear gear-1"></div>
        <div class="gear gear-2"></div>
        <div class="gear gear-3"></div>
        <div class="gear gear-4"></div>
        <div class="gear gear-5"></div>
        <div class="gear gear-6"></div>
    </div>

    <!-- Main content -->
    <div class="content">
        <h1 class="title">Mohon Maaf!</h1>
        <p class="message">
            Superadmin sedang melakukan Maintenance Aplikasi!<br>
            Silahkan Hubungi Admin KKMI Sukmajaya.
        </p>
        <div class="contact">
            🔧 <strong>Kami akan segera kembali</strong>
        </div>
    </div>
</body>

</html>