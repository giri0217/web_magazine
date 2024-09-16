<style>
    .magazine-item {
        height: 18em;
        width: 28em;
        border-radius: 32px;
        box-shadow: 5px 8px #888888;
        transition: transform 0.3s;
    }

    .magazine-item:hover {
        transform: translate(0, -4px);
        background: #a5a5a521;
    }

    .magazine-cover {
        width: 9em;
    }

    .text-purple {
        color: #131214;
    }

    .col-auto {
        max-width: calc(100% - 12em) !important;
    }

    .card-outline {
        /* Add your card outline styles here */
    }

    .list-group-item {
        /* Add your list group item styles here */
    }

    /* Additional CSS styles can be added here */
</style>

<?php if (!isset($_GET['q'])): ?>
    <!-- <h1>Welcome to <?php echo $_settings->info('name') ?></h1> -->
<?php else: ?>
    <h1>Search Result for <b>"<?= $_GET['q'] ?>"</b> keyword.</h1>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <?php
        $search = "";
        if (isset($_GET['q'])) {
            $search = " and (title LIKE '%{$_GET['q']}%' OR description LIKE '%{$_GET['q']}%' or (category_id in (SELECT id FROM `category_list` where name LIKE '%{$_GET['q']}%' or  description LIKE '%{$_GET['q']}%' and `status` = 1 )) or (user_id in (SELECT id FROM `users` where CONCAT(firstname,' ',middlename, ' ',lastname) LIKE '%{$_GET['q']}%' or  username LIKE '%{$_GET['q']}%' and `status` = 1 ))) ";
        }
        $users_qry = $conn->query("SELECT id,username FROM `users` where id in (SELECT user_id from `magazine_list` where `status` = 1 {$search}) ");
        $user_res = $users_qry->fetch_all(MYSQLI_ASSOC);
        $user_arr = array_column($user_res, 'username', 'id');

        $category = $conn->query("SELECT * FROM `category_list` where id in (SELECT category_id from `magazine_list` where `status` = 1 {$search})");
        $category_res = $category->fetch_all(MYSQLI_ASSOC);
        $category_arr = array_column($category_res, 'name', 'id');
        $magazines = $conn->query("SELECT * FROM `magazine_list` where `status` = 1 {$search} order by unix_timestamp(date_created) desc");
        $count = 0;
        while ($row = $magazines->fetch_assoc()) :
            $row['description'] = strip_tags(html_entity_decode($row['description']));
            ?>
            <div class="col-md-6">
                <a href="./?page=view_magazine&id=<?= $row['id'] ?>" class="list-group-item text-decoration-none text-dark magazine-item">
                    <div class="w-100 d-flex flex-nowrap mx-0">
                        <div class="col-auto">
                            <img src="<?= validate_image($row['banner_path']) ?>" alt="" class="float-left m-1 magazine-cover">
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="col-12">
                                <h3 class="text-purple" style="height: 110px;"><?= $row['title'] ?></h3>
                                <!-- <hr class="border-primary mb-0" style="margin-left: -172px; margin-top: 67px;"> -->
                                <div class="w-100 justify-content-between align-items-top" style="">
                                    <div class="d-block mb-3"><span class="text-muted">Category: <?= ucwords(isset($category_arr[$row['category_id']]) ? $category_arr[$row['category_id']] : "") ?></span></div>
                                    <div class="d-block">
                                        <span class="text-muted mr-2 d-block w-100" style="">Created by: <?= isset($user_arr[$row['user_id']]) ? $user_arr[$row['user_id']] : 'N/A' ?></span>
                                        <span class="text-muted"><i class="fa fa-calendar-day"></i> <?= date('Y-m-d H:i', strtotime($row['date_created'])) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a><br>
            </div>
            <?php
            $count++;
            if ($count % 2 == 0) {
                echo '</div><div class="row">';
            }
        endwhile;
        ?>
        <?php if ($magazines->num_rows < 1) : ?>
            <center><span class="text-muted">No Magazine Listed Yet.</span></center>
        <?php endif; ?>
    </div>
</div>
