<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/all.min.css" rel="stylesheet">
    <title>Splash Screen</title>
    <style>
        #splash {
            position: absolute;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #6236FF, #8E2DE2);
            height: 100vh;
            width: 100%;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 1s;
        }

        #splash img {
            width: 50vw;
            /* Responsive width */
            max-width: 300px;
            /* Ensures it doesn't get too large */
            animation: zoomIn 2s ease-in-out;
        }

        @keyframes zoomIn {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        #splash .spinner-border {
            width: 3rem;
            height: 3rem;
            margin-top: 20px;
        }

        #splash.fade {
            opacity: 0;
        }
    </style>
</head>

<body>
    <div id="splash">
        <img alt="Logo" class="logo" src="app/canciones/caratulas/Portada.jpg" />
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('splash').classList.toggle('fade');
            setTimeout(() => {
                window.location.href = 'inicio.php';
            }, 1000); // Wait for the fade-out transition to complete
        }, 2000);
    </script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>