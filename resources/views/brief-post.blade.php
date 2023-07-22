<!-- DEFINIÇÃO: template dos posts que aparecem na página principal e na página do perfil -->

<div data-post="{{ $post->post_id }}" class="width-3">

    <div class="brief-posts__title-wrapper">
        <h3 class="brief-posts__title"><a class="brief-posts__link" href="/posts/{{ $post->post_id }}">{{ $post->title }}</a></h3>
    </div>

    <div>
        <h5 class="brief-posts__name"><a class="brief-posts__link" href="/profile/{{ $post->post_user_id }}">{{ $post->post_user_name }}</a></h5>
        <h5 class="brief-posts__date">{{ $post->date }}</h5>
    </div>

    <p class="brief-posts__description">{{ $post->description }}</p>

</div>