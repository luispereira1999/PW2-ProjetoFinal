<!-- PRINCIPAL: post (informações, ações) -->
<main>
    <section class="full-post__header-wrapper">
        <div>
            <h2 class="full-post__title"><?= $post->title; ?></h2>
            <h3 class="full-post__name"><?= $post->post_user_name; ?></h3>
            <h3 class="full-post__date"><?= $post->date; ?></h3>
        </div>
    </section>

    <section data-post="<?= $post->post_id; ?>" class="full-post__main-wrapper">
        <div>
            <p><?= $post->description; ?></p>
        </div>

        <div class="full-post__interactions">
            <div class="full-post__votes">
                <span class="full-post__vote" data-vote="upvote"><i class="full-post__icon fas fa-heart" <?php if ($post->vote_user_id == $userLoggedId && $post->vote_type_id == 1) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Up Vote"></i></span>
                <label class="full-post__votes-amount"><?= $post->votes_amount; ?></label>
                <span class="full-post__vote" data-vote="downvote"><i class="full-post__icon fas fa-heart-broken" <?php if ($post->vote_user_id == $userLoggedId && $post->vote_type_id == 2) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Down Vote"></i></span>
            </div>

            <span data-toggle="tooltip" data-placement="bottom" title="Comentários"><i class="fas fa-comment full-post__icon"></i></span>
            <label class="full-post__comments-amount"><?= $post->comments_amount; ?></label>
        </div>
    </section>
</main>

<!-- COMENTÁRIOS: informações, ações -->
<section class="comments">
    <span class="comment__new" data-toggle="modal" data-target="#newComment"><i class="fas fa-plus comment__new__icon"></i> Novo Comentário</span>
    <?php require_once("components/new-comment-component.php"); ?>

    <hr>

    <div class="comments__content-wrapper">
        <ul class="comments__items">

            <?php
            if (isset($comments)) :
                for ($current = 0; $current < count($comments); $current++) : ?>

                    <!-- comentário -->
                    <?php
                    $comment = $comments[$current];
                    require("components/comment-component.php");
                    ?>

                <?php endfor; ?>
            <?php endif; ?>

        </ul>
    </div>
</section>