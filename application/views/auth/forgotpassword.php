<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-2">Forgot Password</h1>
                            <h4 class="mb-4"><?= $this->session->userdata('reset_email'); ?></h4>
                        </div>

                        <form class="user" method="post" action="<?= base_url('auth/risetpassword'); ?>">
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" id="new_password1" name="new_password1" placeholder="New Password">
                                <?= form_error('new_password1', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" id="new_password2" name="new_password2" placeholder="Repeat Password">
                                <?= form_error('new_password2', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-user btn-block">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>