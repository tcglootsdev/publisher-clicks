@php
    $avFields['user.r'] = \App\Models\User::getAvailableFields('web', $user->role, 'r');
    $user = \App\Helpers\Utils::onlyKeysInArray($user->toArray(), $avFields['user.r']);
@endphp

<script>
    window.TCGLootsWebUserInfo = {{ Js::from($user) }};
</script>