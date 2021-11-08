<div class="container mt-5 d-flex justify-content-center">
    <div class="card p-3 bg-light">
        <div class="d-flex align-items-center">
            <div class="image"> 
                <img src="<?= $author_profile ?>" style="object-fit:cover;" class="rounded" width="155" height="155"> 
            </div>
            <div class="ml-3 w-100">
                <div class="text-center">
                    <h4 class="mb-0 mt-0"><?= ucwords($author_name) ?></h4>
                    <span class="text-uppercase fs-12 text-success font-weight-bold"><?= $author_role ?></span>
                </div>
                <div class="p-2 mt-2 bg-primary d-flex justify-content-around rounded text-white stats">
                    <div class="d-flex flex-column"> 
                        <span class="followers">Articles</span>
                        <span class="number2"><?= $author_article ?></span> 
                    </div>
                    <div class="d-flex flex-column"> 
                        <span class="articles">Since</span> 
                        <span class="number1"><?= date("m.d.y", strtotime($author_date)) ?></span> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>