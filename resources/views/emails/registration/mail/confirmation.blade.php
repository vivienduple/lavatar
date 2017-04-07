@component('mail::message')
# Introduction

Merci de votre inscription sur Lavatar et bienvenue dans la communauté!

Si vous n'êtes pas à l'origine de cette action, contactez-nous à contact@lavatar.com.

@component('mail::button', ['url' => url('/')])
Ouvrir Lavatar
@endcomponent

Thanks,<br>
{{ "The Lavatar Team" }}
@endcomponent
