<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
            const email = document.querySelector("#email").value;
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!regex.test(email)) {
                alert("Adresse email invalide.");
                e.preventDefault();
            }
        });
    </script>
 
</body>
</html>