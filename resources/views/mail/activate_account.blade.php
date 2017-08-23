Hallo {{ $user->name }},

Willkommen bei {{ config('app.name') }}.

Um Ihrer Anmeldung abzuschließen, klicken Sie bitte auf
{{ route('user.activate', $user->activation_token) }}
@if($user->hasNoPassword())und erstellen ein neues Passwort für Ihren Zugang.@endif

