<?php require_once 'header.php' ?>

    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/main.php">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Articles</li>
            </ol>

            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/add_articles.php">Add new</a>
                </li>
            </ol>
            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Articles list
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Sub title</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Title</th>
                                <th>Sub title</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php $articles = getArticles(); ?>
                            <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?= $article['title'] ?></td>
                                <td><?= substr($article['sub_title'], 0, 20); ?></td>
                                <td><?= $article['created_at'] ?></td>
                                <td>
                                    <a href="javascript:void(0)">Edit</a><br>
                                    <a href="javascript:void(0)">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
        </div>
        <!-- /.container-fluid-->

<?php require_once 'footer.php' ?>