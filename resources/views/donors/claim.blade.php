<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opening your donor card</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
    <div class="text-center p-4">
        <i class="fa-solid fa-droplet fa-2x text-danger mb-3"></i>
        <h5 class="fw-bold">Hello {{ $donor->name }}</h5>
        <p class="text-muted mb-3">Linking your donor card on this device...</p>
        <a id="go" href="{{ route('donors.public.search', ['blood_group' => $donor->blood_group, 'city' => $donor->city]) }}" class="btn btn-danger">Open my card</a>
    </div>

    <script>
        try {
            localStorage.setItem('my_donor_token', @json($token));
            localStorage.setItem('my_donor_id', @json((string) $donor->id));
        } catch (e) {}
        window.location.replace(document.getElementById('go').href);
    </script>
</body>
</html>