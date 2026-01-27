<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Area</title>

    {{-- Bootstrap / CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    {{-- HEADER / NAVBAR --}}
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">FTM Society - Member</span>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="py-4">
        @yield('content')
    </main>

</body>
</html>
