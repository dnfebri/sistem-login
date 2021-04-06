<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <h2>Total User : <b><?= $total; ?></b></h2>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">User role</th>
                <th scope="col">Active</th>
                <th scope="col">Created</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($rowuser as $row) : ?>
                <tr>
                    <th scope="row"><?= $i; ?></th>
                    <td><img src="<?= base_url('assets/img/profile/') . $row['image']; ?>" alt="" width="100"></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['role']; ?></td>
                    <td>
                        <?php if ($row['is_active'] == 1) :
                            echo "Active";
                        else :
                            echo "Not Aktive";
                        endif; ?>
                    </td>
                    <td><?= $row['date_created']; ?></td>
                    <td>
                        <a href="" class="badge badge-success">Edit</a>
                        <a href="" class="badge badge-danger" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->