<!-- MAIN BODY -->
<div class="text-center mt-5">
    <a href="<?php echo (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST']."/example/";?>"><h1 class="mt-2 text-dark"><strong>AGP</strong></h1>
    <h4><span class="badge black">Recnify</span></h4>
    </a>
</div>
<main>
    <div class="container">
    <!-- id result used only on Registration Response via AJAX CALL ( account/ajax/functionality.js -> registerData() ) -->
        <section class="mt-5" id="result">
            <!-- Dashboard Panel -->
            <div class="row" id="panel">
                <div class="col-md-6 offset-md-3">
                    <!-- Account Login / Signup Form -->
                    <div class="card" id="account">
                    </div>
                </div>
            </div>
        </section>
        <!-- Pagination for test Application via AJAX CALL ( dashboard/ajax/functionality.js -> loadPagination() ) -->
        <section class="mt-5" id="pagination">
        </section>
    </div>
</main>