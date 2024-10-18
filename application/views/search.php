<style type="text/css">
    .list-link {
        color: #777;
    }

    .list-content {
        margin-bottom: 15px;
    }

    .list-content p {
        margin-bottom: 0;
    }

    .list-content small {
        color: #888;
    }
</style>

<section id="user">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <?php if (count($data) > 0) : ?>
                <?php foreach ($data as $key => $item) : ?>
                    <a href="#" class="list-link">
                        <div class="row list-content">
                            <div class="col">
                                Content in here...
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No data found</p>
            <?php endif; ?>
        </div>
    </div>
</section>