@component('mail::message')
# Introduction

Bonjour,

Cette adresse mail à été liée à un avatar personnalisé sur notre service (Lavatar), si vous êtes bien à l'origine de
cette action cliquez sur le bouton ci-dessous pour valider cet avatar, sinon, contactez-nous au 0 800 825 845.

@component('mail::button', ['url' => url('/').'/valAvatar/'.$id.'/'.$tocken])
Je valide cet avatar !
@endcomponent

Thanks,<br>
{{ "The Lavatar Team" }}
@endcomponent