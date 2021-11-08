<div class="col-lg-4">
    <form id="catagory" method="POST" action="includes/categoriescrud.php">
        <div class="card bg-light rounded-0 p-4">
            <div class="card-body">
                <h5 class="card-title text-uppercase text-center">Create</h5>
                <div class="form-group">
                    <label for="catname">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Sport...">
                    <span class="error"><?= isset($_SESSION['error']['name']) ? $_SESSION['error']['name'] : "" ?></span>
                </div>
                <div class="form-group">
                    <label for="catdesc">Description</label>
                    <textarea class="form-control" name="desc" rows="3"></textarea>
                    <span class="error"><?= isset($_SESSION['error']['description']) ? $_SESSION['error']['description'] : "" ?></span>
                </div>

                <input type="submit" value="create" name="create" class="text-uppercase w-100 btn btn-primary">

            </div>
        </div>
    </form>

    <?php unset($_SESSION['error']) ?>
</div>