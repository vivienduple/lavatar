@component('mail::message')
# Introduction

Merci de votre iscription sur Lavatar et bienvenue dans la communauté!

@component('mail::button', ['url' => 'http://http://iparla.iutbayonne.univ-pau.fr/~jthebault001/lavatar-2/public/'])
Button Text
@endcomponent

Thanks,<br>
{{ "The Lavatar Team" }}
@endcomponent
