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

            <hr />
            <?php if (count($data) > 0) : ?>
                <?php foreach ($data as $key => $item) : ?>
                    <a href="<?php echo base_url('notification/read/' . $item->id) ?>" class="list-link">
                        <div class="row list-content">
                            <div class="col">
                                <p style="<?php echo ($item->is_read == '0') ? 'font-weight: bold;' : '' ?>"><?php echo $item->description ?></p>
                                <small class="notif-time-ago"><?php echo $item->created_date ?></small>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <?php echo $this->pagination->create_links() ?>
            <?php else : ?>
                <div class="text-center">
                    <img src="<?= base_url('themes/_public/img/notification-empty.png') ?>" alt="No Notification" class="img-fluid" style="width: 350px;">
                    <h6>You have no notification</h6>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>