<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tidak Ditemukan</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <style>
        /* CSS code */
        body {
            background-color:rgb(0, 0, 0);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        main {
            align-items: center;
            display: flex;
            flex-direction: column;
            height: 100vh;
            justify-content: center;
            text-align: center;
        }

        h1 {
            color: #e7ebf2;
            font-size: 12.5rem;
            letter-spacing: .10em;
            margin: .025em 0;
            text-shadow: .05em .05em 0 rgba(0,0,0,.25);
            white-space: nowrap;
        }

        h1 > span {
            animation: spooky 2s alternate infinite linear;
            color: #ed126b;
            display: inline-block;
        }

        h2 {
            color: #e7ebf2;
            margin-bottom: .40em;
        }

        p {
            color: #ccc;
            margin-top: 0;
        }

        @keyframes spooky {
            from {
                transform: translatey(.15em) scaley(.95);
            }

            to {
                transform: translatey(-.15em);
            }
        }
    </style>
</head>
<body>
    <main>
        <h1>4<span><i class="fas fa-ghost"></i></span>4</h1>
        <h2>Error: 404 page not found</h2>
        <p>Sorry, the page you're looking for cannot be accessed</p>
    </main>

    <script>
        // JS code for additional functionality
        document.querySelector("main").addEventListener("click", () => {
            document.body.style.backgroundColor = "#282828";
        });
    </script>
</body>
</html>
