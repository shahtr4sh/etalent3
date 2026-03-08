<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">

    <title>eTalent - Log in</title>

</head>
<body class="bg-gray-100">


<div class="min-h-screen w-full p-6 flex justify-center items-center">

    <div class="w-full max-w-lg">

        <div class="bg-white border p-8 shadow rounded w-full mb-8">

            <h1 class="mb-6 text-lg text-gray-900 font-thin cropper-center">eTalent Login</h1>

            <form method="POST" action="{{ route('app.login.submit') }}">
                @csrf

                <fieldset class="mb-4">
                    <label class="block text-sm text-gray-900 mb-2">Email address</label>
                    <input id="email" type="email" class="block w-full rounded-sm border bg-white py-2 px-3 text-sm" name="email" required autofocus>
                    @error('email') <div style="color:red">{{ $message }}</div> @enderror
                </fieldset>

                <fieldset class="mb-4">
                    <div class="w-full flex justify-between items-center">
                        <label for="password" class="block text-sm text-gray-900 mb-2">Password</label>
                        </a>
                    </div>
                    <input id="password" type="password" class="block w-full rounded-sm border bg-white py-2 px-3 text-sm" name="password" required>
                    @error('password') <div style="color:red">{{ $message }}</div> @enderror
                </fieldset>

                <div class="pt-1 pb-5 text-sm text-gray-darker font-thin">
                    <label><input class="mr-1" type="checkbox" name="remember" id="remember"> Remember me</label>
                </div>


                <button type="submit" class="block w-full bg-blue-600 text-white rounded-sm py-3 text-sm tracking-wide">
                    Sign in
                </button>
            </form>
        </div>

    </div>

</div>


</body>
</html>
