<!-- resources/views/emails/confirmation.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto max-w-md mt-10 p-6 bg-white rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-6">Email Confirmation</h2>

        <p class="mb-4">Hi {{ $user['username'] }},</p>

        <p class="mb-4">Thanks for signing up! To complete your registration, please click the link below:</p>

        <a href="{{ route('confirm.email', ['token' => $user['confirmation_token'] ]) }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800">
            Confirm Email
        </a>

        <p class="mt-4">If the button doesn't work, you can also click on the link below:</p>
        <a href="{{ route('confirm.email', ['token' => $user['confirmation_token'] ]) }}"
           class="text-blue-500 hover:underline">{{ route('confirm.email', ['token' => $user['confirmation_token'] ]) }}</a>

        <p class="mt-6">Thank you!</p>
    </div>

</body>
</html>
