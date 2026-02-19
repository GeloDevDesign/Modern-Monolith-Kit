<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
<link rel="icon" href="{{ asset('images/site-icon.png') }}" type="image/x-icon">
  @routes
  @vite(['resources/css/app.css', 'resources/js/app.ts'])
  @inertiaHead
  <script>
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage))) {
      document.documentElement.classList.add('light');
    } else {
      document.documentElement.classList.remove('light');
    }
  </script>
</head>

<body>
  @inertia
</body>

</html>
