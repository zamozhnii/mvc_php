<?php foreach($posts as $post) : ?>
    <div>
        <h2>
            <a href="./post/<?=$post['book_id']?>"><?=$post['book_title']?></a>
        </h2>
        <p>
            <?=$post['book_descr']?>
        </p>
    </div>
<?php endforeach; ?>