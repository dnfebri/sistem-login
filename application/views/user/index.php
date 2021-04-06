
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row">
                    <!-- Page Heading -->
                        <div class="clo-lg-6 mr-3">
                            <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
                        </div>

                        <div class="clo-lg-6">
                            <?= $this->session->flashdata('massage'); ?>
                        </div>
                    </div>

                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" alt="<?= $user['name']; ?>" style="width: 100%; max-heght: 200px;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $user['name']; ?></h5>
                                    <p class="card-text"><?= $user['email']; ?></p>
                                    <p class="card-text"><small class="text-muted"><?= date('d F Y', $user['date_created']); ?></small></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            